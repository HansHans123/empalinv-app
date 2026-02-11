<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->dateTime('tanggal');
            $table->decimal('total', 12, 2);
            $table->integer('jumlah_porsi')->default(0);
            $table->enum('pembayaran', ['tunai', 'debit', 'qris', 'lainnya'])->default('tunai');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('tanggal');
            $table->index('kode_transaksi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};