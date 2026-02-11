<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\DB;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BahanBaku::query();
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_bahan', 'LIKE', "%{$search}%")
                  ->orWhere('nama', 'LIKE', "%{$search}%")
                  ->orWhere('kategori', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != 'all') {
            $query->where('kategori', $request->kategori);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter stok rendah
        if ($request->has('stok_rendah')) {
            $query->whereColumn('stok', '<=', 'stok_minimum');
        }
        
        $bahanBaku = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics
        $totalBahanBaku = BahanBaku::count();
        $totalStokRendah = BahanBaku::whereColumn('stok', '<=', 'stok_minimum')->count();
        $totalAktif = BahanBaku::where('status', 'aktif')->count();
        
        $kategoriList = BahanBaku::select('kategori')->distinct()->pluck('kategori');
        
        return view('bahan-baku.index', compact(
            'bahanBaku', 
            'totalBahanBaku',
            'totalStokRendah',
            'totalAktif',
            'kategoriList'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bahan-baku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'kode_bahan' => 'required|unique:bahan_baku|max:20',
            'nama' => 'required|max:100',
            'stok' => 'nullable|numeric|min:0',
            'satuan' => 'required|max:50',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'kategori' => 'required|in:daging,santan,rempah,bumbu,lainnya',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'kode_bahan.required' => 'Kode bahan harus diisi',
            'kode_bahan.unique' => 'Kode bahan sudah digunakan',
            'nama.required' => 'Nama bahan harus diisi',
            'stok_minimum.required' => 'Stok minimum harus diisi',
            'harga_beli.required' => 'Harga beli harus diisi',
        ]);

        // Start transaction
        DB::beginTransaction();
        
        try {
            // Create bahan baku
            $bahanBaku = BahanBaku::create([
                'kode_bahan' => $validated['kode_bahan'],
                'nama' => $validated['nama'],
                'stok' => $validated['stok'] ?? 0,
                'satuan' => $validated['satuan'],
                'stok_minimum' => $validated['stok_minimum'],
                'harga_beli' => $validated['harga_beli'],
                'kategori' => $validated['kategori'],
                'status' => $validated['status'],
            ]);

            // Log activity
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Menambah Bahan Baku',
                'deskripsi' => "Menambah bahan baku baru: {$bahanBaku->nama} ({$bahanBaku->kode_bahan})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            
            return redirect()->route('bahan-baku.index')
                ->with('success', 'Bahan baku berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambah bahan baku: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BahanBaku $bahanBaku)
    {
        // Get related data
        $resep = $bahanBaku->resep()->with('menu')->get();
        $pembelian = $bahanBaku->pembelian()->latest()->take(5)->get();
        $stokFisik = $bahanBaku->stokFisik()->latest()->take(5)->get();
        
        return view('bahan-baku.show', compact(
            'bahanBaku',
            'resep',
            'pembelian',
            'stokFisik'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BahanBaku $bahanBaku)
    {
        return view('bahan-baku.edit', compact('bahanBaku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BahanBaku $bahanBaku)
    {
        // Validate request
        $validated = $request->validate([
            'kode_bahan' => 'required|max:20|unique:bahan_baku,kode_bahan,' . $bahanBaku->id,
            'nama' => 'required|max:100',
            'satuan' => 'required|max:50',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'kategori' => 'required|in:daging,santan,rempah,bumbu,lainnya',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Start transaction
        DB::beginTransaction();
        
        try {
            // Update bahan baku
            $bahanBaku->update([
                'kode_bahan' => $validated['kode_bahan'],
                'nama' => $validated['nama'],
                'satuan' => $validated['satuan'],
                'stok_minimum' => $validated['stok_minimum'],
                'harga_beli' => $validated['harga_beli'],
                'kategori' => $validated['kategori'],
                'status' => $validated['status'],
            ]);

            // Log activity
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Mengedit Bahan Baku',
                'deskripsi' => "Mengedit bahan baku: {$bahanBaku->nama} ({$bahanBaku->kode_bahan})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            
            return redirect()->route('bahan-baku.index')
                ->with('success', 'Bahan baku berhasil diperbarui!');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui bahan baku: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BahanBaku $bahanBaku)
    {
        // Start transaction
        DB::beginTransaction();
        
        try {
            $nama = $bahanBaku->nama;
            $kode = $bahanBaku->kode_bahan;
            
            // Delete bahan baku
            $bahanBaku->delete();
            
            // Log activity
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Menghapus Bahan Baku',
                'deskripsi' => "Menghapus bahan baku: {$nama} ({$kode})",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('bahan-baku.index')
                ->with('success', 'Bahan baku berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('bahan-baku.index')
                ->with('error', 'Gagal menghapus bahan baku: ' . $e->getMessage());
        }
    }

    /**
     * Update stock for bahan baku.
     */
    public function updateStok(Request $request, BahanBaku $bahanBaku)
    {
        $request->validate([
            'tipe' => 'required|in:masuk,keluar',
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        
        try {
            $tipe = $request->tipe;
            $jumlah = $request->jumlah;
            $keterangan = $request->keterangan;
            
            if ($tipe === 'masuk') {
                $bahanBaku->updateStok($jumlah, 'masuk', auth()->id());
                $deskripsi = "Menambah stok {$bahanBaku->nama} sebanyak {$jumlah} {$bahanBaku->satuan}";
            } else {
                // Check if stock is sufficient
                if (!$bahanBaku->isStokCukup($jumlah)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak cukup!'
                    ], 400);
                }
                
                $bahanBaku->updateStok($jumlah, 'keluar', auth()->id());
                $deskripsi = "Mengurangi stok {$bahanBaku->nama} sebanyak {$jumlah} {$bahanBaku->satuan}";
            }
            
            // Add keterangan to log if exists
            if ($keterangan) {
                $deskripsi .= " - {$keterangan}";
            }
            
            // Log activity
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Update Stok Manual',
                'deskripsi' => $deskripsi,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil diperbarui!',
                'stok_baru' => $bahanBaku->stok,
                'status_stok' => $bahanBaku->status_stok,
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui stok: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bahan baku data for API.
     */
    public function getData()
    {
        $bahanBaku = BahanBaku::select('id', 'kode_bahan', 'nama', 'stok', 'satuan', 'status_stok')
            ->aktif()
            ->orderBy('nama')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $bahanBaku
        ]);
    }

    /**
     * Export bahan baku to PDF.
     */
    public function exportPDF()
    {
        $bahanBaku = BahanBaku::orderBy('kategori')->orderBy('nama')->get();
        $date = now()->format('d-m-Y');
        
        // You can implement PDF generation here
        // For now, return view
        return view('bahan-baku.export-pdf', compact('bahanBaku', 'date'));
    }
}