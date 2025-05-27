@extends('layouts.app')

@section('title', 'Daftar Rekam Medis')
@section('page_title', 'Daftar Rekam Medis')
@section('page_subtitle', 'Mengelola data rekam medis pasien puskesmas')

@section('content')
<div class="px-4 py-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="mb-4 md:mb-0">
            <h3 class="text-2xl font-semibold text-gray-900">Daftar Rekam Medis</h3>
            <p class="mt-1 text-sm text-gray-500">Kelola data rekam medis pasien</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('medical_record.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:ring-4 focus:ring-blue-300">
                <i class="fas fa-plus mr-2"></i> Tambah Rekam Medis
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-blue-50 border-l-4 border-blue-600">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-blue-600 mr-3 text-lg"></i>
                <span class="text-blue-800 font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Search and Filter Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form action="{{ route('medical_record.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pasien</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                           placeholder="Masukkan NIK atau Nama Pasien">
                </div>
            </div>
            <div class="flex-1">
                <label for="pasien_id" class="block text-sm font-medium text-gray-700 mb-1">Filter Pasien</label>
                <select id="pasien_id" name="pasien_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Semua Pasien</option>
                    @foreach(\App\Models\Pasien::orderBy('nama')->get() as $pasien)
                        <option value="{{ $pasien->id }}" {{ request('pasien_id') == $pasien->id ? 'selected' : '' }}>{{ $pasien->nama }} ({{ $pasien->nik }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:ring-4 focus:ring-blue-300">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. RM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kunjungan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Antrian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($medicalRecords as $record)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->pasien->no_rm }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->pasien->nik }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $record->pasien->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->tanggal_kunjungan->format('d F Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $record->pendaftaran->antrian->status->value == 'Sedang Dilayani' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($record->pendaftaran->antrian->status->value == 'Selesai' ? 'bg-green-100 text-green-800' : 
                                       ($record->pendaftaran->antrian->status->value == 'Dibatalkan' ? 'bg-red-100 text-red-800' : 
                                       'bg-blue-100 text-blue-800')) }}">
                                    {{ $record->pendaftaran->antrian->status->value }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                <a href="{{ route('medical_record.show', $record->id) }}" class="text-blue-600 hover:text-blue-800 transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('medical_record.edit', $record->id) }}" class="text-yellow-600 hover:text-yellow-800 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('medical_record.destroy', $record->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data rekam medis ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6">
            {{ $medicalRecords->appends(['search' => request('search'), 'pasien_id' => request('pasien_id')])->links() }}
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
                if (confirm('Apakah Anda yakin ingin menghapus rekam medis ini?')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush