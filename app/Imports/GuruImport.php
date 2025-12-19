<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows; // Tambahkan ini

class GuruImport implements ToModel, WithStartRow, WithValidation, SkipsEmptyRows
{
    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        // Validasi manual tambahan jika kolom utama kosong (pengaman kedua)
        if (!isset($row[1]) || empty(trim($row[1]))) {
            return null;
        }

        $nip_raw = $row[1];

        // Konversi scientific notation (e.g. 1.97E+17) ke string angka utuh
        if (is_numeric($nip_raw) && str_contains(strtoupper((string)$nip_raw), 'E')) {
            $nip_raw = number_format((float)$nip_raw, 0, '', '');
        }

        $nip = preg_replace('/\s+/', '', trim((string)$nip_raw));
        $nama = trim($row[2]);

        $user = User::updateOrCreate(
            ['username' => $nip],
            [
                'name'     => $nama,
                'email'    => $nip . '@guru.com',
                'password' => Hash::make($nip),
                'role'     => 'guru',
            ]
        );

        Guru::updateOrCreate(
            ['NIP' => $nip],
            [
                'user_id' => $user->id,
                'NAMA'    => $nama,
            ]
        );
        
        return null;
    }

    public function rules(): array
    {
        return [
            '1' => 'required', // NIP
            '2' => 'required|string', // NAMA
        ];
    }

    /**
     * Mengganti angka indeks (1, 2) menjadi nama kolom di pesan error
     */
    public function validationAttributes()
    {
        return [
            '1' => 'NIP',
            '2' => 'Nama Guru',
        ];
    }
}