<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Penilaian PKL') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg border">
                        <table class="text-sm">
                            <tr>
                                <td class="w-32 font-bold py-1">Nama Siswa</td>
                                <td class="py-1">: {{ $penilaian->siswa->nama_lengkap ?? 'Siswa Dihapus' }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold py-1">Tempat PKL</td>
                                <td class="py-1">: {{ $penilaian->siswa->dudi->nama_perusahaan ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <form action="{{ route('guru.penilaian.update', $penilaian) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <x-input-label for="lama_pkl" :value="__('Lama Pelaksanaan PKL')" />
                            <x-text-input id="lama_pkl" name="lama_pkl" type="text" class="mt-1 block w-full" 
                                :value="old('lama_pkl', $penilaian->lama_pkl)" required />
                        </div>

                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full border">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border px-4 py-2 text-sm">Capaian Pembelajaran</th>
                                        <th class="border px-4 py-2 text-sm w-32">Ketercapaian</th>
                                        <th class="border px-4 py-2 text-sm">Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penilaian->nilai as $index => $item)
                                    <tr>
                                        <td class="border px-4 py-2 text-sm">
                                            <input type="hidden" name="capaian[]" value="{{ $item['capaian'] }}">
                                            {{ $item['capaian'] }}
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <select name="ketercapaian[]" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500">
                                                <option value="Ya" {{ $item['ketercapaian'] == 'Ya' ? 'selected' : '' }}>Ya</option>
                                                <option value="Tidak" {{ $item['ketercapaian'] == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                            </select>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <textarea name="deskripsi[]" rows="2" class="w-full text-sm border-gray-300 rounded-md focus:border-indigo-500">{{ $item['deskripsi'] }}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="catatan" :value="__('Catatan Pembimbing')" />
                            <textarea name="catatan" id="catatan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('catatan', $penilaian->catatan) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('guru.penilaian.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>