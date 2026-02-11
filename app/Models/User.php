<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Method untuk cek role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKasir()
    {
        return $this->role === 'kasir';
    }

    public function isStafDapur()
    {
        return $this->role === 'staf_dapur';
    }

    // Relasi dengan penjualan
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'user_id');
    }

    // Relasi dengan pembelian
    public function pembelian()
    {
        return $this->hasMany(PembelianBahanBaku::class, 'user_id');
    }

    // Relasi dengan stok fisik
    public function stokFisik()
    {
        return $this->hasMany(StokFisik::class, 'user_id');
    }

    // Relasi dengan log aktivitas
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }

    // Relasi dengan pemakaian
    public function pemakaian()
    {
        return $this->hasMany(PemakaianBahanBaku::class, 'user_id');
    }
}