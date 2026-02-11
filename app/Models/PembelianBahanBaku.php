<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'pembelian_bahan_baku';
    
    protected $fillable = [
        'kode_pembelian',
        'tanggal',
        'bahan_id',
        'jumlah',
        'harga_satuan',
        'total',
        'supplier',
        'user_id'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2',
        'tanggal' => 'datetime'
    ];

    // Relasi dengan bahan baku
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }

    // Relasi dengan user (pembeli)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Method untuk generate kode pembelian
    public static function generateKodePembelian()
    {
        $date = now()->format('Ymd');
        $lastPembelian = self::whereDate('created_at', today())->latest()->first();
        
        if ($lastPembelian) {
            $lastNumber = intval(substr($lastPembelian->kode_pembelian, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "PBL-{$date}-{$newNumber}";
    }

    // Method untuk menghitung total
    public function hitungTotal()
    {
        return $this->jumlah * $this->harga_satuan;
    }

    // Event untuk update total dan stok bahan baku
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($pembelian) {
            // Hitung total otomatis
            $pembelian->total = $pembelian->hitungTotal();
        });
        
        // Di app/Models/PembelianBahanBaku.php, ubah event created:
        static::created(function ($pembelian) {
            // Tambah stok bahan baku dengan userId
            $pembelian->bahanBaku->updateStok($pembelian->jumlah, 'masuk', $pembelian->user_id);
            
            // Log aktivitas
            LogAktivitas::create([
                'user_id' => $pembelian->user_id,
                'aktivitas' => 'Pembelian Bahan',
                'deskripsi' => "Membeli {$pembelian->jumlah} {$pembelian->bahanBaku->satuan} {$pembelian->bahanBaku->nama} dari {$pembelian->supplier}",
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'Seeder',
            ]);
        });
        
        static::deleted(function ($pembelian) {
            // Kurangi stok bahan baku jika pembelian dihapus
            $pembelian->bahanBaku->updateStok($pembelian->jumlah, 'keluar');
        });
    }

    // Accessor untuk format total
    public function getTotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}