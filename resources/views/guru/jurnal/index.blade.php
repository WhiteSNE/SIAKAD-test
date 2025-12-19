<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Validasi Jurnal Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @forelse($jurnals as $jurnal)
                    <div class="mb-6 p-4 border rounded-lg shadow-sm bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-lg font-bold text-indigo-700">{{ $jurnal->siswa->nama_lengkap }}</h4>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $jurnal->status == 'disetujui' ? 'bg-green-100 text-green-800' : 
                                   ($jurnal->status == 'revisi' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ strtoupper($jurnal->status) }}
                            </span>
                        </div>

                        <div class="mt-4 p-3 bg-white border rounded">
                            <p class="text-gray-700 whitespace-pre-line">{{ $jurnal->deskripsi_kegiatan }}</p>
                            @if($jurnal->foto_dokumentasi)
                                <div class="mt-3">
                                    <a href="{{ asset('storage/' . $jurnal->foto_dokumentasi) }}" target="_blank" class="text-blue-500 text-sm hover:underline">Lihat Lampiran Gambar</a>
                                </div>
                            @endif
                        </div>
                        
                        <form action="{{ route('guru.jurnal.validasi', $jurnal) }}" method="POST" class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Status Validasi</label>
                                <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="pending" {{ $jurnal->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="disetujui" {{ $jurnal->status == 'disetujui' ? 'selected' : '' }}>Setujui (Valid)</option>
                                    <option value="revisi" {{ $jurnal->status == 'revisi' ? 'selected' : '' }}>Minta Revisi</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700">Catatan Pembimbing (Opsional)</label>
                                <div class="flex gap-2">
                                    <input type="text" name="catatan_pembimbing" value="{{ $jurnal->catatan_pembimbing }}" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           placeholder="Contoh: Deskripsi kurang detail...">
                                    <button type="submit" class="mt-1 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500 italic">Belum ada jurnal yang dikirimkan oleh siswa bimbingan Anda.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>