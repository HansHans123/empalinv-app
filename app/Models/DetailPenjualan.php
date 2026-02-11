<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';
    
    protected $fillable = [
        'penjualan_id',
        'menu_id',
        'jumlah_porsi',
        'harga_satuan',
        'subtotal'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    // Relasi dengan penjualan
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    // Relasi dengan menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    // Method untuk menghitung subtotal
    public function hitungSubtotal()
    {
        return $this->jumlah_porsi * $this->harga_satuan;
    }

    // Event untuk update subtotal
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($detail) {
            // Hitung subtotal otomatis
            $detail->subtotal = $detail->hitungSubtotal();
        });
        
        static::created(function ($detail) {
            // Kurangi stok bahan baku berdasarkan resep
            if ($detail->menu) {
                $detail->menu->kurangiStokBahan($detail->jumlah_porsi);
            }
        });
    }
}