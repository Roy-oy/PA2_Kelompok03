<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            // Migrate data from 'pembayaran' to 'jenis_pembayaran' where jenis_pembayaran is null
            DB::table('pendaftarans')
                ->whereNull('jenis_pembayaran')
                ->update(['jenis_pembayaran' => DB::raw('pembayaran')]);

            // Drop the 'pembayaran' column
            $table->dropColumn('pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            // Recreate 'pembayaran' column
            $table->enum('pembayaran', ['bpjs', 'umum'])->nullable()->after('jenis_pasien');
            
            // Migrate data back from 'jenis_pembayaran' to 'pembayaran'
            DB::table('pendaftarans')
                ->whereNull('pembayaran')
                ->update(['pembayaran' => DB::raw('jenis_pembayaran')]);
        });
    }
};