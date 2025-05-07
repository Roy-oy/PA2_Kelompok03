@extends('layouts.app')

@section('title', 'Edit Antrian')
@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <form action="{{ route('antrian.update', $antrian->id) }}" method="POST" class="space-y-6" id="editForm">
            @csrf
            @method('PUT')

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="form-select rounded-lg w-full @error('status') border-red-500 @enderror" required>
                    <option value="menunggu" {{ $antrian->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="dipanggil" {{ $antrian->status === 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                    <option value="selesai" {{ $antrian->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Keluhan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan</label>
                <textarea name="complaint" rows="3" class="form-textarea rounded-lg w-full @error('complaint') border-red-500 @enderror">{{ old('complaint', $antrian->complaint) }}</textarea>
                @error('complaint')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('antrian.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('editForm').addEventListener('submit', function(e) {
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