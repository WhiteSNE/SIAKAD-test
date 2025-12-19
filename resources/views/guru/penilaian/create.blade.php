<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Nilai PKL Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 bg-white border-b border-gray-200">
                    <div class="mb-8 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                        <h3 class="text-lg font-bold text-indigo-800 mb-2">Data Siswa</h3>
                        <table class="w-full text-sm">
                            <tr>
                                <td class="w-32 font-semibold py-1">Nama Lengkap</td>
                                <td class="py-1">: {{ $siswa->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-1">NISN</td>
                                <td class="py-1">: {{ $siswa->nisn }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold py-1">Tempat PKL</td>
                                <td class="py-1">: {{ $siswa->dudi->nama_perusahaan ?? 'Belum ditentukan' }}</td>
                            </tr>
                        </table>
                    </div>

                    <form action="{{ route('guru.penilaian.store', $siswa) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <x-input-label for="lama_pkl" :value="__('Lama Pelaksanaan PKL (Bulan/Hari)')" />
                            <x-text-input id="lama_pkl" name="lama_pkl" type="text" class="mt-1 block w-full" 
                                placeholder="Contoh: 3 Bulan / 90 Hari" required />
                            <x-input-error :messages="$errors->get('lama_pkl')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <h4 class="font-bold text-gray-700 mb-4 border-b pb-2">Unsur Penilaian</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="nilai_kedisiplinan" :value="__('Kedisiplinan (0-100)')" />
                                    <x-text-input name="nilai[kedisiplinan]" type="number" min="0" max="100" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label for="nilai_kerjasama" :value="__('Kerja Sama (0-100)')" />
                                    <x-text-input name="nilai[kerjasama]" type="number" min="0" max="100" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label for="nilai_inisiatif" :value="__('Inisiatif (0-100)')" />
                                    <x-text-input name="nilai[inisiatif]" type="number" min="0" max="100" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label for="nilai_keterampilan" :value="__('Keterampilan Kerja (0-100)')" />
                                    <x-text-input name="nilai[keterampilan]" type="number" min="0" max="100" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label for="nilai_tanggung_jawab" :value="__('Tanggung Jawab (0-100)')" />
                                    <x-text-input name="nilai[tanggung_jawab]" type="number" min="0" max="100" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label for="nilai_kebersihan" :value="__('Kebersihan & Kerapian (0-100)')" />
                                    <x-text-input name="nilai[kebersihan]" type="number" min="0" max="100" class="mt-1 block w-full" required />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="catatan" :value="__('Catatan Pembimbing')" />
                            <textarea name="catatan" id="catatan" rows="4" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                placeholder="Masukkan catatan atau saran untuk siswa..."></textarea>
                            <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4 border-t pt-6">
                            <a href="{{ route('guru.penilaian.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                            <x-primary-button class="bg-indigo-600">
                                {{ __('Simpan Penilaian') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>