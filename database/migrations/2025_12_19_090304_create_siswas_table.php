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
        Schema::create('siswas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->string('nisn', 20)->unique();
    $table->string('nama_lengkap');
    $table->enum('jenis_kelamin', ['L', 'P']);
    $table->foreignId('jurusan_id')->constrained('jurusans')->restrictOnDelete();
    $table->foreignId('kelas_id')->constrained('kelas')->restrictOnDelete();
    $table->foreignId('guru_id')->nullable()->constrained('gurus')->nullOnDelete();
    $table->foreignId('dudi_id')->nullable()->constrained('dudis')->nullOnDelete();
    $table->string('foto_profil')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
