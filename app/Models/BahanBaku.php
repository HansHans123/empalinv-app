<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BahanBaku extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bahan_baku';
    
    protected $fillable = [
        'kode_bahan',
        'nama',
        'stok',
        'satuan',
        'stok_minimum',
        'harga_beli',
        'kategori',
        'status'
    ];

    protected $casts = [
        'stok' => 'decimal:2',
        'stok_minimum' => 'decimal:2',
        'harga_beli' => 'decimal:2'
    ];

    // Relasi dengan resep
    public function resep()
    {
        return $this->hasMany(Resep::class, 'bahan_id');
    }

    // Relasi dengan pembelian
    public function pembelian()
    {
        return $this->hasMany(PembelianBahanBaku::class, 'bahan_id');
    }

    // Relasi dengan stok fisik
    public function stokFisik()
    {
        return $this->hasMany(StokFisik::class, 'bahan_id');
    }

    // Relasi dengan pemakaian
    public function pemakaian()
    {
        return $this->hasMany(PemakaianBahanBaku::class, 'bahan_id');
    }

    // Scope untuk bahan aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk bahan dengan stok rendah
    public function scopeStokRendah($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimum')
                    ->where('status', 'aktif');
    }

    // Method untuk cek stok cukup
    public function isStokCukup($jumlah)
    {
        return $this->stok >= $jumlah;
    }

    // Method untuk update stok
    // Di app/Models/BahanBaku.php, ubah method updateStok():
    public function updateStok($jumlah, $tipe = 'keluar', $userId = null)
    {
        if ($tipe === 'keluar') {
            $this->stok -= $jumlah;
        } else {
            $this->stok += $jumlah;
        }
        
        $this->save();
        
        // Gunakan userId jika diberikan, jika tidak gunakan auth()->id()
        $logUserId = $userId ?? (auth()->check() ? auth()->id() : null);
        
        if ($logUserId) {
            // Log aktivitas
            LogAktivitas::create([
                'user_id' => $logUserId,
                'aktivitas' => 'Update Stok',
                'deskripsi' => "{$tipe} bahan {$this->nama} sebanyak {$jumlah} {$this->satuan}"
            ]);
        }
        
        return $this;
    }

    // Method untuk cek status stok
    public function getStatusStokAttribute()
    {
        if ($this->stok <= 0) {
            return 'habis';
        } elseif ($this->stok <= $this->stok_minimum) {
            return 'rendah';
        } else {
            return 'aman';
        }
    }

    // Method untuk mendapatkan warna status stok
    public function getStatusStokWarnaAttribute()
    {
        $status = $this->status_stok;
        
        switch ($status) {
            case 'habis': return 'red';
            case 'rendah': return 'yellow';
            case 'aman': return 'green';
            default: return 'gray';
        }
    }
}