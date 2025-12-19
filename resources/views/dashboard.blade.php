<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Guru Pembimbing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $guru = Auth::user()->guru; // Mengambil data profil guru
                $siswaIds = $guru->siswas->pluck('id'); // ID semua siswa bimbingan
            @endphp

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
                                        <a href="#" class="text-blue-600 hover:text-blue-900">Lihat Jurnal</a>
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
        </div>
    </div>
</x-app-layout>