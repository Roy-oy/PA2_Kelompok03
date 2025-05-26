@extends('layouts.app')

@section('title', 'Detail Pasien')
@section('page_title', 'Detail Pasien')
@section('page_subtitle', 'Rincian informasi pasien')

@section('content')
<div class="px-4 py-6 max-w-7xl mx-auto">
    <!-- Page Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('pasien.index') }}" class="mr-3 text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-user-circle text-green-600 mr-3"></i>
                {{ $pasien->nama }}
            </h1>
            <span class="ml-3 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                RM: {{ $pasien->no_rm }}
            </span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('pasien.edit', $pasien->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors focus:ring-2 focus:ring-green-300">
                <i class="fas fa-edit mr-2"></i>
                Edit Data
            </a>
            <button type="button" id="delete-button" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors focus:ring-2 focus:ring-red-300">
                <i class="fas fa-trash-alt mr-2"></i>
                Hapus Data
            </button>
            <a href="{{ route('pasien.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors focus:ring-2 focus:ring-gray-200">
                <i class="fas fa-list mr-2"></i>
                Daftar Pasien
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="p-4 mb-6 rounded-lg bg-green-50 border-l-4 border-green-600 relative" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3 text-lg"></i>
            <span class="text-green-800 font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" class="absolute top-4 right-4 text-green-600 hover:text-green-800" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Patient Information Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Patient Profile Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="px-6 py-6">
                <div class="flex justify-center mb-4">
                    <div class="h-24 w-24 rounded-full bg-green-50 flex items-center justify-center border border-green-200">
                        <i class="fas {{ $pasien->jenis_kelamin == 'Laki-laki' ? 'fa-male text-green-600' : 'fa-female text-green-600' }} text-4xl"></i>
                    </div>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 text-center mb-1">{{ $pasien->nama }}</h2>
                <div class="flex justify-center mt-2">
                    <span class="px-3 py-1 text-xs bg-green-50 text-green-700 rounded-full">
                        RM: {{ $pasien->no_rm }}
                    </span>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100">
                <div class="space-y-4">
                    <div class="flex items-center text-sm">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center mr-3">
                            <i class="fas {{ $pasien->jenis_kelamin == 'Laki-laki' ? 'fa-mars text-green-600' : 'fa-venus text-green-600' }} text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Jenis Kelamin</p>
                            <p class="font-medium text-gray-800">{{ $pasien->jenis_kelamin }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center mr-3">
                            <i class="fas fa-id-card text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">NIK</p>
                            <p class="font-medium text-gray-800">{{ $pasien->nik }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center mr-3">
                            <i class="fas fa-address-card text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">No. KK</p>
                            <p class="font-medium text-gray-800">{{ $pasien->no_kk ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-alt text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Tanggal Lahir</p>
                            <p class="font-medium text-gray-800">{{ $pasien->tanggal_lahir->format('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center mr-3">
                            <i class="fas fa-birthday-cake text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Umur</p>
                            <p class="font-medium text-gray-800">{{ $pasien->umur }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center mr-3">
                            <i class="fas fa-tint text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Golongan Darah</p>
                            <p class="font-medium text-gray-800">
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-50 border border-green-200 text-green-700 font-semibold text-xs">
                                    {{ $pasien->golongan_darah }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center mr-3">
                            <i class="fas fa-id-card text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">No. BPJS</p>
                            <p class="font-medium text-gray-800">
                                @if($pasien->no_bpjs)
                                    <span class="inline-block px-2 py-1 bg-green-50 text-green-700 rounded border border-green-100">{{ $pasien->no_bpjs }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle text-Green-600 mr-2"></i>
                        Informasi Pribadi
                    </h3>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-1">
                                <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>
                                Tempat Lahir
                            </dt>
                            <dd class="text-gray-900 font-medium">{{ $pasien->tempat_lahir }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-1">
                                <i class="fas fa-home mr-1 text-gray-400"></i>
                                Alamat
                            </dt>
                            <dd class="text-gray-900">{{ $pasien->alamat }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-1">
                                <i class="fas fa-phone mr-1 text-gray-400"></i>
                                No. HP
                            </dt>
                            <dd class="text-gray-900">
                                @if($pasien->no_hp)
                                    <a href="tel:{{ $pasien->no_hp }}" class="text-green-600 hover:underline">{{ $pasien->no_hp }}</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-1">
                                <i class="fas fa-briefcase mr-1 text-gray-400"></i>
                                Pekerjaan
                            </dt>
                            <dd class="text-gray-900">{{ $pasien->pekerjaan ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-notes-medical text-green-600 mr-2"></i>
                        Riwayat Medis
                    </h3>
                </div>
                <div class="px-6 py-5">
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-2 flex items-center">
                            <i class="fas fa-allergies mr-1 text-gray-400"></i>
                            Riwayat Alergi
                        </h4>
                        <div class="p-3 bg-green-50 rounded-md border border-green-200">
                            <p class="text-gray-800">{{ $pasien->riwayat_alergi ?? 'Tidak ada riwayat alergi' }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2 flex items-center">
                            <i class="fas fa-heartbeat mr-1 text-gray-400"></i>
                            Riwayat Penyakit
                        </h4>
                        <div class="p-3 bg-green-50 rounded-md border border-green-200">
                            <p class="text-gray-800">{{ $pasien->riwayat_penyakit ?? 'Tidak ada riwayat penyakit' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div id="modal-backdrop" class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity"></div>
        
        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-title">
                            Konfirmasi Hapus Data Pasien
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">
                                Apakah Anda yakin ingin menghapus data pasien <span class="font-semibold">{{ $pasien->nama }}</span>? Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form action="{{ route('pasien.destroy', $pasien->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto">
                        Hapus
                    </button>
                </form>
                <button type="button" id="cancel-btn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('delete-modal');
        const modalBackdrop = document.getElementById('modal-backdrop');
        const cancelBtn = document.getElementById('cancel-btn');
        const deleteButton = document.getElementById('delete-button');
        
        // Open modal
        deleteButton.addEventListener('click', function() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
        
        // Close modal
        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        cancelBtn.addEventListener('click', closeModal);
        modalBackdrop.addEventListener('click', closeModal);
    });
</script>
@endpush