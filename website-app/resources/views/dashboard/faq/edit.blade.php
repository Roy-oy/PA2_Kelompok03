@extends('layouts.app')

@section('title', 'Edit FAQ')
@section('page_title', 'Edit FAQ')
@section('page_subtitle', 'Ubah data FAQ')

@section('content')
<div class="px-4 py-5">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white">Formulir Edit FAQ</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('faq.update', $faq->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6">
                    <!-- Kategori -->
                    <div class="mb-4">
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori" id="kategori" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('kategori') border-red-500 @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriOptions as $kategori)
                                <option value="{{ $kategori }}" {{ old('kategori', $faq->kategori) == $kategori ? 'selected' : '' }}>
                                    {{ ucfirst($kategori) }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pertanyaan -->
                    <div class="mb-4">
                        <label for="question" class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                        <input type="text" name="question" id="question" value="{{ old('question', $faq->question) }}" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('question') border-red-500 @enderror">
                        @error('question')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jawaban -->
                    <div class="mb-4">
                        <label for="answer" class="block text-sm font-medium text-gray-700 mb-1">Jawaban <span class="text-red-500">*</span></label>
                        <textarea name="answer" id="answer" rows="5" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('answer') border-red-500 @enderror">{{ old('answer', $faq->answer) }}</textarea>
                        @error('answer')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('faq.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection