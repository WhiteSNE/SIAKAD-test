<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;
    protected $fillable = ['nama_kelas'];
    public function siswas() { return $this->hasMany(Siswa::class); }
}
