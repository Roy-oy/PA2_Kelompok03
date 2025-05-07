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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('spesialisasi'); // Spesialisasi dokter
            $table->string('email')->unique();
            $table->string('no_telepon')->nullable();
            $table->string('no_str')->unique(); // Nomor STR/SIP
            $table->string('jenis_kelamin'); // Jenis kelamin
            $table->date('tanggal_lahir'); // Tanggal lahir
            $table->text('alamat')->nullable(); // Alamat dokter
            $table->string('foto_profil')->nullable(); 
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status kerja
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
