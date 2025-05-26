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
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained()->onDelete('cascade');
            $table->foreignId('cluster_id')->constrained()->onDelete('cascade');
            $table->string('no_antrian');
            $table->date('tanggal');
            $table->enum('status', ['Belum Dipanggil', 'Sedang Dilayani', 'Selesai', 'Dibatalkan'])->default('Belum Dipanggil');
            $table->timestamps();
            $table->unique(['no_antrian', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};