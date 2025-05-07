@extends('layouts.app')

@section('title', 'Edit Berita')
@section('page_title', 'Edit Berita')
@section('page_subtitle', 'Ubah informasi berita')

@section('content')
<div class="px-4 py-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('berita.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-newspaper text-green-600 mr-3"></i>
                Edit Berita
            </h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit Berita: {{ $berita->judul }}
            </h2>
            <p class="text-green-100 text-sm mt-1">Perbarui informasi berita dengan data terbaru</p>
        </div>
        <div class="p-6">
            <form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Form Sections -->
                <div class="grid grid-cols-1 gap-8">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-info-circle text-green-600 mr-2"></i>
                            Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Judul -->
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700">Judul Berita <span class="text-red-500">*</span></label>
                                <input type="text" name="judul" id="judul" value="{{ old('judul', $berita->judul) }}" required
                                    class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('judul') border-red-500 @enderror">
                                @error('judul')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label for="kategori_berita_id" class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                                <select name="kategori_berita_id" id="kategori_berita_id" required
                                    class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('kategori_berita_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoriBerita as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_berita_id', $berita->kategori_berita_id) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_berita_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Upload -->
                            <div>
                                <label for="tanggal_upload" class="block text-sm font-medium text-gray-700">Tanggal Upload <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_upload" id="tanggal_upload" 
                                    value="{{ old('tanggal_upload', $berita->tanggal_upload->format('Y-m-d')) }}" required
                                    class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('tanggal_upload') border-red-500 @enderror">
                                @error('tanggal_upload')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Current Photo -->
                            @if($berita->photo)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Foto Saat Ini</label>
                                <div class="relative">
                                    <img src="{{ Storage::url($berita->photo) }}" alt="Current Photo" class="max-w-xs rounded-lg shadow-sm">
                                </div>
                            </div>
                            @endif

                            <!-- Photo Upload -->
                            <div>
                                <label for="photo" class="block text-sm font-medium text-gray-700">Ganti Foto</label>
                                <input type="file" name="photo" id="photo" accept="image/*"
                                    class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('photo') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, JPEG. Maksimal 2MB</p>
                                @error('photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Preview Image -->
                            <div class="hidden" id="imagePreview">
                                <label class="block text-sm font-medium text-gray-700">Preview Foto Baru</label>
                                <img src="" alt="Preview" class="max-w-xs rounded-lg shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-file-alt text-green-600 mr-2"></i>
                            Konten Berita
                        </h3>
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Isi Berita -->
                            <div>
                                <label for="isi_berita" class="block text-sm font-medium text-gray-700">Konten Berita <span class="text-red-500">*</span></label>
                                <textarea name="isi_berita" id="isi_berita" rows="10" required
                                    class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('isi_berita') border-red-500 @enderror">{{ old('isi_berita', $berita->isi_berita) }}</textarea>
                                @error('isi_berita')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('berita.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors focus:ring-4 focus:ring-green-300">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview
        const photoInput = document.getElementById('photo');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = imagePreview.querySelector('img');

        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush
@endsection