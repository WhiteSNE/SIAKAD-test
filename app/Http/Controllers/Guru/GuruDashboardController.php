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

// app/Http/Controllers/Guru/GuruDashboardController.php

public function indexSiswa(Request $request)
{
    $guru = $this->getGuru();
    $search = $request->input('search');

    // Inisialisasi query dari relasi siswa bimbingan
    $query = $guru->siswas()->with(['kelas', 'jurusan', 'dudi']);

    // Logika Pencarian: Nama, NISN, Nama Kelas, atau Nama Jurusan
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('nisn', 'like', "%{$search}%")
              ->orWhereHas('kelas', function($q) use ($search) {
                  $q->where('nama_kelas', 'like', "%{$search}%");
              })
              ->orWhereHas('jurusan', function($q) use ($search) {
                  $q->where('nama_jurusan', 'like', "%{$search}%");
              });
        });
    }

    // Logika Sorting
    $allowedSortColumns = ['nama_lengkap', 'nisn'];
    $sort = in_array($request->sort, $allowedSortColumns) ? $request->sort : 'nama_lengkap';
    $direction = $request->direction === 'desc' ? 'desc' : 'asc';

    $siswas = $query->orderBy($sort, $direction)->get();

    return view('guru.siswa.index', compact('siswas'));
}

    /**
     * Menampilkan daftar jurnal siswa bimbingan untuk divalidasi.
     */
    public function indexJurnal(Request $request)
{
    $guru = $this->getGuru(); //
    $siswas = $guru->siswas; // Ambil daftar siswa bimbingan untuk filter
    
    $query = Jurnal::whereIn('siswa_id', $siswas->pluck('id'))
        ->with('siswa')
        ->orderBy('tanggal', 'desc');

    // Fitur Filter Siswa
    if ($request->has('siswa_id') && $request->siswa_id != '') {
        $query->where('siswa_id', $request->siswa_id);
    }

    // Paginasi agar performa tetap ringan
    $jurnals = $query->paginate(15)->withQueryString();

    return view('guru.jurnal.index', compact('jurnals', 'siswas'));
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

    public function bulkValidasiJurnal(Request $request)
{
    $request->validate([
        'jurnal_ids' => 'required|array',
        'jurnal_ids.*' => 'exists:jurnals,id',
        'status' => 'required|in:disetujui,revisi,pending',
        'bulk_catatan' => 'nullable|string',
    ]);

    // Update data secara massal untuk efisiensi
    Jurnal::whereIn('id', $request->jurnal_ids)->update([
        'status' => $request->status,
        'catatan_pembimbing' => $request->bulk_catatan,
    ]);

    return back()->with('success', count($request->jurnal_ids) . ' jurnal berhasil diperbarui secara massal.');
}

    /**
     * Menampilkan daftar status penilaian seluruh siswa bimbingan.
     */
    public function indexPenilaian(Request $request)
{
    $guru = $this->getGuru();
    $search = $request->input('search');

    // Inisialisasi Query
    $query = Siswa::where('guru_id', $guru->id)->with(['penilaian', 'kelas', 'jurusan']);

    // Fitur Pencarian (Nama, NISN, Kelas, Jurusan)
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('nisn', 'like', "%{$search}%")
              ->orWhereHas('kelas', function($q) use ($search) {
                  $q->where('nama_kelas', 'like', "%{$search}%");
              })
              ->orWhereHas('jurusan', function($q) use ($search) {
                  $q->where('nama_jurusan', 'like', "%{$search}%");
              });
        });
    }

    // Fitur Sorting (Nama & NISN)
    $allowedSort = ['nama_lengkap', 'nisn'];
    $sort = in_array($request->sort, $allowedSort) ? $request->sort : 'nama_lengkap';
    $direction = $request->direction === 'desc' ? 'desc' : 'asc';

    // Pagination: 15 data per halaman
    $siswas = $query->orderBy($sort, $direction)->paginate(15)->withQueryString();

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
        // REVISI: Samakan nama helper dengan yang didefinisikan di atas
        $guru = $this->getGuru();

        // Pastikan guru pembimbingnya sesuai
        if ($penilaian->guru_id !== $guru->id) {
            abort(403, 'Anda bukan pembimbing untuk penilaian ini.');
        }

        $penilaian->load(['siswa.dudi', 'guru']);

        $pdf = Pdf::loadView('guru.penilaian.export-pdf', [
            'penilaian' => $penilaian,
            'siswa' => $penilaian->siswa,
            'guru' => $guru,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Lembar_Penilaian_' . str_replace(' ', '_', $penilaian->siswa->nama_lengkap) . '.pdf');
    }
}
