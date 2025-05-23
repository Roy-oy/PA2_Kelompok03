@extends('layouts.app')

@section('title', 'Tambah Pengumuman')
@section('page_title', 'Tambah Pengumuman')
@section('page_subtitle', 'Buat pengumuman baru')

@section('content')
<div class="px-4 py-5">
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white">Formulir Tambah Pengumuman</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <!-- Judul -->
                    <div class="mb-4">
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">
                            Judul Pengumuman <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('judul') border-red-500 @enderror">
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Isi Pengumuman -->
                    <div class="mb-4">
                        <label for="isi_pengumuman" class="block text-sm font-medium text-gray-700 mb-1">
                            Isi Pengumuman <span class="text-red-500">*</span>
                        </label>
                        <textarea name="isi_pengumuman" id="isi_pengumuman" rows="5" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('isi_pengumuman') border-red-500 @enderror">{{ old('isi_pengumuman') }}</textarea>
                        @error('isi_pengumuman')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Upload -->
                    <div class="mb-4">
                        <label for="tanggal_upload" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Upload <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_upload" id="tanggal_upload" value="{{ old('tanggal_upload', now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('tanggal_upload') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Pilih tanggal hari ini atau di masa depan. Status akan otomatis 'Publish' untuk hari ini atau 'Pending' untuk tanggal berikutnya.</p>
                        @error('tanggal_upload')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Surat -->
                    <div class="mb-4">
                        <label for="file_surat" class="block text-sm font-medium text-gray-700 mb-1">
                            File Surat
                        </label>
                        <input type="file" name="file_surat" id="file_surat"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('file_surat') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Format yang diperbolehkan: PDF, DOC, DOCX. Maksimal 2MB</p>
                        @error('file_surat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                        <a href="{{ route('pengumuman.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700">
                            <i class="fas fa-save mr-2"></i> Simpan Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection