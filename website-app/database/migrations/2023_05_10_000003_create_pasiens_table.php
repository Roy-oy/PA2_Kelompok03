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
            $table->foreignId('app_user_id')->nullable()->constrained('app_users');
            $table->string('no_rm', 12)->unique();
            $table->string('nik', 16)->unique();
            $table->string('no_kk', 16)->nullable();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('alamat');
            $table->string('no_hp', 13)->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('no_bpjs', 13)->unique()->nullable();
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O']);
            $table->string('riwayat_alergi')->nullable();
            $table->string('riwayat_penyakit')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('no_bpjs');
            $table->index('nik');
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