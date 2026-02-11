<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\BahanBaku;
use App\Models\PembelianBahanBaku;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Halaman utama laporan & dashboard analitik.
     */
    public function index()
    {
        // Statistik cepat untuk dashboard
        $hariIni = now()->toDateString();
        
        $penjualanHariIni = Penjualan::whereDate('tanggal', $hariIni)->sum('total');
        $penjualanBulanIni = Penjualan::whereMonth('tanggal', now()->month)
                                       ->whereYear('tanggal', now()->year)
                                       ->sum('total');
        $transaksiHariIni = Penjualan::whereDate('tanggal', $hariIni)->count();
        $totalMenuTerjual = DetailPenjualan::whereHas('penjualan', function($q) use ($hariIni) {
                                            $q->whereDate('tanggal', $hariIni);
                                        })->sum('jumlah_porsi');
        
        $totalBahan = BahanBaku::count();
        $bahanStokRendah = BahanBaku::whereColumn('stok', '<=', 'stok_minimum')->count();
        $nilaiStok = BahanBaku::select(DB::raw('SUM(stok * harga_beli) as total'))->first()->total ?? 0;
        
        $totalPembelianBulanIni = PembelianBahanBaku::whereMonth('tanggal', now()->month)
                                                    ->whereYear('tanggal', now()->year)
                                                    ->sum('total');

        // Data untuk chart penjualan 7 hari terakhir
        $penjualan7Hari = Penjualan::select(
                                    DB::raw('DATE(tanggal) as tanggal'),
                                    DB::raw('SUM(total) as total'),
                                    DB::raw('COUNT(*) as transaksi')
                                )
                                ->whereBetween('tanggal', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
                                ->groupBy(DB::raw('DATE(tanggal)'))
                                ->orderBy('tanggal')
                                ->get();

        // Top 5 menu terlaris
        $topMenu = DetailPenjualan::select(
                                    'menu_id',
                                    DB::raw('SUM(jumlah_porsi) as total_terjual'),
                                    DB::raw('SUM(subtotal) as total_pendapatan')
                                )
                                ->with('menu')
                                ->groupBy('menu_id')
                                ->orderByDesc('total_terjual')
                                ->limit(5)
                                ->get();

        return view('laporan.index', compact(
            'penjualanHariIni',
            'penjualanBulanIni',
            'transaksiHariIni',
            'totalMenuTerjual',
            'totalBahan',
            'bahanStokRendah',
            'nilaiStok',
            'totalPembelianBulanIni',
            'penjualan7Hari',
            'topMenu'
        ));
    }

    /**
     * Laporan Penjualan dengan filter periode.
     */
    public function penjualan(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $groupBy = $request->input('group_by', 'day'); // day, month, year

        // Query dasar
        $query = Penjualan::with('kasir')
                    ->whereBetween(DB::raw('DATE(tanggal)'), [$startDate, $endDate]);

        // Data summary
        $totalPenjualan = $query->sum('total');
        $totalTransaksi = $query->count();
        $totalPorsi = $query->sum('jumlah_porsi');
        $rataTransaksi = $totalTransaksi > 0 ? $totalPenjualan / $totalTransaksi : 0;

        // Breakdown metode pembayaran
        $pembayaran = $query->select('pembayaran', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(total) as total'))
                            ->groupBy('pembayaran')
                            ->get();

        // Data untuk chart (dikelompokkan sesuai groupBy)
        $groupQuery = Penjualan::whereBetween(DB::raw('DATE(tanggal)'), [$startDate, $endDate]);
        
        switch ($groupBy) {
            case 'day':
                $groupQuery->select(DB::raw('DATE(tanggal) as periode'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as transaksi'));
                $groupQuery->groupBy(DB::raw('DATE(tanggal)'));
                break;
            case 'month':
                $groupQuery->select(DB::raw('DATE_FORMAT(tanggal, "%Y-%m") as periode'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as transaksi'));
                $groupQuery->groupBy(DB::raw('DATE_FORMAT(tanggal, "%Y-%m")'));
                break;
            case 'year':
                $groupQuery->select(DB::raw('YEAR(tanggal) as periode'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as transaksi'));
                $groupQuery->groupBy(DB::raw('YEAR(tanggal)'));
                break;
        }
        
        $chartData = $groupQuery->orderBy('periode')->get();

        // Detail transaksi per periode (tabel)
        $transaksi = Penjualan::with('kasir')
                        ->whereBetween(DB::raw('DATE(tanggal)'), [$startDate, $endDate])
                        ->orderBy('tanggal', 'desc')
                        ->paginate(20);

        return view('laporan.penjualan', compact(
            'startDate', 'endDate', 'groupBy',
            'totalPenjualan', 'totalTransaksi', 'totalPorsi', 'rataTransaksi',
            'pembayaran', 'chartData', 'transaksi'
        ));
    }

    /**
     * Laporan Stok.
     */
    public function stok(Request $request)
    {
        $filter = $request->input('filter', 'all'); // all, low, out-of-stock
        
        $query = BahanBaku::with('resep.menu')->orderBy('nama');

        if ($filter == 'low') {
            $query->whereColumn('stok', '<=', 'stok_minimum');
        } elseif ($filter == 'out') {
            $query->where('stok', '<=', 0);
        }

        $bahan = $query->get();

        // Total nilai stok
        $totalNilaiStok = $bahan->sum(function($item) {
            return $item->stok * $item->harga_beli;
        });

        // Ringkasan per kategori
        $kategoriSummary = BahanBaku::select('kategori', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(stok * harga_beli) as nilai'))
                                    ->groupBy('kategori')
                                    ->get();

        // Riwayat mutasi stok (10 terbaru)
        $mutasi = \App\Models\PemakaianBahanBaku::with('bahanBaku', 'user')
                    ->orderBy('created_at', 'desc')
                    ->limit(50)
                    ->get();

        return view('laporan.stok', compact('bahan', 'filter', 'totalNilaiStok', 'kategoriSummary', 'mutasi'));
    }

    /**
     * Laporan Pengeluaran (Pembelian Bahan).
     */
    public function pengeluaran(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $query = PembelianBahanBaku::with('bahanBaku', 'user')
                    ->whereBetween(DB::raw('DATE(tanggal)'), [$startDate, $endDate]);

        $totalPengeluaran = $query->sum('total');
        $totalTransaksiBeli = $query->count();
        $totalItemBeli = $query->sum('jumlah');

        // Per supplier
        $perSupplier = $query->select('supplier', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(total) as total'))
                            ->groupBy('supplier')
                            ->get();

        // Per bahan
        $perBahan = $query->select('bahan_id', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(jumlah) as total_qty'), DB::raw('SUM(total) as total'))
                        ->with('bahanBaku')
                        ->groupBy('bahan_id')
                        ->orderByDesc('total')
                        ->get();

        // Detail pembelian
        $pembelian = $query->orderBy('tanggal', 'desc')->paginate(20);

        return view('laporan.pengeluaran', compact(
            'startDate', 'endDate',
            'totalPengeluaran', 'totalTransaksiBeli', 'totalItemBeli',
            'perSupplier', 'perBahan', 'pembelian'
        ));
    }
}