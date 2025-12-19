<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Siswa Bimbingan') }}
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
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Kelas/Jurusan</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan (DUDI)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($siswas as $siswa)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $siswa->kelas->nama_kelas }} / {{ $siswa->jurusan->nama_jurusan }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $siswa->dudi->nama_perusahaan ?? 'Belum Ditentukan' }}
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