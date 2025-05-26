@extends('layouts.app')

@section('title', 'Manajemen Pengguna Aplikasi')
@section('page_title', 'Manajemen Pengguna Aplikasi')
@section('page_subtitle', 'Mengelola data pengguna aplikasi mobile')

@section('content')
<div class="px-4 py-6 max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Pengguna Aplikasi</h3>
                    <p class="mt-1 text-sm text-gray-600">Pengguna yang telah terdaftar pada aplikasi mobile.</p>
                </div>
                {{-- <div>
                    <a href="{{ route('app-users.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-300">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Pengguna
                    </a>
                </div> --}}
            </div>

            @if(session('success'))
                <div class="p-4 mb-6 rounded-lg bg-green-50 border-l-4 border-green-600">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-3 text-lg"></i>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-6 rounded-lg bg-red-50 border-l-4 border-red-600">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-600 mr-3 text-lg"></i>
                        <span class="text-red-800 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. HP</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($appUsers as $index => $appUser)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $index + $appUsers->firstItem() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $appUser->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $appUser->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $appUser->no_hp ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <form action="{{ route('app-users.destroy', $appUser->id) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if($appUsers->isEmpty())
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm font-medium text-gray-500">
                                    Tidak ada data pengguna aplikasi.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $appUsers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmation dialog for delete
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush