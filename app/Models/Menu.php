<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menu';
    
    protected $fillable = [
        'kode_menu',
        'nama',
        'harga',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'harga' => 'decimal:2'
    ];

    // Relasi dengan resep
    public function resep()
    {
        return $this->hasMany(Resep::class, 'menu_id');
    }

    // Relasi dengan detail penjualan
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'menu_id');
    }

    // Method untuk mendapatkan bahan-bahan dalam resep
    public function bahanBaku()
    {
        return $this->belongsToMany(BahanBaku::class, 'resep', 'menu_id', 'bahan_id')
                    ->withPivot('jumlah', 'satuan')
                    ->withTimestamps();
    }

    // Method untuk cek apakah stok bahan cukup
    public function cekStokBahanCukup($jumlahPorsi)
    {
        foreach ($this->resep as $resep) {
            $bahan = $resep->bahanBaku;
            $jumlahDibutuhkan = $resep->jumlah * $jumlahPorsi;
            
            if (!$bahan->isStokCukup($jumlahDibutuhkan)) {
                return false;
            }
        }
        
        return true;
    }

    // Method untuk mengurangi stok bahan berdasarkan jumlah porsi
    public function kurangiStokBahan($jumlahPorsi)
    {
        foreach ($this->resep as $resep) {
            $bahan = $resep->bahanBaku;
            $jumlahDikurangi = $resep->jumlah * $jumlahPorsi;
            
            $bahan->updateStok($jumlahDikurangi, 'keluar');
        }
    }

    // Scope untuk menu tersedia
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }
}