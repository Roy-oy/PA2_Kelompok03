@extends('layouts.app')

@section('title', 'Detail Pasien')
@section('page_title', 'Detail Pasien')
@section('page_subtitle', 'Melihat rincian informasi pasien')

@section('content')
<div class="px-4 py-5">
    <!-- Page Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('pasien.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-circle text-green-600 mr-3"></i>
                {{ $pasien->nama }}
            </h1>
            <span class="ml-3 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center">
                RM: {{ $pasien->no_rm }}
            </span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('pasien.edit', $pasien->id) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors focus:ring-4 focus:ring-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Data
            </a>
            <button type="button" id="delete-button" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700 transition-colors focus:ring-4 focus:ring-red-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus Data
            </button>
            <a href="{{ route('pasien.index') }}" class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors focus:ring-4 focus:ring-green-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Daftar Pasien
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="p-4 mb-6 rounded-lg bg-green-50 border-l-4 border-green-500 relative" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
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
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-6">
                <div class="flex justify-center mb-4">
                    <div class="h-28 w-28 rounded-full bg-white flex items-center justify-center border-4 border-white shadow-md">
                        <i class="fas {{ $pasien->jenis_kelamin == 'Laki-laki' ? 'fa-male text-blue-500' : 'fa-female text-pink-500' }} text-5xl"></i>
                    </div>
                </div>
                <h2 class="text-xl font-bold text-white text-center mb-1">{{ $pasien->nama }}</h2>
                <div class="flex justify-center mt-2">
                    <span class="px-3 py-1 text-xs bg-white bg-opacity-25 text-white rounded-full flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                        RM: {{ $pasien->no_rm }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center text-sm">
                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas {{ $pasien->jenis_kelamin == 'Laki-laki' ? 'fa-mars text-blue-500' : 'fa-venus text-pink-500' }}"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Jenis Kelamin</p>
                            <p class="font-medium text-gray-800">{{ $pasien->jenis_kelamin }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-alt text-green-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Tanggal Lahir</p>
                            <p class="font-medium text-gray-800">{{ $pasien->tanggal_lahir->format('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                            <i class="fas fa-birthday-cake text-purple-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Umur</p>
                            <p class="font-medium text-gray-800">{{ $pasien->umur }} tahun</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center mr-3">
                            <i class="fas fa-tint text-red-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Golongan Darah</p>
                            <p class="font-medium text-gray-800">
                                @if($pasien->golongan_darah != 'Tidak Diketahui')
                                    <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-red-50 border border-red-200 text-red-700 font-bold text-xs">
                                        {{ $pasien->golongan_darah }}
                                    </span>
                                @else
                                    {{ $pasien->golongan_darah }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-id-card text-blue-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">No. BPJS</p>
                            <p class="font-medium text-gray-800">
                                @if($pasien->no_bpjs)
                                    <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 rounded border border-blue-100">{{ $pasien->no_bpjs }}</span>
                                @else
                                    <span class="text-gray-400">Tidak ada</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 border border-gray-100">
                <div class="border-b border-gray-200 px-6 py-4 bg-gray-50/80">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Informasi Pribadi
                    </h3>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Tempat Lahir
                            </dt>
                            <dd class="mt-1 text-gray-900 font-medium">{{ $pasien->tempat_lahir }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Alamat
                            </dt>
                            <dd class="mt-1 text-gray-900">{{ $pasien->alamat }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Nomor Telepon
                            </dt>
                            <dd class="mt-1 text-gray-900">
                                @if($pasien->no_telepon)
                                    <a href="tel:{{ $pasien->no_telepon }}" class="text-blue-600 hover:underline">{{ $pasien->no_telepon }}</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 flex items-center mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Pekerjaan
                            </dt>
                            <dd class="mt-1 text-gray-900">{{ $pasien->pekerjaan ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="border-b border-gray-200 px-6 py-4 bg-gray-50/80">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-notes-medical text-red-500 mr-2"></i>
                        Riwayat Medis
                    </h3>
                </div>
                <div class="px-6 py-5">
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500 mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Riwayat Alergi
                        </h4>
                        <div class="p-3.5 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-800">{{ $pasien->riwayat_alergi ?? 'Tidak ada riwayat alergi' }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Riwayat Penyakit
                        </h4>
                        <div class="p-3.5 bg-gray-50 rounded-lg border border-gray-200">
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
        <div id="modal-backdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Konfirmasi Hapus Data Pasien
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus data pasien <span class="font-semibold">{{ $pasien->nama }}</span>? Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait pasien ini.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form action="{{ route('pasien.destroy', $pasien->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Hapus Data
                    </button>
                </form>
                <button type="button" id="cancel-btn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
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
        // Delete confirmation modal
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