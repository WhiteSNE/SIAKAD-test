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
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('guru.penilaian.create', $siswa) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                        {{ $siswa->penilaian ? 'Edit Nilai' : 'Input Nilai' }}
                                    </a>
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