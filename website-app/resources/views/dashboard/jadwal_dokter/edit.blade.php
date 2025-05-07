@extends('layouts.app')

@section('title', 'Edit Jadwal Dokter')
@section('page_title', 'Edit Jadwal Dokter')
@section('page_subtitle', 'Memperbarui jadwal praktek dokter')

@section('content')
<div class="px-4 py-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-calendar-alt text-amber-500 mr-3"></i>
            Edit Jadwal Dokter
        </h1>
        <a href="{{ route('jadwal_dokter.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Edit Jadwal Dokter
            </h2>
            <p class="text-amber-100 text-sm mt-1">Perbarui jadwal praktek dokter</p>
        </div>
        
        <div class="p-6">
            <form action="{{ route('jadwal_dokter.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dokter -->
                    <div>
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Dokter <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user-md text-gray-400"></i>
                            </div>
                            <select name="doctor_id" id="doctor_id" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                                <option value="">Pilih Dokter</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ $schedule->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('doctor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="schedule_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            <input type="date" name="schedule_date" id="schedule_date" 
                                value="{{ $schedule->schedule_date->format('Y-m-d') }}" 
                                class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                        </div>
                        @error('schedule_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Mulai -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Jam Mulai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-clock text-gray-400"></i>
                            </div>
                            <input type="time" name="start_time" id="start_time" 
                                value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" 
                                class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                        </div>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Selesai -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Jam Selesai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-clock text-gray-400"></i>
                            </div>
                            <input type="time" name="end_time" id="end_time" 
                                value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}" 
                                class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                        </div>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cluster -->
                    <div>
                        <label for="cluster_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Cluster <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hospital text-gray-400"></i>
                            </div>
                            <select name="cluster_id" id="cluster_id" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                                <option value="">Pilih Cluster</option>
                                @foreach($clusters as $cluster)
                                    <option value="{{ $cluster->id }}" {{ $schedule->cluster_id == $cluster->id ? 'selected' : '' }}>
                                        {{ $cluster->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('cluster_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-toggle-on text-gray-400"></i>
                            </div>
                            <select name="status" id="status" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5" required>
                                <option value="active" {{ $schedule->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ $schedule->status === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('jadwal_dokter.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition-colors focus:ring-4 focus:ring-amber-300">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection