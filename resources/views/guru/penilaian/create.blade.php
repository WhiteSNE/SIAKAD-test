<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Nilai: {{ $siswa->nama_lengkap }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Tambahkan ini untuk melihat error jika validasi gagal --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('guru.penilaian.store', $siswa) }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <x-input-label for="lama_pkl" :value="__('Lama Pelaksanaan PKL')" />
                        <x-text-input id="lama_pkl" name="lama_pkl" type="text" class="mt-1 block w-full" placeholder="Contoh: 3 Bulan" required />
                    </div>

                    <table class="w-full border-collapse border border-gray-200 mb-6">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="border p-2">Capaian Pembelajaran</th>
                                <th class="border p-2 w-32">Ketercapaian</th>
                                <th class="border p-2">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($capaianPembelajaran as $cp)
                            <tr>
                                <td class="border p-2">
                                    <input type="hidden" name="capaian[]" value="{{ $cp['capaian'] }}">
                                    {{ $cp['capaian'] }}
                                </td>
                                <td class="border p-2 text-center">
                                    <select name="ketercapaian[]" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 text-sm">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                    </select>
                                </td>
                                <td class="border p-2">
                                    <textarea name="deskripsi[]" rows="2" class="w-full border-gray-300 rounded-md text-sm" placeholder="Catatan capaian..."></textarea>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mb-6">
                        <x-input-label for="catatan" :value="__('Catatan Umum')" />
                        <textarea name="catatan" id="catatan" rows="3" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Simpan Penilaian</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>