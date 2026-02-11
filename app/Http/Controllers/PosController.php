<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\BahanBaku;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    /**
     * Menampilkan halaman utama POS.
     */
    public function index()
    {
        // Ambil menu yang statusnya 'tersedia' saja
        $menu = Menu::with('resep.bahanBaku')
                    ->where('status', 'tersedia')
                    ->orderBy('nama')
                    ->get();

        return view('pos.index', compact('menu'));
    }

    /**
     * Memproses transaksi penjualan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menu,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'pembayaran' => 'required|in:tunai,debit,qris',
            'catatan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Hitung total dan siapkan data detail
            $total = 0;
            $jumlahPorsi = 0;
            $detailItems = [];

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $subtotal = $menu->harga * $item['jumlah'];
                $total += $subtotal;
                $jumlahPorsi += $item['jumlah'];

                $detailItems[] = [
                    'menu_id' => $menu->id,
                    'jumlah_porsi' => $item['jumlah'],
                    'harga_satuan' => $menu->harga,
                    'subtotal' => $subtotal,
                ];
            }

            // Generate kode transaksi
            $kodeTransaksi = Penjualan::generateKodeTransaksi();

            // Simpan header penjualan
            $penjualan = Penjualan::create([
                'kode_transaksi' => $kodeTransaksi,
                'tanggal' => now(),
                'total' => $total,
                'jumlah_porsi' => $jumlahPorsi,
                'pembayaran' => $request->pembayaran,
                'user_id' => Auth::id(),
                'catatan' => $request->catatan,
            ]);

            // Simpan detail penjualan
            foreach ($detailItems as $detail) {
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'menu_id' => $detail['menu_id'],
                    'jumlah_porsi' => $detail['jumlah_porsi'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'subtotal' => $detail['subtotal'],
                ]);

                // Kurangi stok bahan baku berdasarkan resep
                $menu = Menu::find($detail['menu_id']);
                foreach ($menu->resep as $resep) {
                    $bahan = $resep->bahanBaku;
                    $jumlahDipakai = $resep->jumlah * $detail['jumlah_porsi'];
                    
                    if (!$bahan->isStokCukup($jumlahDipakai)) {
                        throw new \Exception("Stok {$bahan->nama} tidak mencukupi untuk menu {$menu->nama}");
                    }
                    
                    $bahan->updateStok($jumlahDipakai, 'keluar', Auth::id());
                }
            }

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Transaksi Penjualan',
                'deskripsi' => "Melakukan transaksi {$kodeTransaksi} dengan total Rp " . number_format($total, 0, ',', '.'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            // Return JSON untuk response AJAX
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'kode_transaksi' => $kodeTransaksi,
                'total' => $total,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menampilkan halaman struk / invoice.
     */
    public function struk($id)
    {
        $penjualan = Penjualan::with('detailPenjualan.menu', 'kasir')
                              ->findOrFail($id);
        return view('pos.struk', compact('penjualan'));
    }
}