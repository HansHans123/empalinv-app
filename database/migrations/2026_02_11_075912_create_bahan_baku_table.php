<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bahan')->unique();
            $table->string('nama');
            $table->decimal('stok', 10, 2)->default(0);
            $table->string('satuan');
            $table->decimal('stok_minimum', 10, 2)->default(0);
            $table->decimal('harga_beli', 12, 2)->default(0);
            $table->enum('kategori', ['daging', 'santan', 'rempah', 'bumbu', 'lainnya'])->default('lainnya');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};