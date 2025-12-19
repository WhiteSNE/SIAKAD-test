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
        Schema::create('dudis', function (Blueprint $table) {
    $table->id();
    $table->string('nama_perusahaan');
    $table->text('alamat');
    $table->string('email_perusahaan')->nullable();
    $table->string('no_telepon_perusahaan', 20)->nullable();
    $table->string('nama_pic');
    $table->text('deskripsi')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dudis');
    }
};
