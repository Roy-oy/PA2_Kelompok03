@extends('layouts.app')

@section('title', 'Tambah Rekam Medis')
@section('page_title', 'Tambah Rekam Medis')
@section('page_subtitle', 'Membuat rekam medis baru untuk pasien')

@section('content')
<div class="px-4 py-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('medical_record.index') }}" class="mr-3 text-gray-600 hover:text-gray-800 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-file-medical text-blue-600 mr-3"></i>
                Tambah Rekam Medis
            </h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Formulir Rekam Medis
            </h2>
            <p class="text-blue-100 text-sm mt-1">Isi data rekam medis dengan lengkap</p>
        </div>
        <div class="p-6">
            <form action="{{ route('medical_record.store') }}" method="POST" x-data="{ date: '' }">
                @csrf

                <!-- Patient Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Informasi Pasien
                    </h3>
                    @if($antrian)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-blue-50 p-4 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Nama Pasien</p>
                                <p class="text-gray-900 font-semibold">{{ $antrian->pendaftaran->pasien->nama }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">NIK</p>
                                <p class="text-gray-900 font-semibold">{{ $antrian->pendaftaran->pasien->nik }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">No. RM</p>
                                <p class="text-gray-900 font-semibold">{{ $antrian->pendaftaran->pasien->no_rm }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">No. Antrian</p>
                                <p class="text-gray-900 font-semibold">{{ $antrian->no_antrian }}</p>
                            </div>
                            <input type="hidden" name="pendaftaran_id" value="{{ $antrian->pendaftaran_id }}">
                            <input type="hidden" name="pasien_id" value="{{ $antrian->pendaftaran->pasien->id }}">
                        </div>
                    @else
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <p class="text-yellow-800">Tidak ada pasien dengan status antrian "Sedang Dilayani".</p>
                        </div>
                    @endif
                </div>

                <!-- Medical Record Form -->
                @if($antrian)
                    <div class="grid grid-cols-1 gap-8">
                        <!-- Visit Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                                Informasi Kunjungan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tanggal Kunjungan -->
                                <div>
                                    <label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tanggal Kunjungan <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                        </div>
                                        <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" x-model="date"
                                               class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('tanggal_kunjungan') border-red-500 @enderror"
                                               required>
                                    </div>
                                    @error('tanggal_kunjungan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Clinical Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                                <i class="fas fa-stethoscope text-blue-600 mr-2"></i>
                                Informasi Klinis
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Keluhan -->
                                <div class="md:col-span-2">
                                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">
                                        Keluhan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="keluhan" name="keluhan" rows="4"
                                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('keluhan') border-red-500 @enderror"
                                              placeholder="Masukkan keluhan pasien" required>{{ old('keluhan') }}</textarea>
                                    @error('keluhan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Diagnosis -->
                                <div class="md:col-span-2">
                                    <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1">
                                        Diagnosis <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="diagnosis" name="diagnosis" rows="4"
                                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('diagnosis') border-red-500 @enderror"
                                              placeholder="Masukkan diagnosis" required>{{ old('diagnosis') }}</textarea>
                                    @error('diagnosis')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Pengobatan -->
                                <div class="md:col-span-2">
                                    <label for="pengobatan" class="block text-sm font-medium text-gray-700 mb-1">
                                        Pengobatan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="pengobatan" name="pengobatan" rows="4"
                                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('pengobatan') border-red-500 @enderror"
                                              placeholder="Masukkan pengobatan yang diberikan" required>{{ old('pengobatan') }}</textarea>
                                    @error('pengobatan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Hasil Pemeriksaan -->
                                <div class="md:col-span-2">
                                    <label for="hasil_pemeriksaan" class="block text-sm font-medium text-gray-700 mb-1">
                                        Hasil Pemeriksaan
                                    </label>
                                    <textarea id="hasil_pemeriksaan" name="hasil_pemeriksaan" rows="4"
                                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('hasil_pemeriksaan') border-red-500 @enderror"
                                              placeholder="Masukkan hasil pemeriksaan (opsional)">{{ old('hasil_pemeriksaan') }}</textarea>
                                    @error('hasil_pemeriksaan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tinggi Badan -->
                                <div>
                                    <label for="tinggi_badan" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tinggi Badan (cm)
                                    </label>
                                    <input type="number" id="tinggi_badan" name="tinggi_badan" value="{{ old('tinggi_badan') }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('tinggi_badan') border-red-500 @enderror"
                                           placeholder="Masukkan tinggi badan">
                                    @error('tinggi_badan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Berat Badan -->
                                <div>
                                    <label for="berat_badan" class="block text-sm font-medium text-gray-700 mb-1">
                                        Berat Badan (kg)
                                    </label>
                                    <input type="number" id="berat_badan" name="berat_badan" value="{{ old('berat_badan') }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('berat_badan') border-red-500 @enderror"
                                           placeholder="Masukkan berat badan">
                                    @error('berat_badan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tekanan Darah -->
                                <div>
                                    <label for="tekanan_darah" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tekanan Darah
                                    </label>
                                    <input type="text" id="tekanan_darah" name="tekanan_darah" value="{{ old('tekanan_darah') }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('tekanan_darah') border-red-500 @enderror"
                                           placeholder="Contoh: 120/80 mmHg">
                                    @error('tekanan_darah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Suhu Badan -->
                                <div>
                                    <label for="suhu_badan" class="block text-sm font-medium text-gray-700 mb-1">
                                        Suhu Badan (°C)
                                    </label>
                                    <input type="number" id="suhu_badan" name="suhu_badan" value="{{ old('suhu_badan') }}" step="0.1"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('suhu_badan') border-red-500 @enderror"
                                           placeholder="Masukkan suhu badan">
                                    @error('suhu_badan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 mt-8 pt-5 border-t border-gray-200">
                        <a href="{{ route('medical_record.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition focus:ring-4 focus:ring-gray-200">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition focus:ring-4 focus:ring-blue-300">
                            <i class="fas fa-save mr-2"></i> Simpan Rekam Medis
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush