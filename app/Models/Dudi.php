<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dudi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perusahaan', 
        'alamat', 
        'email_perusahaan', 
        'no_telepon_perusahaan', 
        'nama_pic', 
        'deskripsi'
    ];

    // Dudi menampung banyak siswa PKL
    public function siswas() { 
        return $this->hasMany(Siswa::class);
    }
}