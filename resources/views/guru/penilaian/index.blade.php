<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penilaian PKL Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Rata-rata</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($siswas as $siswa)
                            <tr>
                                <td class="px-6 py-4">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $siswa->penilaian ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $siswa->penilaian ? 'Sudah Dinilai' : 'Belum Dinilai' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $siswa->penilaian->rata_rata ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm flex items-center gap-4">
                                    {{-- Tombol Input/Edit --}}
                                    <a href="{{ route('guru.penilaian.create', $siswa) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                        {{ $siswa->penilaian ? 'Edit Nilai' : 'Input Nilai' }}
                                    </a>

                                    {{-- Tombol Export PDF: Hanya muncul jika data penilaian ada --}}
                                    @if($siswa->penilaian)
                                        <a href="{{ route('guru.penilaian.export-pdf', $siswa->penilaian) }}" 
                                           class="text-red-600 hover:text-red-900 font-bold flex items-center gap-1"
                                           title="Unduh Lembar Penilaian PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            PDF
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>