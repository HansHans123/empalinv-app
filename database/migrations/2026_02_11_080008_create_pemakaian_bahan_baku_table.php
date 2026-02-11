<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemakaian_bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('bahan_id')->constrained('bahan_baku')->onDelete('cascade');
            $table->decimal('jumlah_pakai', 10, 2);
            $table->decimal('stok_awal', 10, 2);
            $table->decimal('stok_akhir', 10, 2);
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->index(['tanggal', 'bahan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemakaian_bahan_baku');
    }
};