<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->date('tanggal_kunjungan');
            $table->text('keluhan');
            $table->text('diagnosis');
            $table->text('pengobatan');
            $table->text('hasil_pemeriksaan')->nullable();
            $table->float('tinggi_badan')->nullable();
            $table->float('berat_badan')->nullable();
            $table->string('tekanan_darah', 20)->nullable();
            $table->float('suhu_badan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};