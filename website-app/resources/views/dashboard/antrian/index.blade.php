@extends('layouts.app')

@section('title', 'Daftar Antrian')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Daftar Antrian</h3>
                <p class="mt-1 text-sm text-gray-500">Mengelola antrian pasien untuk tanggal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="mb-4">
            <label for="dateFilter" class="sr-only">Filter by Date</label>
            <input type="date" id="dateFilter" class="form-input rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   value="{{ $date }}"
                   onchange="filterByDate(this.value)">
        </div>

        <!-- Success/Error Messages -->
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Antrian</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. RM</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cluster</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($antrians as $index => $antrian)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $antrian->no_antrian }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $antrian->pendaftaran->pasien->no_rm ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $antrian->pendaftaran->pasien->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $antrian->cluster->nama ?? '-' }}</td>
                            <td class="px-6 py-4 max-w-xs whitespace-normal break-words">{{ $antrian->pendaftaran->keluhan ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($antrian->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('antrian.update', $antrian) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()" aria-label="Ubah Status Antrian">
                                        @foreach (\App\Enums\StatusAntrian::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $antrian->status === $status ? 'selected' : '' }}>{{ $status->value }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            {{-- <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                <a href="{{ route('pendaftaran.edit', $antrian->pendaftaran) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Edit Pendaftaran
                                </a>
                                <form action="{{ route('antrian.destroy', $antrian) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus antrian nomor {{ $antrian->no_antrian }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Hapus
                                    </button>
                                </form>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                Tidak ada data antrian untuk tanggal ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $antrians->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function filterByDate(date) {
            window.location.href = `{{ route('antrian.index') }}?date=${date}`;
        }
    </script>
@endpush
@endsection