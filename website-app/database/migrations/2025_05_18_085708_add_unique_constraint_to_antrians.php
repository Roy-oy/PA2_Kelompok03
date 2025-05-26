<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->unique(['no_antrian', 'tanggal'], 'antrians_unique_no_antrian_tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropUnique('antrians_unique_no_antrian_tanggal');
        });
    }
};