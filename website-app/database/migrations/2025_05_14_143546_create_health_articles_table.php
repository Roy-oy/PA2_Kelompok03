<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('health_articles', function (Blueprint $table) {
        $table->id('id_health_articles');
        $table->string('title');
        $table->text('content');
        $table->string('image_path')->nullable();
        $table->dateTime('published_at');
        $table->timestamps(); // created_at & updated_at
        $table->unsignedBigInteger('id_admin');

        $table->foreign('id_admin')->references('id')->on('admins')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_articles');
    }
};
