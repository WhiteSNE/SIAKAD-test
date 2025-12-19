<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Jurnal Harian Saya') }}
            </h2>
            <a href="{{ route('siswa.jurnal.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm">
                + Tambah Jurnal
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan Guru</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($jurnals as $jurnal)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ Str::limit($jurnal->deskripsi_kegiatan, 100) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-bold rounded-full 
                                    {{ $jurnal->status == 'disetujui' ? 'bg-green-100 text-green-800' : 
                                       ($jurnal->status == 'revisi' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ strtoupper($jurnal->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 italic">
                                {{ $jurnal->catatan_pembimbing ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                                Belum ada jurnal yang diinput. Klik tombol "Tambah Jurnal" untuk memulai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>