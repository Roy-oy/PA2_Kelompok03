@extends('layouts.app')

@section('title', 'Edit Rekam Medis')
@section('page_title', 'Edit Rekam Medis')
@section('page_subtitle', 'Memperbarui data rekam medis pasien')

@section('content')
<div class="px-4 py-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('medical_record.show', $medicalRecord->id) }}" class="mr-3 text-gray-600 hover:text-gray-800 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-file-medical-alt text-yellow-600 mr-3"></i>
                Edit Rekam Medis
            </h1>
            <span class="ml-3 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                RM: {{ $medicalRecord->pasien->no_rm }}
            </span>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('medical_record.show', $medicalRecord->id) }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition focus:ring-4 focus:ring-blue-300">
                <i class="fas fa-eye mr-2"></i> Lihat Detail
            </a>
            <a href="{{ route('medical_record.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition focus:ring-4 focus:ring-gray-200">
                <i class="fas fa-list mr-2"></i> Daftar Rekam Medis
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Edit Rekam Medis: {{ $medicalRecord->pasien->nama }}
            </h2>
            <p class="text-yellow-100 text-sm mt-1">Perbarui informasi rekam medis</p>
        </div>
        <div class="p-6">
            <form action="{{ route('medical_record.update', $medicalRecord->id) }}" method="POST" x-data="{ date: '{{ $medicalRecord->tanggal_kunjungan->format('Y-m-d') }}' }">
                @csrf
                @method('PUT')

                <!-- Patient Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                        <i class="fas fa-user text-yellow-600 mr-2"></i>
                        Informasi Pasien
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-yellow-50 p-4 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Nama Pasien</p>
                            <p class="text-gray-900 font-semibold">{{ $medicalRecord->pasien->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">NIK</p>
                            <p class="text-gray-900 font-semibold">{{ $medicalRecord->pasien->nik }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">No. RM</p>
                            <p class="text-gray-900 font-semibold">{{ $medicalRecord->pasien->no_rm }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">No. Antrian</p>
                            <p class="text-gray-900 font-semibold">{{ $medicalRecord->pendaftaran->antrian->no_antrian }}</p>
                        </div>
                    </div>
                </div>

                <!-- Medical Record Form -->
                <div class="grid grid-cols-1 gap-8">
                    <!-- Visit Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-calendar-alt text-yellow-600 mr-2"></i>
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
                                           class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 @error('tanggal_kunjungan') border-red-500 @enderror"
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
                            <i class="fas fa-stethoscope text-yellow-600 mr-2"></i>
                            Informasi Klinis
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Keluhan -->
                            <div class="md:col-span-2">
                                <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Keluhan <span class="text-red-500">*</span>
                                </label>
                                <textarea id="keluhan" name="keluhan" rows="4"
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 @error('keluhan') border-red-500 @enderror"
                                          placeholder="Masukkan keluhan pasien" required>{{ old('keluhan', $medicalRecord->keluhan) }}</textarea>
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
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 @error('diagnosis') border-red-500 @enderror"
                                          placeholder="Masukkan diagnosis" required>{{ old('diagnosis', $medicalRecord->diagnosis) }}</textarea>
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
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 @error('pengobatan') border-red-500 @enderror"
                                          placeholder="Masukkan pengobatan yang diberikan" required>{{ old('pengobatan', $medicalRecord->pengobatan) }}</textarea>
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
                                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 @error('hasil_pemeriksaan') border-red-500 @enderror"
                                          placeholder="Masukkan hasil pemeriksaan (opsional)">{{ old('hasil_pemeriksaan', $medicalRecord->hasil_pemeriksaan) }}</textarea>
                                @error('hasil_pemeriksaan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tinggi Badan -->
                            <div>
                                <label for="tinggi_badan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tinggi Badan (cm)
                                </label>
                                <input type="number" id="tinggi_badan" name="tinggi_badan" value="{{ old('tinggi_badan', $medicalRecord->tinggi_badan) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 @error('tinggi_badan') border-red-500 @enderror"
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
                                <input type="number" id="berat_badan" name="berat_badan" value="{{ old('berat_badan', $medicalRecord->berat_badan) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 @error('berat_badan') border-red-500 @enderror"
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
                                <input type="text" id="tekanan_darah" name="tekanan_darah" value="{{ old('tekanan_darah', $medicalRecord->tekanan_darah) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 @error('tekanan_darah') border-red-500 @enderror"
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
                                <input type="number" id="suhu_badan" name="suhu_badan" value="{{ old('suhu_badan', $medicalRecord->suhu_badan) }}" step="0.1"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 @error('suhu_badan') border-red-500 @enderror"
                                       placeholder="Masukkan suhu badan">
                                @error('suhu_badan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 mt-8 pt-5 border-t border-gray-200">
                        <a href="{{ route('medical_record.show', $medicalRecord->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition focus:ring-4 focus:ring-gray-200">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-5 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition focus:ring-4 focus:ring-yellow-300">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush