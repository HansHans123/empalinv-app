<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmpalGentongSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks untuk menghindari constraint error
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Truncate tables
        DB::table('users')->truncate();
        DB::table('bahan_baku')->truncate();
        DB::table('menu')->truncate();
        DB::table('resep')->truncate();
        DB::table('penjualan')->truncate();
        DB::table('detail_penjualan')->truncate();
        DB::table('pembelian_bahan_baku')->truncate();
        DB::table('stok_fisik')->truncate();
        DB::table('log_aktivitas')->truncate();
        
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Insert users
        $users = [
            [
                'name' => 'Admin Empal Gentong',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kasir 1',
                'username' => 'kasir1',
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staf Dapur',
                'username' => 'dapur1',
                'password' => Hash::make('password'),
                'role' => 'staf_dapur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('users')->insert($users);
        
        // Get user IDs
        $adminId = DB::table('users')->where('username', 'admin')->value('id');
        $kasirId = DB::table('users')->where('username', 'kasir1')->value('id');
        $stafId = DB::table('users')->where('username', 'dapur1')->value('id');
        
        // Insert bahan baku
        $bahanBaku = [
            [
                'kode_bahan' => 'BB001',
                'nama' => 'Daging Sapi',
                'stok' => 50.50,
                'satuan' => 'kg',
                'stok_minimum' => 10.00,
                'harga_beli' => 120000.00,
                'kategori' => 'daging',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'BB002',
                'nama' => 'Santan Kelapa',
                'stok' => 25.75,
                'satuan' => 'liter',
                'stok_minimum' => 5.00,
                'harga_beli' => 15000.00,
                'kategori' => 'santan',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'BB003',
                'nama' => 'Kunyit',
                'stok' => 3.25,
                'satuan' => 'kg',
                'stok_minimum' => 0.50,
                'harga_beli' => 35000.00,
                'kategori' => 'rempah',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'BB004',
                'nama' => 'Serai',
                'stok' => 40.00,
                'satuan' => 'batang',
                'stok_minimum' => 10.00,
                'harga_beli' => 2000.00,
                'kategori' => 'bumbu',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'BB005',
                'nama' => 'Daun Salam',
                'stok' => 0.80,
                'satuan' => 'kg',
                'stok_minimum' => 0.10,
                'harga_beli' => 50000.00,
                'kategori' => 'bumbu',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'BB006',
                'nama' => 'Lengkuas',
                'stok' => 5.00,
                'satuan' => 'kg',
                'stok_minimum' => 1.00,
                'harga_beli' => 25000.00,
                'kategori' => 'bumbu',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'BB007',
                'nama' => 'Jahe',
                'stok' => 4.50,
                'satuan' => 'kg',
                'stok_minimum' => 1.00,
                'harga_beli' => 30000.00,
                'kategori' => 'rempah',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'BB008',
                'nama' => 'Bawang Merah',
                'stok' => 15.00,
                'satuan' => 'kg',
                'stok_minimum' => 3.00,
                'harga_beli' => 40000.00,
                'kategori' => 'bumbu',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'BB009',
                'nama' => 'Bawang Putih',
                'stok' => 10.00,
                'satuan' => 'kg',
                'stok_minimum' => 2.00,
                'harga_beli' => 45000.00,
                'kategori' => 'bumbu',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_bahan' => 'BB010',
                'nama' => 'Garam',
                'stok' => 8.00,
                'satuan' => 'kg',
                'stok_minimum' => 2.00,
                'harga_beli' => 10000.00,
                'kategori' => 'bumbu',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('bahan_baku')->insert($bahanBaku);
        
        // Get bahan IDs
        $dagingId = DB::table('bahan_baku')->where('kode_bahan', 'BB001')->value('id');
        $santanId = DB::table('bahan_baku')->where('kode_bahan', 'BB002')->value('id');
        
        // Insert menu
        $menu = [
            [
                'kode_menu' => 'M001',
                'nama' => 'Empal Gentong Porsi Reguler',
                'harga' => 25000.00,
                'deskripsi' => 'Empal gentong dengan porsi reguler, cocok untuk 1 orang',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_menu' => 'M002',
                'nama' => 'Empal Gentong Porsi Besar',
                'harga' => 35000.00,
                'deskripsi' => 'Empal gentong dengan porsi besar, tambah daging dan kuah',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_menu' => 'M003',
                'nama' => 'Empal Gentong Spesial',
                'harga' => 45000.00,
                'deskripsi' => 'Empal gentong dengan daging pilihan dan rempah lengkap',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_menu' => 'M004',
                'nama' => 'Nasi Putih',
                'harga' => 5000.00,
                'deskripsi' => 'Nasi putih hangat',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_menu' => 'M005',
                'nama' => 'Teh Manis',
                'harga' => 3000.00,
                'deskripsi' => 'Teh manis dingin/hangat',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_menu' => 'M006',
                'nama' => 'Jeruk Peras',
                'harga' => 7000.00,
                'deskripsi' => 'Jeruk peras asli',
                'status' => 'tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('menu')->insert($menu);
        
        // Get menu IDs
        $menuRegulerId = DB::table('menu')->where('kode_menu', 'M001')->value('id');
        $menuBesarId = DB::table('menu')->where('kode_menu', 'M002')->value('id');
        
        // Insert resep
        $resep = [
            // Resep Empal Gentong Porsi Reguler (M001)
            [
                'menu_id' => $menuRegulerId,
                'bahan_id' => $dagingId,
                'jumlah' => 0.25,
                'satuan' => 'kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $menuRegulerId,
                'bahan_id' => $santanId,
                'jumlah' => 0.15,
                'satuan' => 'liter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Resep Empal Gentong Porsi Besar (M002)
            [
                'menu_id' => $menuBesarId,
                'bahan_id' => $dagingId,
                'jumlah' => 0.35,
                'satuan' => 'kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id' => $menuBesarId,
                'bahan_id' => $santanId,
                'jumlah' => 0.20,
                'satuan' => 'liter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('resep')->insert($resep);
        
        // Insert pembelian bahan baku (tanpa trigger event)
        $pembelian = [
            [
                'kode_pembelian' => 'PB-001',
                'tanggal' => now()->subDay(),
                'bahan_id' => $dagingId,
                'jumlah' => 50.00,
                'harga_satuan' => 120000.00,
                'total' => 6000000.00,
                'supplier' => 'Supplier Daging Sapi',
                'user_id' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pembelian' => 'PB-002',
                'tanggal' => now()->subDay(),
                'bahan_id' => $santanId,
                'jumlah' => 30.00,
                'harga_satuan' => 15000.00,
                'total' => 450000.00,
                'supplier' => 'Supplier Santan',
                'user_id' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('pembelian_bahan_baku')->insert($pembelian);
        
        // Insert stok fisik
        $stokFisik = [
            [
                'tanggal' => now()->subDay(),
                'bahan_id' => $dagingId,
                'stok_sistem' => 52.50,
                'stok_fisik' => 50.50,
                'selisih' => -2.00,
                'persentase_selisih' => 3.81,
                'status' => 'melebihi_toleransi',
                'keterangan' => 'Selisih lebih dari 5%',
                'user_id' => $stafId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => now()->subDay(),
                'bahan_id' => $santanId,
                'stok_sistem' => 28.75,
                'stok_fisik' => 25.75,
                'selisih' => -3.00,
                'persentase_selisih' => 10.43,
                'status' => 'melebihi_toleransi',
                'keterangan' => 'Penguapan saat memasak',
                'user_id' => $stafId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('stok_fisik')->insert($stokFisik);
        
        // Insert log aktivitas
        $logs = [
            [
                'user_id' => $adminId,
                'aktivitas' => 'Login',
                'deskripsi' => 'Admin login ke sistem',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now(),
            ],
            [
                'user_id' => $adminId,
                'aktivitas' => 'Tambah Bahan',
                'deskripsi' => 'Menambahkan bahan baku baru: Daging Sapi',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now(),
            ],
            [
                'user_id' => $kasirId,
                'aktivitas' => 'Transaksi',
                'deskripsi' => 'Melakukan transaksi penjualan pertama',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now(),
            ],
            [
                'user_id' => $stafId,
                'aktivitas' => 'Cek Stok',
                'deskripsi' => 'Melakukan pengecekan stok fisik',
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now(),
            ],
        ];
        
        DB::table('log_aktivitas')->insert($logs);
        
        $this->command->info('Seeder berhasil dijalankan!');
        $this->command->info('Login dengan:');
        $this->command->info('  Username: admin / kasir1 / dapur1');
        $this->command->info('  Password: password');
    }
}