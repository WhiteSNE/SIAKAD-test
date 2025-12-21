<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penilaian PKL Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6">
                
                <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                    <form action="{{ route('guru.penilaian.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
                        {{-- Pertahankan Sort saat searching --}}
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                        
                        <div class="relative w-full md:w-80">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <x-text-input name="search" placeholder="Cari Siswa, NISN, Kelas..." value="{{ request('search') }}" class="pl-10 w-full text-sm" />
                        </div>
                        <x-primary-button class="py-2">Cari</x-primary-button>
                        @if(request('search'))
                            <a href="{{ route('guru.penilaian.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 transition">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto border rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('guru.penilaian.index', array_merge(request()->query(), ['sort' => 'nama_lengkap', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-indigo-600 flex items-center gap-1">
                                        Nama Siswa
                                        @if(request('sort', 'nama_lengkap') == 'nama_lengkap')
                                            <span>{!! request('direction') == 'asc' ? '↑' : '↓' !!}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NISN</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas / Jurusan</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($siswas as $siswa)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $siswa->nama_lengkap }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $siswa->nisn }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $siswa->kelas->nama_kelas ?? '-' }} / {{ $siswa->jurusan->nama_jurusan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-tighter
                                        {{ $siswa->penilaian ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $siswa->penilaian ? 'Sudah Dinilai' : 'Belum Dinilai' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                    {{-- Tombol Input/Edit --}}
                                    <a href="{{ route('guru.penilaian.create', $siswa) }}" class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold hover:bg-indigo-100 transition">
                                        {{ $siswa->penilaian ? 'Edit Nilai' : 'Input Nilai' }}
                                    </a>

                                    {{-- Tombol Export PDF --}}
                                    @if($siswa->penilaian)
                                        <a href="{{ route('guru.penilaian.export-pdf', $siswa->penilaian) }}" class="inline-flex items-center px-3 py-1 bg-red-50 text-red-700 rounded-full text-xs font-bold hover:bg-red-100 transition" title="Cetak PDF">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            PDF
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">Data siswa tidak ditemukan sesuai kriteria.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $siswas->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>