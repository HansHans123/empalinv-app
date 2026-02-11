<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjualan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penjualan';
    
    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'total',
        'jumlah_porsi',
        'pembayaran',
        'user_id',
        'catatan'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'tanggal' => 'datetime'
    ];

    // Relasi dengan user (kasir)
    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan detail penjualan
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    // Relasi dengan menu melalui detail penjualan
    public function menu()
    {
        return $this->belongsToMany(Menu::class, 'detail_penjualan', 'penjualan_id', 'menu_id')
                    ->withPivot('jumlah_porsi', 'harga_satuan', 'subtotal')
                    ->withTimestamps();
    }

    // Method untuk generate kode transaksi
    public static function generateKodeTransaksi()
    {
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', today())->latest()->first();
        
        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->kode_transaksi, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "TRX-{$date}-{$newNumber}";
    }

    // Method untuk menghitung total
    public function hitungTotal()
    {
        return $this->detailPenjualan->sum('subtotal');
    }

    // Method untuk menghitung jumlah porsi
    public function hitungJumlahPorsi()
    {
        return $this->detailPenjualan->sum('jumlah_porsi');
    }

    // Event untuk update total dan jumlah porsi
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($penjualan) {
            if ($penjualan->isDirty('total') || $penjualan->isDirty('jumlah_porsi')) {
                // Jika sudah ada detail penjualan, hitung ulang
                if ($penjualan->detailPenjualan()->exists()) {
                    $penjualan->total = $penjualan->hitungTotal();
                    $penjualan->jumlah_porsi = $penjualan->hitungJumlahPorsi();
                }
            }
        });
        
        // Di app/Models/Penjualan.php, ubah event created:
        static::created(function ($penjualan) {
            // Log aktivitas
            LogAktivitas::create([
                'user_id' => $penjualan->user_id,
                'aktivitas' => 'Transaksi Baru',
                'deskripsi' => "Membuat transaksi {$penjualan->kode_transaksi} senilai Rp " . number_format($penjualan->total, 0, ',', '.'),
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'Seeder',
            ]);
        });
    }

    // Accessor untuk format total
    public function getTotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    // Scope untuk penjualan hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }

    // Scope untuk penjualan bulan ini
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', now()->month)
                     ->whereYear('tanggal', now()->year);
    }
}