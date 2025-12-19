<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
}