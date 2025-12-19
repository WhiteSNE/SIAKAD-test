<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\Siswa;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class GuruDashboardController extends Controller
{
    /**
     * Helper untuk mendapatkan data Guru yang sedang login.
     */
    private function getGuru()
    {
        $guru = Auth::user()->guru;
        if (!$guru) {
            abort(403, 'Profil Guru tidak ditemukan di database.');
        }
        return $guru;
    }

    /**
     * Menampilkan daftar siswa bimbingan.
     */
    public function indexSiswa()
    {
        $guru = $this->getGuru();
        $siswas = $guru->siswas()->with(['kelas', 'jurusan', 'dudi'])->get();
        return view('guru.siswa.index', compact('siswas'));
    }

    /**
     * Menampilkan daftar jurnal siswa bimbingan untuk divalidasi.
     */
    public function indexJurnal()
    {
        $guru = $this->getGuru();
        $siswaIds = $guru->siswas->pluck('id');
        $jurnals = Jurnal::whereIn('siswa_id', $siswaIds)->with('siswa')->orderBy('tanggal', 'desc')->get();
        return view('guru.jurnal.index', compact('jurnals'));
    }

    /**
     * Validasi atau revisi jurnal harian siswa.
     */
    public function validasiJurnal(Request $request, Jurnal $jurnal)
    {
        $jurnal->update([
            'status' => $request->status,
            'catatan_pembimbing' => $request->catatan_pembimbing
        ]);

        return back()->with('success', 'Status jurnal berhasil diperbarui');
    }

    /**
     * Menampilkan daftar status penilaian seluruh siswa bimbingan.
     */
    public function indexPenilaian()
    {
        $guru = $this->getGuru();
        // Menggunakan relasi penilaian sesuai model Siswa
        $siswas = Siswa::where('guru_id', $guru->id)->with('penilaian')->latest()->get();
        return view('guru.penilaian.index', compact('siswas'));
    }

    /**
     * Form input penilaian baru untuk siswa tertentu.
     */
    public function createPenilaian(Siswa $siswa)
    {
        $guru = $this->getGuru();
        if ($siswa->guru_id !== $guru->id) {
            abort(403, 'Anda bukan pembimbing siswa ini.');
        }

        // Daftar Capaian Pembelajaran standar
        $capaianPembelajaran = [
            ['capaian' => 'Menerapkan soft skills yang dibutuhkan dalam dunia kerja', 'ketercapaian' => null, 'deskripsi' => ''],
            ['capaian' => 'Menerapkan kompetensi teknis pada pekerjaan sesuai POS yang berlaku di dunia kerja', 'ketercapaian' => null, 'deskripsi' => ''],
            ['capaian' => 'Menerapkan kompetensi teknis baru atau kompetensi yang belum tuntas dipelajari sesuai konsentrasi keahlian', 'ketercapaian' => null, 'deskripsi' => ''],
            ['capaian' => 'Melakukan analisis usaha secara mandiri', 'ketercapaian' => null, 'deskripsi' => ''],
        ];

        return view('guru.penilaian.create', compact('siswa', 'capaianPembelajaran'));
    }

    /**
     * Simpan data penilaian ke database.
     */
    public function storePenilaian(Request $request, Siswa $siswa)
    {
        $guru = $this->getGuru();
        
        $validated = $request->validate([
            'lama_pkl' => 'required|string|max:255',
            'capaian' => 'required|array|min:4',
            'ketercapaian' => 'required|array|min:4',
            'deskripsi' => 'required|array|min:4',
            'catatan' => 'nullable|string',
        ]);

        // Menyusun array nilai dari input form
        $nilaiData = [];
        foreach ($validated['capaian'] as $index => $namaCapaian) {
            $nilaiData[] = [
                'capaian' => $namaCapaian,
                'ketercapaian' => $validated['ketercapaian'][$index] ?? 'Tidak',
                'deskripsi' => $validated['deskripsi'][$index] ?? '',
            ];
        }

        Penilaian::updateOrCreate(
            ['siswa_id' => $siswa->id, 'guru_id' => $guru->id],
            [
                'nilai' => $nilaiData,
                'lama_pkl' => $validated['lama_pkl'],
                'catatan' => $validated['catatan'],
                'status' => 'sudah_dinilai',
                'rata_rata' => 0, // Disesuaikan dengan logika CP
            ]
        );

        return redirect()->route('guru.penilaian.index')->with('success', 'Penilaian berhasil disimpan');
    }

    /**
     * Form edit penilaian yang sudah ada.
     */
    public function editPenilaian(Penilaian $penilaian)
    {
        $guru = $this->getGuru();
        if ($penilaian->guru_id !== $guru->id) {
            abort(403);
        }
        $penilaian->load('siswa');
        return view('guru.penilaian.edit', compact('penilaian'));
    }

    /**
     * Update data penilaian.
     */
    public function updatePenilaian(Request $request, Penilaian $penilaian)
    {
        $guru = $this->getGuru();
        if ($penilaian->guru_id !== $guru->id) {
            abort(403);
        }

        $validated = $request->validate([
            'lama_pkl' => 'required|string|max:255',
            'capaian' => 'required|array',
            'ketercapaian' => 'required|array',
            'deskripsi' => 'required|array',
            'catatan' => 'nullable|string',
        ]);

        $nilaiData = [];
        foreach ($validated['capaian'] as $index => $namaCapaian) {
            $nilaiData[] = [
                'capaian' => $namaCapaian,
                'ketercapaian' => $validated['ketercapaian'][$index],
                'deskripsi' => $validated['deskripsi'][$index],
            ];
        }

        $penilaian->update([
            'lama_pkl' => $validated['lama_pkl'],
            'nilai' => $nilaiData,
            'catatan' => $validated['catatan'],
        ]);

        return redirect()->route('guru.penilaian.index')->with('success', 'Penilaian berhasil diperbarui');
    }

    /**
     * Export penilaian siswa ke format PDF.
     */
    public function exportPdf(Penilaian $penilaian)
    {
        $guru = $this->getGuru();
        if ($penilaian->guru_id !== $guru->id) {
            abort(403);
        }

        // Load relasi untuk kelengkapan data dokumen
        $penilaian->load(['siswa.dudi', 'guru']);
        
        $pdf = Pdf::loadView('guru.penilaian.export-pdf', [
            'penilaian' => $penilaian,
            'siswa' => $penilaian->siswa,
            'guru' => $guru,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Lembar_Penilaian_' . str_replace(' ', '_', $penilaian->siswa->nama_lengkap) . '.pdf');
    }
}