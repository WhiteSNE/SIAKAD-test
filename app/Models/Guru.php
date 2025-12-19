<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'NIP', 'NAMA', 'foto_profil'];

    public function user() { return $this->belongsTo(User::class); }
    
    // Guru membimbing banyak siswa
    public function siswas() { return $this->hasMany(Siswa::class); }

    // Guru memberikan banyak penilaian
    public function penilaians() { return $this->hasMany(Penilaian::class); }
}