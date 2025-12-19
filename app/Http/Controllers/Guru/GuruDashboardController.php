<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\Siswa;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    /**
     * Menampilkan daftar siswa bimbingan.
     */
    public function indexSiswa()
    {
        $siswas = Auth::user()->guru->siswas()->with(['kelas', 'jurusan', 'dudi'])->get();
        return view('guru.siswa.index', compact('siswas'));
    }

    /**
     * Menampilkan daftar jurnal dari siswa bimbingan (Validasi Jurnal).
     */
    public function indexJurnal() // Pastikan nama method ini indexJurnal
    {
        $guru = Auth::user()->guru;
        
        // Ambil ID semua siswa yang dibimbing oleh guru ini
        $siswaIds = $guru->siswas->pluck('id');

        // Ambil jurnal dari siswa-siswa tersebut
        $jurnals = Jurnal::whereIn('siswa_id', $siswaIds)
            ->with('siswa')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.jurnal.index', compact('jurnals'));
    }

    /**
     * Menampilkan daftar status penilaian siswa.
     */
    public function indexPenilaian()
    {
        $siswas = Auth::user()->guru->siswas()->with('penilaian')->get();
        return view('guru.penilaian.index', compact('siswas'));
    }

    /**
     * Form input penilaian.
     */
    public function createPenilaian(Siswa $siswa)
    {
        return view('guru.penilaian.create', compact('siswa'));
    }

    /**
     * Simpan data penilaian.
     */
    public function storePenilaian(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nilai' => 'required|array',
            'catatan' => 'nullable|string',
            'lama_pkl' => 'required|string',
        ]);

        // Hitung rata-rata otomatis dari array nilai
        $rataRata = collect($request->nilai)->avg();

        Penilaian::updateOrCreate(
            ['siswa_id' => $siswa->id, 'guru_id' => Auth::user()->guru->id],
            [
                'nilai' => $request->nilai, // Disimpan sebagai JSON/Array
                'rata_rata' => $rataRata,
                'catatan' => $request->catatan,
                'status' => 'sudah_dinilai',
                'lama_pkl' => $request->lama_pkl
            ]
        );

        return redirect()->route('guru.penilaian.index')->with('success', 'Penilaian berhasil disimpan');
    }

    /**
     * Update status validasi jurnal.
     */
    public function validasiJurnal(Request $request, Jurnal $jurnal)
    {
        $jurnal->update([
            'status' => $request->status,
            'catatan_pembimbing' => $request->catatan_pembimbing
        ]);

        return back()->with('success', 'Status jurnal berhasil diperbarui');
    }
}