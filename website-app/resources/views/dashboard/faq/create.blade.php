@extends('layouts.app')

@section('title', 'Tambah FAQ')
@section('page_title', 'Tambah FAQ')
@section('page_subtitle', 'Tambah pertanyaan dan jawaban baru')

@section('content')
<div class="px-4 py-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('faq.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-question-circle text-green-600 mr-3"></i>
                Tambah FAQ Baru
            </h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Formulir FAQ
            </h2>
            <p class="text-green-100 text-sm mt-1">Isi informasi pertanyaan dan jawaban dengan lengkap dan benar</p>
        </div>
        <div class="p-6">
            <form action="{{ route('faq.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <!-- Pertanyaan -->
                    <div class="mb-4">
                        <label for="pertanyaan" class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                        <input type="text" name="question" id="pertanyaan" value="{{ old('question') }}" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('question') border-red-500 @enderror">
                        @error('question')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jawaban -->
                    <div class="mb-4">
                        <label for="jawaban" class="block text-sm font-medium text-gray-700 mb-1">Jawaban <span class="text-red-500">*</span></label>
                        <textarea name="answer" id="jawaban" rows="6" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('answer') border-red-500 @enderror">{{ old('answer') }}</textarea>
                        @error('answer')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('faq.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200">
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
