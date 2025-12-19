<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->isGuru() ? __('Dashboard Guru Pembimbing') : __('Dashboard Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- TAMPILAN KHUSUS GURU --}}
            @if(Auth::user()->isGuru())
                @php
                    $guru = Auth::user()->guru;
                    // Ambil ID siswa bimbingan hanya jika data profil guru tersedia
                    $siswaIds = $guru ? $guru->siswas->pluck('id') : collect([]);
                @endphp

                @if($guru)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                            <div class="text-sm text-gray-500 uppercase font-bold">Total Siswa</div>
                            <div class="text-2xl font-black text-gray-800">{{ $guru->siswas->count() }}</div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                            <div class="text-sm text-gray-500 uppercase font-bold">Jurnal Pending</div>
                            <div class="text-2xl font-black text-gray-800">
                                {{ \App\Models\Jurnal::whereIn('siswa_id', $siswaIds)->where('status', 'pending')->count() }}
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                            <div class="text-sm text-gray-500 uppercase font-bold">Jurnal Revisi</div>
                            <div class="text-2xl font-black text-gray-800">
                                {{ \App\Models\Jurnal::whereIn('siswa_id', $siswaIds)->where('status', 'revisi')->count() }}
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                            <div class="text-sm text-gray-500 uppercase font-bold">Jurnal Valid</div>
                            <div class="text-2xl font-black text-gray-800">
                                {{ \App\Models\Jurnal::whereIn('siswa_id', $siswaIds)->where('status', 'disetujui')->count() }}
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                            <div class="text-sm text-gray-500 uppercase font-bold">Sudah Dinilai</div>
                            <div class="text-2xl font-black text-gray-800">
                                {{ $guru->siswas()->whereHas('penilaian')->count() }}
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Siswa Bimbingan --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="font-bold text-lg mb-4">Daftar Siswa Bimbingan</h3>
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 bg-gray-50 border-b">Nama Siswa</th>
                                        <th class="py-2 px-4 bg-gray-50 border-b">DUDI</th>
                                        <th class="py-2 px-4 bg-gray-50 border-b text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($guru->siswas as $siswa)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $siswa->nama_lengkap }}</td>
                                            <td class="py-2 px-4 border-b">{{ $siswa->dudi->nama_perusahaan ?? 'Belum ditentukan' }}</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                <a href="{{ route('guru.jurnal.index') }}" class="text-blue-600 hover:text-blue-900">Lihat Jurnal</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 text-center text-gray-500">Belum ada siswa bimbingan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <p class="text-red-500 font-bold">Data profil Guru Anda tidak ditemukan. Hubungi Admin.</p>
                    </div>
                @endif

            {{-- TAMPILAN KHUSUS SISWA --}}
            @elseif(Auth::user()->isSiswa())
                @php
                    $siswa = Auth::user()->siswa;
                @endphp

                @if($siswa)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        {{-- Info Lokasi & Pembimbing --}}
                        <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-indigo-500">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Magang</h3>
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-semibold">Lokasi:</span> {{ $siswa->dudi->nama_perusahaan ?? 'Belum ditentukan' }}</p>
                                <p class="text-sm"><span class="font-semibold">Guru Pembimbing:</span> {{ $siswa->guru->NAMA ?? 'Belum ditentukan' }}</p>
                            </div>
                        </div>

                        {{-- Statistik Jurnal Siswa --}}
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-lg shadow-sm text-center border-b-4 border-yellow-500">
                                <div class="text-xs text-gray-500 uppercase font-bold">Pending</div>
                                <div class="text-xl font-black">{{ $siswa->jurnals()->where('status', 'pending')->count() }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm text-center border-b-4 border-red-500">
                                <div class="text-xs text-gray-500 uppercase font-bold">Revisi</div>
                                <div class="text-xl font-black">{{ $siswa->jurnals()->where('status', 'revisi')->count() }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm text-center border-b-4 border-green-500">
                                <div class="text-xs text-gray-500 uppercase font-bold">Valid</div>
                                <div class="text-xl font-black">{{ $siswa->jurnals()->where('status', 'disetujui')->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                        <h3 class="font-bold text-lg">Selamat Datang, {{ Auth::user()->name }}</h3>
                        <p class="text-gray-600">Jangan lupa untuk mengisi jurnal harian Anda secara rutin.</p>
                        <a href="{{ route('siswa.jurnal.create') }}" class="mt-4 inline-block bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition font-bold">
                            Isi Jurnal Hari Ini
                        </a>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <p class="text-red-500 font-bold">Data profil Siswa Anda tidak ditemukan. Hubungi Admin.</p>
                    </div>
                @endif

            {{-- TAMPILAN KHUSUS ADMIN --}}
            @else
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <p class="text-gray-600">Anda login sebagai Admin. Silakan kelola data melalui 
                        <a href="/admin" class="text-indigo-600 font-bold hover:underline">Panel Admin</a>.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>