<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Jurnal Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('siswa.jurnal.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Kegiatan</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Kegiatan</label>
                        <textarea name="deskripsi_kegiatan" rows="5" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Apa yang Anda kerjakan hari ini? Jelaskan secara detail..." required></textarea>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Foto Dokumentasi (Opsional)</label>
                        <input type="file" name="foto_dokumentasi" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-2 text-xs text-gray-400">Format: JPG, PNG. Maksimal 2MB.</p>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('siswa.jurnal.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md font-bold hover:bg-indigo-700 transition">
                            Kirim Jurnal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>