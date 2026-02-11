<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;

    protected $table = 'resep';
    
    protected $fillable = [
        'menu_id',
        'bahan_id',
        'jumlah',
        'satuan'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2'
    ];

    // Relasi dengan menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    // Relasi dengan bahan baku
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }

    // Method untuk mendapatkan jumlah bahan dalam satuan standar
    public function getJumlahDalamSatuanStandarAttribute()
    {
        // Konversi satuan ke satuan standar jika diperlukan
        $satuan = strtolower($this->satuan);
        $jumlah = $this->jumlah;
        
        // Contoh konversi (bisa disesuaikan)
        $konversi = [
            'gr' => 0.001,
            'gram' => 0.001,
            'ml' => 0.001,
            'mililiter' => 0.001,
            'l' => 1,
            'liter' => 1,
            'kg' => 1,
            'kilogram' => 1,
        ];
        
        if (isset($konversi[$satuan])) {
            return $jumlah * $konversi[$satuan];
        }
        
        return $jumlah;
    }
}