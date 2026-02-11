<?php

namespace App\Http\Controllers;

use App\Models\StokFisik;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalisisController extends Controller
{
    /**
     * Halaman utama analisis selisih stok.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $status = $request->input('status', 'all'); // all, normal, melebihi_toleransi

        $query = StokFisik::with('bahanBaku', 'user')
                    ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($status != 'all') {
            $query->where('status', $status);
        }

        $selisihData = $query->orderBy('tanggal', 'desc')->paginate(20);

        // Statistik selisih
        $totalPengecekan = StokFisik::whereBetween('tanggal', [$startDate, $endDate])->count();
        $totalSelisihMelebihi = StokFisik::whereBetween('tanggal', [$startDate, $endDate])
                                        ->where('status', 'melebihi_toleransi')
                                        ->count();
        $rataPersentase = StokFisik::whereBetween('tanggal', [$startDate, $endDate])
                                ->avg('persentase_selisih') ?? 0;

        // Grafik tren selisih
        $chartData = StokFisik::select(
                            DB::raw('DATE(tanggal) as tanggal'),
                            DB::raw('AVG(persentase_selisih) as rata_persen'),
                            DB::raw('COUNT(*) as jumlah_cek')
                        )
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->groupBy(DB::raw('DATE(tanggal)'))
                        ->orderBy('tanggal')
                        ->get();

        // Bahan dengan selisih tertinggi
        $bahanTertinggi = StokFisik::select('bahan_id', DB::raw('AVG(persentase_selisih) as rata_persen'))
                            ->with('bahanBaku')
                            ->whereBetween('tanggal', [$startDate, $endDate])
                            ->groupBy('bahan_id')
                            ->orderByDesc('rata_persen')
                            ->limit(5)
                            ->get();

        return view('analisis.selisih', compact(
            'startDate', 'endDate', 'status',
            'selisihData', 'totalPengecekan', 'totalSelisihMelebihi', 'rataPersentase',
            'chartData', 'bahanTertinggi'
        ));
    }

    /**
     * Halaman untuk melakukan opname stok (input stok fisik).
     */
    public function opname()
    {
        $bahan = BahanBaku::where('status', 'aktif')->orderBy('nama')->get();
        return view('analisis.opname', compact('bahan'));
    }

    /**
     * Simpan hasil opname stok.
     */
    public function storeOpname(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'bahan_id' => 'required|exists:bahan_baku,id',
            'stok_fisik' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_id);

        // Hitung selisih
        $stokSistem = $bahan->stok;
        $selisih = $request->stok_fisik - $stokSistem;
        $persentase = $stokSistem > 0 ? abs(($selisih / $stokSistem) * 100) : ($selisih != 0 ? 100 : 0);
        $status = $persentase > 5 ? 'melebihi_toleransi' : 'normal';

        StokFisik::create([
            'tanggal' => $request->tanggal,
            'bahan_id' => $request->bahan_id,
            'stok_sistem' => $stokSistem,
            'stok_fisik' => $request->stok_fisik,
            'selisih' => $selisih,
            'persentase_selisih' => $persentase,
            'status' => $status,
            'keterangan' => $request->keterangan,
            'user_id' => auth()->id(),
        ]);

        // Update stok sistem dengan stok fisik (penyesuaian)
        $bahan->stok = $request->stok_fisik;
        $bahan->save();

        return redirect()->route('analisis.selisih')->with('success', 'Stok fisik berhasil dicatat.');
    }

    /**
     * Ekspor laporan selisih ke PDF (bisa ditambahkan nanti).
     */
    public function exportPDF(Request $request)
    {
        // Implementasi PDF
    }
}