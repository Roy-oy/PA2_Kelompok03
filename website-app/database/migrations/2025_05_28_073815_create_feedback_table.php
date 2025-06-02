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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('id_feedback')->nullable();
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->foreignId('id_medical_record')->nullable()->constrained('medical_records')->onDelete('set null');
            $table->foreignId('pasien_id')->nullable()->constrained('pasiens')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
