<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->enum('jenis_pasien', ['baru', 'lama']);
            $table->text('keluhan');
            $table->foreignId('cluster_id')->constrained('clusters')->onDelete('restrict');
            $table->date('tanggal_daftar');
            $table->enum('pembayaran', ['bpjs', 'umum']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};