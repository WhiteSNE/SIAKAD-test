<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithStartRow, WithValidation
{
    /**
     * Import mulai dari baris ke-5 (setelah header template).
     */
    public function startRow(): int
    {
        return 5;
    }

    public function model(array $row)
    {
        // $row[1] = NAMA, $row[2] = L, $row[3] = P, $row[4] = NO_INDUK/NISN, $row[5] = KELAS, $row[6] = JURUSAN
        
        if (!isset($row[1]) || !isset($row[4])) {
            return null;
        }

        // 1. Cari atau Buat Kelas & Jurusan berdasarkan nama di Excel
        $kelas = Kelas::firstOrCreate(['nama_kelas' => $row[5]]);
        $jurusan = Jurusan::firstOrCreate(['nama_jurusan' => $row[6]]);

        // 2. Buat User Siswa (Username & Password default menggunakan NISN)
        $user = User::create([
            'name'     => $row[1],
            'username' => $row[4],
            'email'    => $row[4] . '@siswa.com', // Email otomatis menggunakan NISN
            'password' => Hash::make($row[4]),
            'role'     => 'siswa',
        ]);

        // 3. Tentukan Jenis Kelamin (Cek kolom L atau P)
        $jk = (!empty($row[2])) ? 'L' : 'P';

        // 4. Simpan Data Siswa
        return new Siswa([
            'user_id'       => $user->id,
            'nisn'          => $row[4],
            'nama_lengkap'  => $row[1],
            'jenis_kelamin' => $jk,
            'jurusan_id'    => $jurusan->id,
            'kelas_id'      => $kelas->id,
        ]);
    }

    public function rules(): array
    {
        return [
            '4' => 'unique:siswas,nisn', // Validasi agar NISN tidak duplikat
        ];
    }
}