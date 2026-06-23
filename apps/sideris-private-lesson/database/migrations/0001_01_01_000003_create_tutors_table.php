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
        Schema::create('tutors', function (Blueprint $table) {
            $table->id('id_tutor');
            $table->foreignId('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->enum('mata_pelajaran', ['Matematika', 'Bahasa Inggris', 'Bahasa Indonesia', 'Fisika', 'Bahasa Latin']);
            $table->integer('harga_per_jam');
            $table->text('jam_tersedia');
            $table->string('foto_profil')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
