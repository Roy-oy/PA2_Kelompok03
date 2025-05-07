@extends('layouts.app')

@section('title', 'Tambah Antrian')
@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <form action="{{ route('antrian.store') }}" method="POST" class="space-y-6" id="antrianForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pasien -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pasien</label>
                    <select name="pasiens_id" class="form-select rounded-lg w-full @error('pasiens_id') border-red-500 @enderror" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($pasiens as $pasien)
                            <option value="{{ $pasien->id }}" {{ old('pasiens_id') == $pasien->id ? 'selected' : '' }}>
                                {{ $pasien->nama }} - RM: {{ $pasien->no_rm }}
                            </option>
                        @endforeach
                    </select>
                    @error('pasiens_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dokter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dokter</label>
                    <select name="doctors_id" class="form-select rounded-lg w-full @error('doctors_id') border-red-500 @enderror" required>
                        <option value="">Pilih Dokter</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctors_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctors_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cluster & Pembayaran -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cluster</label>
                        <select name="cluster" class="form-select rounded-lg w-full @error('cluster') border-red-500 @enderror" required>
                            <option value="">Pilih Cluster</option>
                            @foreach(['cluster_1', 'cluster_2', 'cluster_3', 'cluster_4', 'cluster_5'] as $cluster)
                                <option value="{{ $cluster }}" {{ old('cluster') == $cluster ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $cluster)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('cluster')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pembayaran</label>
                        <select name="pembayaran" class="form-select rounded-lg w-full @error('pembayaran') border-red-500 @enderror" required>
                            <option value="">Pilih Pembayaran</option>
                            <option value="bpjs" {{ old('pembayaran') == 'bpjs' ? 'selected' : '' }}>BPJS</option>
                            <option value="umum" {{ old('pembayaran') == 'umum' ? 'selected' : '' }}>Umum</option>
                        </select>
                        @error('pembayaran')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Keluhan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan</label>
                <textarea name="complaint" rows="3" class="form-textarea rounded-lg w-full @error('complaint') border-red-500 @enderror">{{ old('complaint') }}</textarea>
                @error('complaint')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('antrian.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Antrian</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('antrianForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.href = "{{ route('antrian.index') }}";
        }
    });
});
</script>
@endpush
@endsection