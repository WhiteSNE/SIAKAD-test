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
        Schema::create('penilaians', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
    $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
    
    $table->string('lama_pkl')->nullable();
    $table->json('nilai'); // Menyimpan detail indikator nilai
    $table->decimal('rata_rata', 5, 2)->default(0);
    $table->text('catatan')->nullable();
    $table->enum('status', ['belum_dinilai', 'sudah_dinilai'])->default('belum_dinilai');
    $table->timestamps();

    // Satu siswa hanya dinilai satu kali oleh gurunya
    $table->unique(['siswa_id', 'guru_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
