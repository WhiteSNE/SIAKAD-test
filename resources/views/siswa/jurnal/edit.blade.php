<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Jurnal') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('siswa.jurnal.update', $jurnal) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <x-input-label for="tanggal" :value="__('Tanggal Kegiatan')" />
                        <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" :value="old('tanggal', $jurnal->tanggal->format('Y-m-d'))" required />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="deskripsi_kegiatan" :value="__('Deskripsi Kegiatan')" />
                        <textarea name="deskripsi_kegiatan" rows="5" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('deskripsi_kegiatan', $jurnal->deskripsi_kegiatan) }}</textarea>
                    </div>

                    <div class="mb-8">
                        <x-input-label for="foto_dokumentasi" :value="__('Foto Dokumentasi (Kosongkan jika tidak diubah)')" />
                        @if($jurnal->foto_dokumentasi)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $jurnal->foto_dokumentasi) }}" class="h-20 w-auto rounded shadow">
                            </div>
                        @endif
                        <input type="file" name="foto_dokumentasi" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('siswa.jurnal.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                        <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>