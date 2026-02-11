<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';
    
    protected $fillable = [
        'user_id',
        'aktivitas',
        'deskripsi',
        'ip_address',
        'user_agent'
    ];

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Method untuk mencatat aktivitas
    public static function catat($aktivitas, $deskripsi = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    // Scope untuk aktivitas hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Scope untuk aktivitas user tertentu
    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessor untuk waktu relatif
    public function getWaktuRelatifAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Accessor untuk format tanggal
    public function getTanggalFormatAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }
}