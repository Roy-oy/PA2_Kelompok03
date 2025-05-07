@extends('layouts.app')

@section('title', 'Tambah Jadwal Dokter')
@section('page_title', 'Tambah Jadwal Dokter')
@section('page_subtitle', 'Menambahkan jadwal dokter baru ke sistem')

@section('content')
<div class="px-4 py-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('jadwal_dokter.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-calendar-plus text-green-600 mr-3"></i>
                Tambah Jadwal Dokter
            </h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Formulir Jadwal Dokter
            </h2>
            <p class="text-green-100 text-sm mt-1">Isi data jadwal dokter dengan lengkap dan benar</p>
        </div>
        <div class="p-6">
            <form action="{{ route('jadwal_dokter.store') }}" method="POST">
                @csrf
                
                <!-- Form Sections -->
                <div class="grid grid-cols-1 gap-8">
                    <!-- Informasi Jadwal -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                            Informasi Jadwal
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Dokter -->
                            <div>
                                <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Dokter <span class="text-red-500">*</span>
                                </label>
                                <select id="doctor_id" name="doctor_id" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('doctor_id') border-red-500 @enderror" 
                                    required>
                                    <option value="">-- Pilih Dokter --</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Jadwal -->
                            <div>
                                <label for="schedule_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Jadwal <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="schedule_date" name="schedule_date" value="{{ old('schedule_date') }}" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('schedule_date') border-red-500 @enderror"
                                    required>
                                @error('schedule_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jam Mulai -->
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jam Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('start_time') border-red-500 @enderror" 
                                    required>
                                @error('start_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jam Selesai -->
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jam Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('end_time') border-red-500 @enderror" 
                                    required>
                                @error('end_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cluster -->
                            <div>
                                <label for="cluster_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Cluster <span class="text-red-500">*</span>
                                </label>
                                <select id="cluster_id" name="cluster_id" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('cluster_id') border-red-500 @enderror" 
                                    required>
                                    <option value="">-- Pilih Cluster --</option>
                                    @foreach($clusters as $cluster)
                                        <option value="{{ $cluster->id }}" {{ old('cluster_id') == $cluster->id ? 'selected' : '' }}>
                                            {{ $cluster->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cluster_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status" name="status" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('status') border-red-500 @enderror" 
                                    required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('jadwal_dokter.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors focus:ring-4 focus:ring-green-300">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection