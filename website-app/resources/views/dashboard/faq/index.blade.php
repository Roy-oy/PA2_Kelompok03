@extends('layouts.app')

@section('title', 'Manajemen FAQ')
@section('page_title', 'Manajemen FAQ')
@section('page_subtitle', 'Mengelola daftar pertanyaan yang sering ditanyakan')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex justify-between mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Daftar FAQ</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola pertanyaan dan jawaban untuk ditampilkan pada aplikasi.
                </p>
            </div>
            <div>
                <a href="{{ route('faq.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah FAQ
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 leading-normal text-green-700 bg-green-100 rounded-lg" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jawaban</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($faqs as $index => $faq)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $index + $faqs->firstItem() }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900">{{ $faq->question }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-700 text-sm">{!! nl2br(e(Str::limit($faq->answer, 100))) !!}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('faq.edit', $faq->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('faq.destroy', $faq->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if($faqs->count() === 0)
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm font-medium text-gray-500">
                                Tidak ada data FAQ.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $faqs->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin menghapus FAQ ini?')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush
