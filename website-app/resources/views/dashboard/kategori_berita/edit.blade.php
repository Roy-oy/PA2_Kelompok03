@extends('layouts.app')

@section('title', 'Edit Kategori Berita')
@section('page_title', 'Edit Kategori Berita')
@section('page_subtitle', 'Ubah data kategori berita')

@section('content')
<div class="px-4 py-5">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white">Formulir Edit Kategori Berita</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('kategori_berita.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6">
                    <!-- Nama Kategori -->
                    <div>
                        <label for="nama_kategori" class="block text-sm font-medium text-gray-700">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kategori" id="nama_kategori" 
                            value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required
                            class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('nama_kategori') border-red-500 @enderror"
                            placeholder="Masukkan nama kategori">
                        @error('nama_kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" required
                            class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($kategori->news_count > 0)
                    <div class="mt-6 p-3 bg-blue-50 rounded-md border border-blue-200">
                        <div class="text-sm text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            Kategori ini memiliki {{ $kategori->news_count }} berita terkait.
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('kategori_berita.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
        slugInput.value = nameInput.value
            .toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    });
});
</script>
@endpush
