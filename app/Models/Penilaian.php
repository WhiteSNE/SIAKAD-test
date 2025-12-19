<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id', 'guru_id', 'lama_pkl', 'nilai', 'rata_rata', 'catatan', 'status'
    ];

    protected $casts = [
        'nilai' => 'array', // Menyimpan format JSON indikator nilai
    ];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function guru() { return $this->belongsTo(Guru::class); }
}