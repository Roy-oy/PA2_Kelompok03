@extends('layouts.app')

@section('title', 'Edit Data Dokter')
@section('page_title', 'Edit Data Dokter')
@section('page_subtitle', 'Memperbarui informasi dokter')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        background-color: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        height: 42px;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
        padding-left: 1rem;
    }
    .select2-dropdown {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
    }
    .select2-container--open .select2-dropdown--below {
        border-top: none;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    .select2-results__option {
        padding: 8px 16px;
    }
    .select2-results__option--highlighted[aria-selected] {
        background-color: #edf2f7;
        color: #1a202c;
    }
</style>
@endpush

@section('content')
<div class="px-4 py-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('dokter.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-edit text-green-600 mr-3"></i>
                Edit Data Dokter
            </h1>
            <span class="ml-3 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center">
                NO_STR: {{ $dokter->no_str }}
            </span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('dokter.show', $dokter->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:ring-4 focus:ring-blue-300">
                <i class="fas fa-eye mr-2"></i>
                Lihat Detail
            </a>
            <a href="{{ route('dokter.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200">
                <i class="fas fa-list mr-2"></i>
                Daftar Dokter
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Edit Data Dokter: {{ $dokter->nama }}
            </h2>
            <p class="text-green-100 text-sm mt-1">Perbarui data dokter dengan informasi terbaru</p>
        </div>
        <div class="p-6">
            @if(session('error'))
                <div class="p-4 mb-6 rounded-lg bg-red-50 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg"></i>
                        <span class="text-red-800 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif
            <form action="{{ route('dokter.update', $dokter->id) }}" method="POST" enctype="multipart/form-data" id="edit-doctor-form">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-8">
                    <!-- Informasi Pribadi & Foto -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Foto Profil Section -->
                        <div class="lg:col-span-1 order-2 lg:order-1 bg-gray-50 rounded-xl p-6 flex flex-col items-center justify-center border border-gray-200">
                            <div class="w-32 h-32 rounded-full bg-white border-2 border-green-200 flex items-center justify-center overflow-hidden mb-3 shadow-md relative group">
                                @if($dokter->foto_profil)
                                    <img src="{{ asset('storage/' . $dokter->foto_profil) }}"
                                        alt="Foto Profil {{ $dokter->nama }}"
                                        class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 flex items-center justify-center transition-all rounded-full">
                                        <i class="fas fa-camera text-white opacity-0 group-hover:opacity-100 transition-opacity text-2xl"></i>
                                    </div>
                                @else
                                    <i class="fas fa-user-md text-gray-300 text-5xl group-hover:opacity-50 transition-opacity"></i>
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 flex items-center justify-center transition-all rounded-full">
                                        <i class="fas fa-camera text-white opacity-0 group-hover:opacity-100 transition-opacity text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <h3 class="text-md font-medium text-gray-700 mb-4 text-center">Foto Profil Dokter</h3>
                            <div class="w-full">
                                <label for="foto_profil" class="block text-sm font-medium text-gray-700 mb-2 text-center">
                                    Ganti Foto
                                </label>
                                <input type="file" id="foto_profil" name="foto_profil"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer @error('foto_profil') border-red-500 @enderror"
                                    accept="image/jpeg,image/png,image/jpg,image/gif">
                                <p class="mt-2 text-xs text-gray-500 text-center">Format: JPG, PNG, GIF (maks. 2MB)</p>
                                @error('foto_profil')
                                    <p class="mt-1 text-sm text-red-600 text-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Main Form -->
                        <div class="lg:col-span-2 order-1 lg:order-2">
                            <h3 class="text-lg font-medium text-gray-800 mb-5 pb-2 border-b border-gray-200 flex items-center">
                                <i class="fas fa-user text-green-600 mr-2"></i>
                                Informasi Pribadi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama -->
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" id="nama" name="nama" value="{{ old('nama', $dokter->nama) }}"
                                            class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('nama') border-red-500 @enderror"
                                            placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    @error('nama')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- No STR -->
                                <div>
                                    <label for="no_str" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nomor STR <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-id-badge text-gray-400"></i>
                                        </div>
                                        <input type="text" id="no_str" name="no_str" value="{{ old('no_str', $dokter->no_str) }}"
                                            class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('no_str') border-red-500 @enderror"
                                            placeholder="Masukkan nomor STR" maxlength="12" required>
                                    </div>
                                    @error('no_str')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Spesialisasi -->
                                <div>
                                    <label for="spesialisasi" class="block text-sm font-medium text-gray-700 mb-1">
                                        Spesialisasi <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-stethoscope text-gray-400"></i>
                                        </div>
                                        <select id="spesialisasi" name="spesialisasi"
                                            class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('spesialisasi') border-red-500 @enderror"
                                            required>
                                            <option value="">-- Pilih Spesialisasi --</option>
                                            <option value="umum" {{ old('spesialisasi', $dokter->spesialisasi) == 'umum' ? 'selected' : '' }}>Dokter Umum</option>
                                            <option value="gigi" {{ old('spesialisasi', $dokter->spesialisasi) == 'gigi' ? 'selected' : '' }}>Dokter Gigi</option>
                                        </select>
                                    </div>
                                    @error('spesialisasi')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jenis Kelamin -->
                                <div>
                                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jenis Kelamin <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-venus-mars text-gray-400"></i>
                                        </div>
                                        <select id="jenis_kelamin" name="jenis_kelamin"
                                            class="gender-select pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('jenis_kelamin') border-red-500 @enderror"
                                            required>
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="laki-laki" data-icon="fas fa-mars text-blue-500" {{ old('jenis_kelamin', $dokter->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="perempuan" data-icon="fas fa-venus text-pink-500" {{ old('jenis_kelamin', $dokter->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    @error('jenis_kelamin')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tanggal Lahir -->
                                <div>
                                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tanggal Lahir <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                        </div>
                                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $dokter->tanggal_lahir->format('Y-m-d')) }}"
                                            class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('tanggal_lahir') border-red-500 @enderror"
                                            required max="{{ date('Y-m-d') }}">
                                    </div>
                                    @error('tanggal_lahir')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select id="status" name="status"
                                        class="status-select bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('status') border-red-500 @enderror"
                                        required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="active" data-icon="fas fa-check-circle text-green-500" {{ old('status', $dokter->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" data-icon="fas fa-times-circle text-red-500" {{ old('status', $dokter->status) == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Kontak -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-5 pb-2 border-b border-gray-200 flex items-center">
                            <i class="fas fa-address-card text-green-600 mr-2"></i>
                            Informasi Kontak
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" id="email" name="email" value="{{ old('email', $dokter->email) }}"
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('email') border-red-500 @enderror"
                                        placeholder="Masukkan email" required>
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No HP -->
                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">
                                    No. HP <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $dokter->no_hp) }}"
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('no_hp') border-red-500 @enderror"
                                        placeholder="Masukkan nomor HP" maxlength="15" required>
                                </div>
                                @error('no_hp')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                                    Alamat <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-home text-gray-400"></i>
                                    </div>
                                    <textarea id="alamat" name="alamat" rows="3"
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('alamat') border-red-500 @enderror"
                                        placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $dokter->alamat) }}</textarea>
                                </div>
                                @error('alamat')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('dokter.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors focus:ring-4 focus:ring-green-300">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.status-select, .gender-select').select2({
            templateResult: formatStateResult,
            templateSelection: formatStateSelection,
            minimumResultsForSearch: Infinity,
        });

        function formatStateResult(state) {
            if (!state.id) return state.text;
            var icon = $(state.element).data('icon');
            return $('<span><i class="' + icon + ' mr-2"></i> ' + state.text + '</span>');
        }

        function formatStateSelection(state) {
            if (!state.id) return state.text;
            var icon = $(state.element).data('icon');
            return $('<span><i class="' + icon + ' mr-2"></i> ' + state.text + '</span>');
        }

        // Client-side validation for no_str
        $('#no_str').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 12) {
                this.value = this.value.slice(0, 12);
            }
        });

        // Form submission validation
        $('#edit-doctor-form').on('submit', function(e) {
            const noStr = $('#no_str').val();
            if (noStr.length !== 12) {
                e.preventDefault();
                $('#no_str').addClass('border-red-500');
                $('#no_str').parent().next('.text-red-600').remove();
                $('#no_str').parent().after('<p class="mt-1 text-sm text-red-600">Nomor STR harus tepat 12 digit.</p>');
            }
        });
    });
</script>
@endpush