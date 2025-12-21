<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Siswa Bimbingan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6">
                    <form action="{{ route('guru.siswa.index') }}" method="GET" class="flex gap-2">
                        {{-- Pertahankan parameter sort saat mencari --}}
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                        
                        <x-text-input 
                            name="search" 
                            placeholder="Cari Nama, NISN, Kelas, atau Jurusan..." 
                            value="{{ request('search') }}" 
                            class="w-full md:w-1/3"
                        />
                        <x-primary-button>Cari</x-primary-button>
                        @if(request('search'))
                            <a href="{{ route('guru.siswa.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition shadow-sm">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('guru.siswa.index', array_merge(request()->query(), ['sort' => 'nama_lengkap', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-indigo-600 flex items-center gap-1">
                                        Nama Siswa
                                        @if(request('sort') == 'nama_lengkap')
                                            <span>{!! request('direction') == 'asc' ? '↑' : '↓' !!}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('guru.siswa.index', array_merge(request()->query(), ['sort' => 'nisn', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-indigo-600 flex items-center gap-1">
                                        NISN
                                        @if(request('sort') == 'nisn')
                                            <span>{!! request('direction') == 'asc' ? '↑' : '↓' !!}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jurusan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tempat PKL</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($siswas as $siswa)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $siswa->nisn }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $siswa->jurusan->nama_jurusan ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $siswa->dudi->nama_perusahaan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">Data siswa tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>