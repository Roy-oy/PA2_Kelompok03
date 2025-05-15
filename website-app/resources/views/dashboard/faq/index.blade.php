@extends('layouts.app')

@section('title', 'Manajemen FAQ')
@section('page_title', 'Manajemen FAQ')
@section('page_subtitle', 'Kelola daftar pertanyaan yang sering ditanyakan')

@section('content')
<div class="px-4 py-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-question-circle text-green-600 mr-3"></i>
                Manajemen FAQ
            </h1>
        </div>
        <div>
            <a href="{{ route('faq.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors focus:ring-4 focus:ring-green-300">
                <i class="fas fa-plus mr-2"></i>
                Tambah FAQ Baru
            </a>
        </div>
    </div>

    <!-- All FAQs in One Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-list mr-2"></i>
                Daftar Semua FAQ
            </h2>
        </div>
        
        @if(($allFaqs && $allFaqs->count() > 0) || ($uncategorizedFaqs && $uncategorizedFaqs->count() > 0))
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jawaban</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $index = 1;
                    @endphp

                    <!-- Categorized FAQs -->
                    @foreach($categorizedFaqs as $category)
                        @foreach($category->faqs as $faq)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index++ }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $faq->question }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($faq->answer, 100) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    {{ $category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('faq.show', $faq->id) }}" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('faq.edit', $faq->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('faq.destroy', $faq->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach

                    <!-- Uncategorized FAQs -->
                    @foreach($uncategorizedFaqs as $faq)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index++ }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $faq->question }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($faq->answer, 100) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                                Tanpa Kategori
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('faq.show', $faq->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('faq.edit', $faq->id) }}" class="text-yellow-500 hover:text-yellow-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('faq.destroy', $faq->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3">
            {{ $uncategorizedFaqs->links() }}
        </div>
        @else
        <div class="p-6 text-center text-gray-500">
            Belum ada data FAQ yang tersedia
        </div>
        @endif
    </div>
</div>
@endsection