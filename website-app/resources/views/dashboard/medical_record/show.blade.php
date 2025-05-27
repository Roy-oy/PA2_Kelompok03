@extends('layouts.app')

@section('title', 'Detail Rekam Medis')
@section('page_title', 'Detail Rekam Medis')
@section('page_subtitle', 'Informasi lengkap rekam medis pasien')

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
                Detail Rekam Medis
            </h1>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('medical_record.edit', $medicalRecords->first()->id) }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition focus:ring-4 focus:ring-yellow-300">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('medical_record.pdf', $medicalRecords->first()->id) }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition focus:ring-4 focus:ring-blue-300">
                <i class="fas fa-file-pdf mr-2"></i> Unduh PDF
            </a>
            <form action="{{ route('medical_record.destroy', $medicalRecords->first()->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition focus:ring-4 focus:ring-red-300"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?')">
                    <i class="fas fa-trash-alt mr-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Patient Information -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-user mr-2"></i>
                Informasi Pasien
            </h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-600">Nama Pasien</p>
                <p class="text-gray-900 font-semibold">{{ $pasien->nama }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">NIK</p>
                <p class="text-gray-900 font-semibold">{{ $pasien->nik }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">No. RM</p>
                <p class="text-gray-900 font-semibold">{{ $pasien->no_rm }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Alamat</p>
                <p class="text-gray-900 font-semibold">{{ $pasien->alamat }}</p>
            </div>
        </div>
    </div>

    <!-- Medical Records History -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Riwayat Rekam Medis
            </h2>
            <p class="text-blue-100 text-sm mt-1">Semua kunjungan pasien</p>
        </div>
        <div class="p-6">
            @forelse($medicalRecords as $record)
                <div class="mb-8 border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">
                        Kunjungan: {{ $record->tanggal_kunjungan->format('d F Y') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Visit Information -->
                        <div>
                            <p class="text-sm font-medium text-gray-600">No. Antrian</p>
                            <p class="text-gray-900">{{ $record->pendaftaran->antrian->no_antrian }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Status Antrian</p>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $record->pendaftaran->antrian->status->value == 'Sedang Dilayani' ? 'bg-yellow-100 text-yellow-800' :
                                   ($record->pendaftaran->antrian->status->value == 'Selesai' ? 'bg-green-100 text-green-800' :
                                   ($record->pendaftaran->antrian->status->value == 'Dibatalkan' ? 'bg-red-100 text-red-800' :
                                   'bg-blue-100 text-blue-800')) }}">
                                {{ $record->pendaftaran->antrian->status->value }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Cluster</p>
                            <p class="text-gray-900">{{ $record->pendaftaran->cluster->nama }}</p>
                        </div>
                    </div>

                    <!-- Clinical Information -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-600">Keluhan</p>
                            <p class="text-gray-900">{{ $record->keluhan }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-600">Diagnosis</p>
                            <p class="text-gray-900">{{ $record->diagnosis }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-600">Pengobatan</p>
                            <p class="text-gray-900">{{ $record->pengobatan }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-600">Hasil Pemeriksaan</p>
                            <p class="text-gray-900">{{ $record->hasil_pemeriksaan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tinggi Badan</p>
                            <p class="text-gray-900">{{ $record->tinggi_badan ? $record->tinggi_badan . ' cm' : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Berat Badan</p>
                            <p class="text-gray-900">{{ $record->berat_badan ? $record->berat_badan . ' kg' : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tekanan Darah</p>
                            <p class="text-gray-900">{{ $record->tekanan_darah ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Suhu Badan</p>
                            <p class="text-gray-900">{{ $record->suhu_badan ? $record->suhu_badan . ' °C' : '-' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-600">Tidak ada rekam medis untuk pasien ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection