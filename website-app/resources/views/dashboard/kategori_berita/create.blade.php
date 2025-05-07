@extends('layouts.app')

@section('title', 'Tambah Kategori Berita')
@section('page_title', 'Tambah Kategori Berita')
@section('page_subtitle', 'Buat kategori berita baru')

@section('content')
<div class="px-4 py-5">
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white">Formulir Tambah Kategori Berita</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('kategori_berita.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <!-- Nama Kategori -->
                    <div class="mb-4">
                        <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kategori" id="nama_kategori" 
                            value="{{ old('nama_kategori') }}" required
                            class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('nama_kategori') border-red-500 @enderror"
                            placeholder="Masukkan nama kategori">
                        @error('nama_kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" required
                            class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                        <a href="{{ route('kategori_berita.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
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
