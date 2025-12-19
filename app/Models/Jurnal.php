<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jurnal extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * * @var array<int, string>
     */
    protected $fillable = [
        'siswa_id',
        'tanggal',
        'deskripsi_kegiatan',
        'foto_dokumentasi',
        'keterangan',
        'status',
        'catatan_pembimbing',
    ];

    /**
     * Konversi tipe data otomatis.
     * * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date', // Memastikan kolom tanggal menjadi objek Carbon
    ];

    /**
     * Relasi ke model Siswa.
     * Setiap jurnal dimiliki oleh satu siswa.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}