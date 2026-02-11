<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu')->onDelete('cascade');
            $table->foreignId('bahan_id')->constrained('bahan_baku')->onDelete('cascade');
            $table->decimal('jumlah', 10, 2);
            $table->string('satuan');
            $table->timestamps();
            $table->unique(['menu_id', 'bahan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep');
    }
};