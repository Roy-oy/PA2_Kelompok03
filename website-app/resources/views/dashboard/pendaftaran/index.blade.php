@extends('layouts.app')

@section('title', 'Daftar Pendaftaran')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Daftar Pendaftaran</h3>
                <p class="mt-1 text-sm text-gray-500">Mengelola pendaftaran pasien untuk tanggal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <a href="{{ route('pendaftaran.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Tambah Pendaftaran
                </a>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="mb-4">
            <input type="date" id="dateFilter" class="form-input rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   value="{{ $date }}"
                   onchange="filterByDate(this.value)">
        </div>

        <!-- Success/Error Message -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. RM</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pasien</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pembayaran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cluster</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendaftarans as $index => $pendaftaran)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $pendaftarans->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $pendaftaran->pasien->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $pendaftaran->pasien->no_rm ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($pendaftaran->jenis_pasien) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($pendaftaran->jenis_pembayaran) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $pendaftaran->cluster->nama ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $pendaftaran->keluhan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $pendaftaran->status->value ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($pendaftaran->canBeEdited())
                                    <a href="{{ route('pendaftaran.edit', $pendaftaran) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                @else
                                    <span class="text-gray-500">Edit</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                Tidak ada data pendaftaran untuk tanggal ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $pendaftarans->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function filterByDate(date) {
            window.location.href = `{{ route('pendaftaran.index') }}?date=${date}`;
        }
    </script>
@endpush
@endsection