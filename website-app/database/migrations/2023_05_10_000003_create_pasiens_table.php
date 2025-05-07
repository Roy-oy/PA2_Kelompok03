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
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('no_rm')->unique()->comment('Nomor Rekam Medis');
            $table->string('nama');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('alamat');
            $table->string('no_telepon')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('no_bpjs')->nullable();
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'Tidak Diketahui'])->default('Tidak Diketahui');
            $table->text('riwayat_alergi')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
}; 