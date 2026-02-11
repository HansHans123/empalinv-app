<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\StokFisik;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokFisikController extends Controller
{
    /**
     * Menampilkan daftar bahan baku dan form opname cepat.
     */
    public function index()
    {
        $bahan = BahanBaku::where('status', 'aktif')
                    ->orderBy('kategori')
                    ->orderBy('nama')
                    ->get();

        return view('stok-fisik.index', compact('bahan'));
    }

    /**
     * Menampilkan form opname untuk bahan tertentu.
     */
    public function create($id = null)
    {
        if ($id) {
            $bahan = BahanBaku::findOrFail($id);
            return view('stok-fisik.create', compact('bahan'));
        }

        $bahanList = BahanBaku::where('status', 'aktif')->orderBy('nama')->get();
        return view('stok-fisik.create', compact('bahanList'));
    }

    /**
     * Menyimpan hasil opname stok.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'bahan_id' => 'required|exists:bahan_baku,id',
            'stok_fisik' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_id);

        DB::beginTransaction();
        try {
            // Hitung selisih
            $stokSistem = $bahan->stok;
            $selisih = $request->stok_fisik - $stokSistem;
            $persentase = $stokSistem > 0 ? abs(($selisih / $stokSistem) * 100) : ($selisih != 0 ? 100 : 0);
            $status = $persentase > 5 ? 'melebihi_toleransi' : 'normal';

            // Simpan record opname
            $stokFisik = StokFisik::create([
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

            // Update stok sistem dengan stok fisik
            $bahan->stok = $request->stok_fisik;
            $bahan->save();

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Opname Stok',
                'deskripsi' => "Stok fisik {$bahan->nama} dicatat: {$request->stok_fisik} {$bahan->satuan} (sebelumnya {$stokSistem}), selisih " . number_format($selisih, 2) . " ({$persentase}%)",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('stok-fisik.history')
                ->with('success', 'Opname stok berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan opname: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan riwayat opname yang dilakukan oleh user yang login.
     */
    public function history()
    {
        $history = StokFisik::with('bahanBaku')
                    ->where('user_id', auth()->id())
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        return view('stok-fisik.history', compact('history'));
    }

    /**
     * API: Mendapatkan data stok terkini untuk bahan tertentu (AJAX).
     */
    public function getStok($id)
    {
        $bahan = BahanBaku::findOrFail($id);
        return response()->json([
            'stok' => $bahan->stok,
            'satuan' => $bahan->satuan,
            'nama' => $bahan->nama,
            'stok_minimum' => $bahan->stok_minimum,
        ]);
    }
}