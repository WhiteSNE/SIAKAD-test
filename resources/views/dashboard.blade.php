<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->isGuru() ? __('Dashboard Guru Pembimbing') : (Auth::user()->isSiswa() ? __('Dashboard Siswa') : __('Dashboard Admin')) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- TAMPILAN KHUSUS GURU --}}
            @if(Auth::user()->isGuru())
                @php
                    $guru = Auth::user()->guru;
                    $siswaIds = $guru ? $guru->siswas->pluck('id') : collect([]);
                @endphp

                @if($guru)
                    {{-- Statistik Guru --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
                        {{-- Total Siswa --}}
                        <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-l-4 border-blue-500 flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase font-bold">Total Siswa</div>
                                <div class="text-2xl font-black text-gray-800">{{ $guru->siswas->count() }}</div>
                            </div>
                        </div>

                        {{-- Jurnal Pending --}}
                        <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-l-4 border-yellow-500 flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase font-bold">Pending</div>
                                <div class="text-2xl font-black text-gray-800">{{ \App\Models\Jurnal::whereIn('siswa_id', $siswaIds)->where('status', 'pending')->count() }}</div>
                            </div>
                        </div>

                        {{-- Jurnal Revisi --}}
                        <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-l-4 border-red-500 flex items-center">
                            <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase font-bold">Revisi</div>
                                <div class="text-2xl font-black text-gray-800">{{ \App\Models\Jurnal::whereIn('siswa_id', $siswaIds)->where('status', 'revisi')->count() }}</div>
                            </div>
                        </div>

                        {{-- Jurnal Valid --}}
                        <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-l-4 border-green-500 flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase font-bold">Valid</div>
                                <div class="text-2xl font-black text-gray-800">{{ \App\Models\Jurnal::whereIn('siswa_id', $siswaIds)->where('status', 'disetujui')->count() }}</div>
                            </div>
                        </div>

                        {{-- Sudah Dinilai --}}
                        <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-l-4 border-purple-500 flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase font-bold">Dinilai</div>
                                <div class="text-2xl font-black text-gray-800">{{ $guru->siswas()->whereHas('penilaian')->count() }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Siswa Bimbingan --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-lg text-gray-800">Daftar Siswa Bimbingan</h3>
                                <a href="{{ route('guru.siswa.index') }}" class="text-sm text-indigo-600 hover:underline">Lihat Semua</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="text-gray-400 text-xs uppercase tracking-wider">
                                            <th class="py-3 px-4 font-bold border-b">Nama Siswa</th>
                                            <th class="py-3 px-4 font-bold border-b text-center">DUDI / Tempat PKL</th>
                                            <th class="py-3 px-4 font-bold border-b text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm divide-y divide-gray-100">
                                        @forelse($guru->siswas as $siswa)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="py-4 px-4 font-medium text-gray-900">{{ $siswa->nama_lengkap }}</td>
                                                <td class="py-4 px-4 text-center text-gray-600">{{ $siswa->dudi->nama_perusahaan ?? '-' }}</td>
                                                <td class="py-4 px-4 text-center space-x-2">
                                                    <a href="{{ route('guru.jurnal.index', ['siswa_id' => $siswa->id]) }}" class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold hover:bg-blue-100 transition">
                                                        Jurnal
                                                    </a>
                                                    <a href="{{ route('guru.penilaian.create', $siswa) }}" class="inline-flex items-center px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-bold hover:bg-purple-100 transition">
                                                        Input Nilai
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="py-8 text-center text-gray-500 italic">Belum ada siswa bimbingan yang terdaftar.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white p-8 rounded-xl shadow-sm text-center border-2 border-dashed border-red-200">
                        <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-red-600 font-bold">Data profil Guru tidak ditemukan. Harap hubungi administrator sistem.</p>
                    </div>
                @endif

            {{-- TAMPILAN KHUSUS SISWA --}}
            @elseif(Auth::user()->isSiswa())
                @php $siswa = Auth::user()->siswa; @endphp

                @if($siswa)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Kolom Kiri: Info & Greeting --}}
                        <div class="lg:col-span-2 space-y-8">
                            <div class="bg-indigo-600 p-8 rounded-2xl shadow-lg text-white relative overflow-hidden">
                                <div class="relative z-10">
                                    <h3 class="text-2xl font-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h3>
                                    <p class="text-indigo-100 mb-6">Sudahkah Anda mengisi jurnal kegiatan hari ini? Tetap semangat melaksanakan PKL!</p>
                                    <a href="{{ route('siswa.jurnal.create') }}" class="inline-block bg-white text-indigo-600 px-6 py-3 rounded-xl hover:bg-indigo-50 transition font-bold shadow-md">
                                        📝 Isi Jurnal Sekarang
                                    </a>
                                </div>
                                {{-- Ornamen Background --}}
                                <div class="absolute -right-10 -bottom-10 opacity-20">
                                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M11 5h2v2h-2V5zm-2 0h2v2H9V5zm4 0h2v2h-2V5zm2 0h2v2h-2V5zM7 5h2v2H7V5zm10 4h2v2h-2V9zm-2 0h2v2h-2V9zm-2 0h2v2h-2V9zm-2 0h2v2h-2V9zm-2 0h2v2H9V9zm-2 0h2v2H7V9zm10 4h2v2h-2v-2zm-2 0h2v2h-2v-2zm-2 0h2v2h-2v-2zm-2 0h2v2h-2v-2zm-2 0h2v2H9v-2zm-2 0h2v2H7v-2zm10 4h2v2h-2v-2zm-2 0h2v2h-2v-2zm-2 0h2v2h-2v-2zm-2 0h2v2h-2v-2zm-2 0h2v2H9v-2zm-2 0h2v2H7v-2z"></path></svg>
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    Rincian Penempatan
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                    <div class="p-4 rounded-xl bg-gray-50">
                                        <p class="text-xs uppercase font-bold text-gray-400 mb-1">Perusahaan / DUDI</p>
                                        <p class="font-bold text-gray-800">{{ $siswa->dudi->nama_perusahaan ?? '-' }}</p>
                                    </div>
                                    <div class="p-4 rounded-xl bg-gray-50">
                                        <p class="text-xs uppercase font-bold text-gray-400 mb-1">Guru Pembimbing</p>
                                        <p class="font-bold text-gray-800">{{ $siswa->guru->NAMA ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Statistik --}}
                        <div class="space-y-6">
                            <h3 class="font-bold text-gray-800 px-1">Ringkasan Jurnal</h3>
                            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-yellow-400 flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase">Menunggu</p>
                                    <p class="text-3xl font-black text-gray-800">{{ $siswa->jurnals()->where('status', 'pending')->count() }}</p>
                                </div>
                                <div class="bg-yellow-50 p-3 rounded-xl text-yellow-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-red-400 flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase">Revisi</p>
                                    <p class="text-3xl font-black text-gray-800">{{ $siswa->jurnals()->where('status', 'revisi')->count() }}</p>
                                </div>
                                <div class="bg-red-50 p-3 rounded-xl text-red-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-green-400 flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase">Disetujui</p>
                                    <p class="text-3xl font-black text-gray-800">{{ $siswa->jurnals()->where('status', 'disetujui')->count() }}</p>
                                </div>
                                <div class="bg-green-50 p-3 rounded-xl text-green-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white p-12 rounded-2xl shadow-sm text-center border-2 border-dashed border-gray-200">
                        <p class="text-gray-500 font-medium">Data profil Siswa tidak ditemukan. Mohon hubungi Bagian Hubin.</p>
                    </div>
                @endif

            {{-- TAMPILAN KHUSUS ADMIN --}}
            @else
                <div class="bg-white p-12 rounded-2xl shadow-sm text-center border border-gray-100">
                    <div class="bg-indigo-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Panel Administrator</h3>
                    <p class="text-gray-600 mb-6">Kelola data master, monitoring pkl, dan pengaturan sistem secara penuh.</p>
                    <a href="/admin" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition font-bold shadow-lg">
                        Buka Panel Admin
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>