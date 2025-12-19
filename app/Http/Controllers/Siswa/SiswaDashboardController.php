<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaDashboardController extends Controller
{
    public function indexJurnal()
    {
        $jurnals = Jurnal::where('siswa_id', Auth::user()->siswa->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('siswa.jurnal.index', compact('jurnals'));
    }

    public function createJurnal()
    {
        return view('siswa.jurnal.create');
    }

    public function storeJurnal(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'deskripsi_kegiatan' => 'required|string',
            'foto_dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_dokumentasi')) {
            $fotoPath = $request->file('foto_dokumentasi')->store('jurnal-foto', 'public');
        }

        Jurnal::create([
            'siswa_id' => Auth::user()->siswa->id,
            'tanggal' => $request->tanggal,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'foto_dokumentasi' => $fotoPath,
            'status' => 'pending',
        ]);

        return redirect()->route('siswa.jurnal.index')->with('success', 'Jurnal harian berhasil dikirim.');
    }
    public function editJurnal(Jurnal $jurnal)
    {
        // Pastikan jurnal milik siswa yang login dan belum disetujui
        if ($jurnal->siswa_id !== Auth::user()->siswa->id || $jurnal->status === 'disetujui') {
            abort(403, 'Jurnal yang sudah disetujui tidak dapat diubah.');
        }

        return view('siswa.jurnal.edit', compact('jurnal'));
    }

    public function updateJurnal(Request $request, Jurnal $jurnal)
    {
        if ($jurnal->siswa_id !== Auth::user()->siswa->id || $jurnal->status === 'disetujui') {
            abort(403);
        }

        $request->validate([
            'tanggal' => 'required|date',
            'deskripsi_kegiatan' => 'required|string',
            'foto_dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'tanggal' => $request->tanggal,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'status' => 'pending', // Set kembali ke pending jika diedit
        ];

        if ($request->hasFile('foto_dokumentasi')) {
            // Hapus foto lama jika ada
            if ($jurnal->foto_dokumentasi) {
                Storage::disk('public')->delete($jurnal->foto_dokumentasi);
            }
            $data['foto_dokumentasi'] = $request->file('foto_dokumentasi')->store('jurnal-foto', 'public');
        }

        $jurnal->update($data);

        return redirect()->route('siswa.jurnal.index')->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function destroyJurnal(Jurnal $jurnal)
    {
        if ($jurnal->siswa_id !== Auth::user()->siswa->id || $jurnal->status === 'disetujui') {
            abort(403, 'Jurnal yang sudah disetujui tidak dapat dihapus.');
        }

        if ($jurnal->foto_dokumentasi) {
            Storage::disk('public')->delete($jurnal->foto_dokumentasi);
        }

        $jurnal->delete();

        return redirect()->route('siswa.jurnal.index')->with('success', 'Jurnal berhasil dihapus.');
    }
    public function exportPdf()
{
    // Load data siswa beserta semua relasi pendukungnya
    $siswa = Auth::user()->siswa->load(['jurusan', 'kelas', 'dudi', 'guru']);
    $jurnals = Jurnal::where('siswa_id', $siswa->id)->orderBy('tanggal', 'asc')->get();

    $pdf = Pdf::loadView('siswa.jurnal.export-pdf', [
        'siswa' => $siswa,
        'jurnals' => $jurnals,
    ])->setPaper('a4', 'portrait');

    return $pdf->download('Jurnal_PKL_' . str_replace(' ', '_', $siswa->nama_lengkap) . '.pdf');
}
}
