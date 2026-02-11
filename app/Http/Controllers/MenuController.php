<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Resep;
use App\Models\BahanBaku;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Menu::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_menu', 'LIKE', "%{$search}%")
                  ->orWhere('nama', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }

        // Filter status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $menu = $query->orderBy('created_at', 'desc')->paginate(10);

        $totalMenu = Menu::count();
        $totalTersedia = Menu::where('status', 'tersedia')->count();
        $totalHabis = Menu::where('status', 'habis')->count();

        return view('menu.index', compact('menu', 'totalMenu', 'totalTersedia', 'totalHabis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_menu' => 'required|unique:menu|max:20',
            'nama' => 'required|max:100',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,habis',
        ], [
            'kode_menu.required' => 'Kode menu harus diisi',
            'kode_menu.unique' => 'Kode menu sudah digunakan',
            'nama.required' => 'Nama menu harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
        ]);

        DB::beginTransaction();
        try {
            $menu = Menu::create($validated);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Menambah Menu',
                'deskripsi' => "Menambah menu baru: {$menu->nama} ({$menu->kode_menu})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('menu.index')
                ->with('success', 'Menu berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambah menu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        $menu->load('resep.bahanBaku');
        return view('menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $menu->load('resep.bahanBaku');
        $bahanBaku = BahanBaku::where('status', 'aktif')->orderBy('nama')->get();
        return view('menu.edit', compact('menu', 'bahanBaku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'kode_menu' => 'required|max:20|unique:menu,kode_menu,' . $menu->id,
            'nama' => 'required|max:100',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,habis',
        ]);

        DB::beginTransaction();
        try {
            $menu->update($validated);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Mengedit Menu',
                'deskripsi' => "Mengedit menu: {$menu->nama} ({$menu->kode_menu})",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('menu.index')
                ->with('success', 'Menu berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui menu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        DB::beginTransaction();
        try {
            $nama = $menu->nama;
            $kode = $menu->kode_menu;
            $menu->delete();

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Menghapus Menu',
                'deskripsi' => "Menghapus menu: {$nama} ({$kode})",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('menu.index')
                ->with('success', 'Menu berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('menu.index')
                ->with('error', 'Gagal menghapus menu: ' . $e->getMessage());
        }
    }

    // ==================== MANAJEMEN RESEP ====================

    /**
     * Store resep (tambah bahan ke menu)
     */
    public function storeResep(Request $request, Menu $menu)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahan_baku,id',
            'jumlah' => 'required|numeric|min:0.01',
            'satuan' => 'required|string|max:50',
        ], [
            'bahan_id.required' => 'Pilih bahan baku',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.min' => 'Jumlah minimal 0.01',
            'satuan.required' => 'Satuan harus diisi',
        ]);

        DB::beginTransaction();
        try {
            // Cek apakah bahan sudah ada di resep menu ini
            $exists = Resep::where('menu_id', $menu->id)
                        ->where('bahan_id', $request->bahan_id)
                        ->exists();
            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Bahan baku sudah ada dalam resep. Silakan edit jika ingin mengubah jumlah.');
            }

            $resep = Resep::create([
                'menu_id' => $menu->id,
                'bahan_id' => $request->bahan_id,
                'jumlah' => $request->jumlah,
                'satuan' => $request->satuan,
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Menambah Resep',
                'deskripsi' => "Menambah bahan {$resep->bahanBaku->nama} ke menu {$menu->nama}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('menu.edit', $menu->id)
                ->with('success', 'Bahan berhasil ditambahkan ke resep.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menambah resep: ' . $e->getMessage());
        }
    }

    /**
     * Update resep (ubah jumlah/satuan)
     */
    public function updateResep(Request $request, Menu $menu, Resep $resep)
    {
        // Pastikan resep milik menu ini
        if ($resep->menu_id != $menu->id) {
            abort(404);
        }

        $request->validate([
            'jumlah' => 'required|numeric|min:0.01',
            'satuan' => 'required|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            $resep->update([
                'jumlah' => $request->jumlah,
                'satuan' => $request->satuan,
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Mengedit Resep',
                'deskripsi' => "Mengedit bahan {$resep->bahanBaku->nama} pada menu {$menu->nama}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('menu.edit', $menu->id)
                ->with('success', 'Resep berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui resep: ' . $e->getMessage());
        }
    }

    /**
     * Remove resep (hapus bahan dari menu)
     */
    public function destroyResep(Menu $menu, Resep $resep)
    {
        // Pastikan resep milik menu ini
        if ($resep->menu_id != $menu->id) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $bahanNama = $resep->bahanBaku->nama;
            $resep->delete();

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Menghapus Resep',
                'deskripsi' => "Menghapus bahan {$bahanNama} dari menu {$menu->nama}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('menu.edit', $menu->id)
                ->with('success', 'Bahan berhasil dihapus dari resep.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus resep: ' . $e->getMessage());
        }
    }
}