@extends('layouts.app')

@section('title', 'Edit Klaster')
@section('page_title', 'Edit Klaster')
@section('page_subtitle', 'Perbarui data klaster')

@section('content')
<div class="px-4 py-5">
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white">Formulir Edit Klaster</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('klaster.update', $cluster->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6">
                    <!-- Nama Klaster -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Klaster <span class="text-red-500">*</span></label>
                        <select name="nama" id="nama" 
                            class="gender-select pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('nama') border-red-500 @enderror">
                            <option value="">-- Pilih Klaster --</option>
                            <option value="Klaster 1" {{ $cluster->nama == 'Klaster 1' ? 'selected' : '' }}>Klaster 1</option>
                            <option value="Klaster 2" {{ $cluster->nama == 'Klaster 2' ? 'selected' : '' }}>Klaster 2</option>
                            <option value="Klaster 3" {{ $cluster->nama == 'Klaster 3' ? 'selected' : '' }}>Klaster 3</option>
                            <option value="Klaster 4" {{ $cluster->nama == 'Klaster 4' ? 'selected' : '' }}>Klaster 4</option>
                            <option value="Klaster 5" {{ $cluster->nama == 'Klaster 5' ? 'selected' : '' }}>Klaster 5</option>
                        </select>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                

                    <!-- Deskripsi -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea id="summernote" name="description" rows="3" 
                            class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('description') border-red-500 @enderror"
                            placeholder="Masukkan deskripsi klaster">{{ old('description', $cluster->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('klaster.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700">
                        <i class="fas fa-save mr-2"></i> Simpan Perubaha
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection