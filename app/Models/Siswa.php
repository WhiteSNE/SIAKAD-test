<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nisn', 'nama_lengkap', 'jenis_kelamin', 
        'jurusan_id', 'kelas_id', 'guru_id', 'dudi_id', 'foto_profil'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function jurusan() { return $this->belongsTo(Jurusan::class); }
    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function guru() { return $this->belongsTo(Guru::class); }
    public function dudi() { return $this->belongsTo(Dudi::class); }
    
    public function jurnals() { return $this->hasMany(Jurnal::class); }
    public function penilaian() { return $this->hasOne(Penilaian::class); }
}