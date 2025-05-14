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
                    <!-- Pertanyaan -->
                    <div>
                        <label for="pertanyaan" class="block text-sm font-medium text-gray-700">Pertanyaan <span class="text-red-500">*</span></label>
                        <input type="text" name="pertanyaan" id="pertanyaan" value="{{ old('pertanyaan', $faq->pertanyaan) }}" required
                            class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('pertanyaan') border-red-500 @enderror">
                        @error('pertanyaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jawaban -->
                    <div>
                        <label for="jawaban" class="block text-sm font-medium text-gray-700">Jawaban <span class="text-red-500">*</span></label>
                        <textarea name="jawaban" id="jawaban" rows="5" required
                            class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('jawaban') border-red-500 @enderror">{{ old('jawaban', $faq->jawaban) }}</textarea>
                        @error('jawaban')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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