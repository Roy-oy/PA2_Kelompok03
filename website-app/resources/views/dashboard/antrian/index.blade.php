@extends('layouts.app')

@section('title', 'Daftar Antrian')
@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Daftar Antrian</h3>
                <p class="mt-1 text-sm text-gray-500">Mengelola antrian pasien</p>
            </div>
            <div>
                <a href="{{ route('antrian.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Tambah Antrian
                </a>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="mb-4">
            <input type="date" id="dateFilter" class="form-input rounded-lg" 
                   value="{{ request('date', date('Y-m-d')) }}"
                   onchange="filterByDate(this.value)">
        </div>

        <!-- Table -->
        <div class="overflow-x-auto" id="antrianTable">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dokter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($antrians as $antrian)
                    <tr>
                        <td class="px-6 py-4">{{ $antrian->no_antrian }}</td>
                        <td class="px-6 py-4">
                            {{ $antrian->pasiens->nama }}
                            <div class="text-sm text-gray-500">{{ $antrian->complaint }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $antrian->doctors->nama }}</td>
                        <td class="px-6 py-4">
                            <select onchange="updateStatus('{{ $antrian->id }}', this.value)" 
                                    class="form-select rounded-lg">
                                <option value="menunggu" {{ $antrian->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="dipanggil" {{ $antrian->status === 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                                <option value="selesai" {{ $antrian->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('antrian.edit', $antrian->id) }}" class="btn btn-sm btn-info mr-2">Edit</a>
                            <button onclick="deleteAntrian('{{ $antrian->id }}')" class="btn btn-sm btn-danger">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">Tidak ada data antrian</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterByDate(date) {
    window.location.href = `/antrian/by-date?date=${date}`;
}

function updateStatus(id, status) {
    fetch(`/antrian/${id}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Reload halaman untuk memperbarui data
            window.location.reload();
        }
    });
}

function deleteAntrian(id) {
    if (confirm('Apakah Anda yakin ingin menghapus antrian ini?')) {
        fetch(`/antrian/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            }
        });
    }
}
</script>
@endpush
@endsection