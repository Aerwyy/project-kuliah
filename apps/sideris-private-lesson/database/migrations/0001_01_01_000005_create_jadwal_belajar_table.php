<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_belajar', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->foreignId('id_transaksi')->references('id_transaksi')->on('transaksi')->onDelete('cascade');
            $table->string('judul_pertemuan', 100);
            $table->string('status', 20)->default('Terjadwal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_belajar');
    }
};
