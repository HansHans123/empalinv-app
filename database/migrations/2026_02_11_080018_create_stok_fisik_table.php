<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_fisik', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('bahan_id')->constrained('bahan_baku')->onDelete('cascade');
            $table->decimal('stok_sistem', 10, 2);
            $table->decimal('stok_fisik', 10, 2);
            $table->decimal('selisih', 10, 2);
            $table->decimal('persentase_selisih', 5, 2);
            $table->enum('status', ['normal', 'melebihi_toleransi'])->default('normal');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['tanggal', 'bahan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_fisik');
    }
};