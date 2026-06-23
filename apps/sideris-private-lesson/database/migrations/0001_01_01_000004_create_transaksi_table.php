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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->foreignId('id_user_murid')->references('id_user')->on('users');
            $table->foreignId('id_tutor')->references('id_tutor')->on('tutors');
            $table->integer('total_harga');
            $table->date('tanggal_les');
            $table->enum('jam_pilihan_murid', ['09:00', '16:00', '20:00']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
