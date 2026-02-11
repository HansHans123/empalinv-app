<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemakaianBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'pemakaian_bahan_baku';
    
    protected $fillable = [
        'tanggal',
        'bahan_id',
        'jumlah_pakai',
        'stok_awal',
        'stok_akhir',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'jumlah_pakai' => 'decimal:2',
        'stok_awal' => 'decimal:2',
        'stok_akhir' => 'decimal:2',
        'tanggal' => 'date'
    ];

    // Relasi dengan bahan baku
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Event untuk update stok akhir
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($pemakaian) {
            // Hitung stok akhir
            $pemakaian->stok_akhir = $pemakaian->stok_awal - $pemakaian->jumlah_pakai;
        });
        
        static::created(function ($pemakaian) {
            // Log aktivitas
            LogAktivitas::create([
                'user_id' => $pemakaian->user_id,
                'aktivitas' => 'Pemakaian Bahan',
                'deskripsi' => "Mencatat pemakaian {$pemakaian->jumlah_pakai} {$pemakaian->bahanBaku->satuan} {$pemakaian->bahanBaku->nama}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }
}