<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokFisik extends Model
{
    use HasFactory;

    protected $table = 'stok_fisik';
    
    protected $fillable = [
        'tanggal',
        'bahan_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'persentase_selisih',
        'status',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'stok_sistem' => 'decimal:2',
        'stok_fisik' => 'decimal:2',
        'selisih' => 'decimal:2',
        'persentase_selisih' => 'decimal:2',
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

    // Method untuk menghitung selisih
    public function hitungSelisih()
    {
        return $this->stok_fisik - $this->stok_sistem;
    }

    // Method untuk menghitung persentase selisih
    public function hitungPersentaseSelisih()
    {
        if ($this->stok_sistem == 0) {
            return 0;
        }
        
        return abs(($this->hitungSelisih() / $this->stok_sistem) * 100);
    }

    // Method untuk menentukan status selisih
    public function tentukanStatus()
    {
        $persentase = $this->hitungPersentaseSelisih();
        
        // Toleransi 5%
        if ($persentase > 5) {
            return 'melebihi_toleransi';
        }
        
        return 'normal';
    }

    // Event untuk update selisih, persentase, dan status
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($stokFisik) {
            // Hitung selisih
            $stokFisik->selisih = $stokFisik->hitungSelisih();
            
            // Hitung persentase selisih
            $stokFisik->persentase_selisih = $stokFisik->hitungPersentaseSelisih();
            
            // Tentukan status
            $stokFisik->status = $stokFisik->tentukanStatus();
        });
        
        // Di app/Models/StokFisik.php, ubah event created:
        static::created(function ($stokFisik) {
            // Update stok sistem berdasarkan stok fisik
            $bahan = $stokFisik->bahanBaku;
            $bahan->stok = $stokFisik->stok_fisik;
            $bahan->save();
            
            // Log aktivitas
            LogAktivitas::create([
                'user_id' => $stokFisik->user_id,
                'aktivitas' => 'Pengecekan Stok Fisik',
                'deskripsi' => "Melakukan pengecekan stok fisik {$bahan->nama}. Selisih: {$stokFisik->selisih} {$bahan->satuan} ({$stokFisik->persentase_selisih}%)",
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'Seeder',
            ]);
        });
    }

    // Accessor untuk warna status
    public function getStatusWarnaAttribute()
    {
        return $this->status === 'melebihi_toleransi' ? 'red' : 'green';
    }

    // Accessor untuk icon status
    public function getStatusIconAttribute()
    {
        return $this->status === 'melebihi_toleransi' ? 'exclamation-triangle' : 'check-circle';
    }
}