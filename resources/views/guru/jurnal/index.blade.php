<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Validasi Jurnal Siswa') }}
        </h2>
    </x-slot>

    {{-- State Alpine.js untuk Modal, Bulk Action, dan Dropdown --}}
    <div class="py-12" x-data="{ 
        showModal: false, 
        modalImage: '', 
        selectedJurnals: [],
        selectAll: false,
        toggleAll() {
            this.selectAll = !this.selectAll;
            this.selectedJurnals = this.selectAll 
                ? Array.from(document.querySelectorAll('.jurnal-checkbox')).map(el => el.value)
                : [];
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                    {{-- Pencarian & Filter --}}
                    <form action="{{ route('guru.jurnal.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                        <x-text-input name="search" placeholder="Cari nama atau kegiatan..." value="{{ request('search') }}" class="text-sm py-1.5 w-64" />
                        <select name="siswa_id" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 text-sm py-1.5">
                            <option value="">Semua Siswa</option>
                            @foreach($siswas as $s)
                                <option value="{{ $s->id }}" {{ request('siswa_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_lengkap }}</option>
                            @endforeach
                        </select>
                        <x-primary-button class="py-2 px-4">Filter</x-primary-button>
                    </form>

                    {{-- Bulk Action Form (Muncul saat ada item dipilih) --}}
                    <form action="{{ route('guru.jurnal.bulk-validasi') }}" method="POST" x-show="selectedJurnals.length > 0" x-transition class="flex items-center gap-2 p-1.5 bg-indigo-50 rounded-xl border border-indigo-100 shadow-sm animate-pulse">
                        @csrf
                        <template x-for="id in selectedJurnals">
                            <input type="hidden" name="jurnal_ids[]" :value="id">
                        </template>
                        
                        <span class="text-[10px] font-black text-indigo-700 px-2 uppercase" x-text="selectedJurnals.length + ' dipilih'"></span>
                        
                        <select name="status" required class="text-[10px] border-gray-300 rounded-md py-1 px-2 font-bold uppercase">
                            <option value="disetujui">Setujui</option>
                            <option value="revisi">Minta Revisi</option>
                        </select>
                        
                        <input type="text" name="bulk_catatan" placeholder="Catatan massal..." class="text-[10px] border-gray-300 rounded-md py-1 w-32">
                        
                        <button type="submit" class="bg-indigo-600 text-white text-[10px] px-3 py-1.5 rounded-md hover:bg-indigo-700 font-black uppercase tracking-wider transition">
                            Proses
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto border rounded-xl shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left w-10">
                                    <input type="checkbox" @click="toggleAll()" :checked="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">Siswa</th>
                                <th class="px-4 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">Kegiatan</th>
                                <th class="px-4 py-3 text-center text-xs font-black text-gray-500 uppercase tracking-widest">Foto</th>
                                <th class="px-4 py-3 text-center text-xs font-black text-gray-500 uppercase tracking-widest">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-black text-gray-500 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($jurnals as $jurnal)
                            <tr class="hover:bg-gray-50 transition duration-75">
                                <td class="px-4 py-4">
                                    <input type="checkbox" value="{{ $jurnal->id }}" x-model="selectedJurnals" class="jurnal-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $jurnal->siswa->nama_lengkap }}</div>
                                </td>
                                <td class="px-4 py-4 text-[11px] text-gray-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-[11px] text-gray-600 max-w-xs truncate" title="{{ $jurnal->deskripsi_kegiatan }}">
                                        {{ $jurnal->deskripsi_kegiatan }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($jurnal->foto_dokumentasi)
                                        <button @click="showModal = true; modalImage = '{{ asset('storage/' . $jurnal->foto_dokumentasi) }}'" class="text-indigo-400 hover:text-indigo-600 transition">
                                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </button>
                                    @else
                                        <span class="text-gray-200">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-2 py-0.5 text-[9px] font-black rounded-full uppercase tracking-tighter
                                        {{ $jurnal->status == 'disetujui' ? 'bg-green-100 text-green-800' : 
                                           ($jurnal->status == 'revisi' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $jurnal->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    {{-- Individual Dropdown Action --}}
                                    <form action="{{ route('guru.jurnal.validasi', $jurnal) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-[10px] py-1 pl-2 pr-8 border-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500 font-bold uppercase cursor-pointer hover:bg-gray-50">
                                            <option value="pending" {{ $jurnal->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="disetujui" {{ $jurnal->status == 'disetujui' ? 'selected' : '' }}>Setujui</option>
                                            <option value="revisi" {{ $jurnal->status == 'revisi' ? 'selected' : '' }}>Revisi</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-400 italic text-xs tracking-widest">Data jurnal tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $jurnals->links() }}
                </div>
            </div>
        </div>

        {{-- Modal Gambar --}}
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-80" x-transition x-cloak style="display: none;">
            <div class="relative max-w-3xl w-full bg-white rounded-2xl shadow-2xl p-2" @click.away="showModal = false">
                <img :src="modalImage" class="w-full h-auto max-h-[80vh] object-contain rounded-xl shadow-inner">
                <button @click="showModal = false" class="mt-4 w-full bg-indigo-600 text-white py-2 rounded-xl font-black uppercase text-xs tracking-widest hover:bg-indigo-700 transition">Tutup Pratinjau</button>
            </div>
        </div>
    </div>
</x-app-layout>