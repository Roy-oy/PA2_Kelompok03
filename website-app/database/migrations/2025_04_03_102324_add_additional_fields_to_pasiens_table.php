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
        Schema::table('pasiens', function (Blueprint $table) {
            // Add the missing fields
            $table->string('rhesus')->default('Positif')->after('golongan_darah');
            $table->string('status_perkawinan')->nullable()->after('rhesus');
            $table->string('agama')->nullable()->after('status_perkawinan');
            $table->string('pendidikan')->nullable()->after('agama');
            $table->integer('tinggi_badan')->nullable()->after('pendidikan');
            $table->integer('berat_badan')->nullable()->after('tinggi_badan');
            $table->string('imt')->nullable()->after('berat_badan');
            $table->string('tekanan_darah')->nullable()->after('imt');
            $table->string('status_bpjs')->nullable()->after('no_bpjs');
            $table->string('kelas_rawat')->nullable()->after('status_bpjs');
            $table->date('masa_berlaku_bpjs')->nullable()->after('kelas_rawat');
            
            // Update the golongan_darah enum to allow more values
            $table->string('golongan_darah')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasiens', function (Blueprint $table) {
            // Remove the added columns
            $table->dropColumn([
                'rhesus',
                'status_perkawinan',
                'agama',
                'pendidikan',
                'tinggi_badan',
                'berat_badan',
                'imt',
                'tekanan_darah',
                'status_bpjs',
                'kelas_rawat',
                'masa_berlaku_bpjs'
            ]);
            
            // Revert golongan_darah back to enum
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'Tidak Diketahui'])->default('Tidak Diketahui')->change();
        });
    }
};
