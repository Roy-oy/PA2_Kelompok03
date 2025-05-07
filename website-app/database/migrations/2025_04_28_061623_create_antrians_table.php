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
            $table->string('no_antrian')->unique()->comment('Nomor Antrian');
            $table->foreignId('pasiens_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctors_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_daftar');
            $table->enum('pembayaran', ['bpjs', 'umum'])->default('umum');
            $table->enum('cluster', ['cluster_1', 'cluster_2', 'cluster_3', 'cluster_4', 'cluster_5']);
            $table->text('complaint')->nullable();
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai'])->default('menunggu');
            $table->timestamps();
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
