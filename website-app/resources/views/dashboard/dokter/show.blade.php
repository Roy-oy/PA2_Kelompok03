@extends('layouts.app')

@section('title', 'Detail Dokter')
@section('page_title', 'Detail Dokter')
@section('page_subtitle', 'Melihat rincian informasi dokter')

@section('content')
<div class="px-4 py-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('dokter.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $dokter->nama }}</h1>
            <span class="ml-3 px-3 py-1 text-sm bg-{{ $dokter->status == 'active' ? 'green' : 'red' }}-100 text-{{ $dokter->status == 'active' ? 'green' : 'red' }}-800 rounded-full">
                {{ $dokter->status == 'active' ? 'Aktif' : 'Non-Aktif' }}
            </span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('dokter.edit', $dokter->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Edit Data
            </a>
            <button type="button" id="delete-button" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                <i class="fas fa-trash mr-2"></i>
                Hapus Data
            </button>
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column - Profile Photo -->
        <div class="lg:col-span-4">
            <!-- Profile Photo Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        @if($dokter->foto_profil)
                            <div class="h-96 w-full overflow-hidden border border-gray-200">
                                <img src="{{ asset('storage/' . $dokter->foto_profil) }}" alt="Foto {{ $dokter->nama }}" class="h-full w-full object-contain">
                            </div>
                        @else
                            <div class="h-96 w-full bg-gray-100 flex items-center justify-center border border-gray-200">
                                <i class="fas {{ $dokter->jenis_kelamin == 'Laki-laki' ? 'fa-male' : 'fa-female' }} text-6xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Kontak</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($dokter->no_telepon)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 w-5"></i>
                            <a href="tel:{{ $dokter->no_telepon }}" class="text-gray-600 hover:text-gray-900 ml-3">{{ $dokter->no_telepon }}</a>
                        </div>
                        @endif
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 w-5"></i>
                            <a href="mailto:{{ $dokter->email }}" class="text-gray-600 hover:text-gray-900 ml-3">{{ $dokter->email }}</a>
                        </div>
                        @if($dokter->alamat)
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-gray-400 w-5 mt-1"></i>
                            <span class="text-gray-600 ml-3">{{ $dokter->alamat }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Detailed Information -->
        <div class="lg:col-span-8">
            <!-- Combined Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Dokter</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Nama Lengkap</h4>
                            <p class="text-gray-900 font-semibold text-base">{{ $dokter->nama }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Spesialisasi</h4>
                            <p class="text-gray-900 font-semibold text-base">{{ $dokter->spesialisasi }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Nomor STR</h4>
                            <p class="text-gray-900 font-semibold text-base">{{ $dokter->no_str }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Jenis Kelamin</h4>
                            <p class="text-gray-900 font-semibold text-base">{{ $dokter->jenis_kelamin }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Status Akun</h4>
                            <p class="text-gray-900 font-semibold text-base">{{ $dokter->user ? 'Terdaftar' : 'Belum memiliki akun' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Tanggal Lahir</h4>
                            <p class="text-gray-900 font-semibold text-base">{{ $dokter->tanggal_lahir->format('d F Y') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Umur</h4>
                            <p class="text-gray-900 font-semibold text-base">{{ $dokter->umur }} tahun</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase mb-2">Terdaftar Sejak</h4>
                            <p class="text-gray-900 font-semibold text-base">{{ $dokter->created_at->format('d M Y') }}</p>
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
        <div id="modal-backdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Konfirmasi Hapus Data Dokter
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus data dokter <span class="font-semibold">{{ $dokter->nama }}</span>? Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait dokter ini.
                            </p>
                        </div>  
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form action="{{ route('dokter.destroy', $dokter->id) }}" method="POST" class="inline">
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
        const modal = document.getElementById('delete-modal');
        const modalBackdrop = document.getElementById('modal-backdrop');
        const cancelBtn = document.getElementById('cancel-btn');
        const deleteButton = document.getElementById('delete-button');
        
        deleteButton.addEventListener('click', function() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
        
        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        cancelBtn.addEventListener('click', closeModal);
        modalBackdrop.addEventListener('click', closeModal);
    });
</script>
@endpush 
        
