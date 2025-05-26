@extends('layouts.app')

@section('title', 'Edit Pendaftaran')
@section('page_title', 'Edit Pendaftaran')
@section('page_subtitle', 'Memperbarui data pendaftaran pasien')

@section('content')
<div class="px-4 py-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('pendaftaran.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-edit text-amber-500 mr-3"></i>
                Edit Pendaftaran Pasien
            </h1>
            <span class="ml-3 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full flex items-center">
                RM: {{ $pendaftaran->pasien->no_rm ?? '-' }}
            </span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Edit Pendaftaran: {{ $pendaftaran->pasien->nama }}
            </h2>
            <p class="text-amber-100 text-sm mt-1">Perbarui data pendaftaran dengan informasi terbaru</p>
        </div>
        <div class="p-6">
            <form action="{{ route('pendaftaran.update', $pendaftaran) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Form Sections -->
                <div class="grid grid-cols-1 gap-8">
                    <!-- Informasi Pendaftaran -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-clipboard text-amber-600 mr-2"></i>
                            Informasi Pendaftaran
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jenis Pasien -->
                            <div>
                                <label for="jenis_pasien" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jenis Pasien
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user-tag text-gray-400"></i>
                                    </div>
                                    <input type="text" id="jenis_pasien" value="{{ ucfirst($pendaftaran->jenis_pasien) }}" 
                                        class="pl-10 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed" 
                                        readonly>
                                    <input type="hidden" name="jenis_pasien" value="{{ $pendaftaran->jenis_pasien }}">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Jenis pasien tidak dapat diubah</p>
                            </div>

                            <!-- Jenis Pembayaran -->
                            <div>
                                <label for="jenis_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jenis Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-money-bill text-gray-400"></i>
                                    </div>
                                    <select name="jenis_pembayaran" id="jenis_pembayaran" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5 @error('jenis_pembayaran') border-red-500 @enderror" required>
                                        <option value="bpjs" {{ old('jenis_pembayaran', $pendaftaran->jenis_pembayaran) === 'bpjs' ? 'selected' : '' }}>BPJS</option>
                                        <option value="umum" {{ old('jenis_pembayaran', $pendaftaran->jenis_pembayaran) === 'umum' ? 'selected' : '' }}>Umum</option>
                                    </select>
                                </div>
                                @error('jenis_pembayaran')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cluster -->
                            <div>
                                <label for="cluster_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Cluster <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-hospital text-gray-400"></i>
                                    </div>
                                    <select name="cluster_id" id="cluster_id" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5 @error('cluster_id') border-red-500 @enderror" required>
                                        <option value="">Pilih Cluster</option>
                                        @foreach ($clusters as $cluster)
                                            <option value="{{ $cluster->id }}" {{ old('cluster_id', $pendaftaran->cluster_id) == $cluster->id ? 'selected' : '' }}>{{ $cluster->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('cluster_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Daftar -->
                            <div>
                                <label for="tanggal_daftar" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Daftar <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                    <input type="date" name="tanggal_daftar" id="tanggal_daftar" value="{{ old('tanggal_daftar', $pendaftaran->tanggal_daftar->format('Y-m-d')) }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5 @error('tanggal_daftar') border-red-500 @enderror" 
                                        required>
                                </div>
                                @error('tanggal_daftar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Keluhan -->
                            <div class="md:col-span-2">
                                <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Keluhan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-comment-medical text-gray-400"></i>
                                    </div>
                                    <textarea name="keluhan" id="keluhan" rows="3" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5 @error('keluhan') border-red-500 @enderror" required>{{ old('keluhan', $pendaftaran->keluhan) }}</textarea>
                                </div>
                                @error('keluhan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pasien -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-user text-amber-600 mr-2"></i>
                            Informasi Pasien
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIK -->
                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">
                                    NIK
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" id="nik" value="{{ $pendaftaran->pasien->nik }}" 
                                        class="pl-10 bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed" 
                                        readonly>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">NIK tidak dapat diubah</p>
                            </div>

                            <!-- No BPJS -->
                            <div id="no_bpjs_container" class="{{ $pendaftaran->jenis_pembayaran === 'bpjs' ? '' : 'hidden' }}">
                                <label for="no_bpjs" class="block text-sm font-medium text-gray-700 mb-1">
                                    No. BPJS <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="no_bpjs" id="no_bpjs" value="{{ old('no_bpjs', $pendaftaran->pasien->no_bpjs) }}" 
                                        class="pl-10 {{ $pendaftaran->pasien->no_bpjs ? 'bg-gray-100 cursor-not-allowed' : 'bg-gray-50' }} border border-gray-300 text-gray-900 text-sm rounded-lg {{ $pendaftaran->pasien->no_bpjs ? '' : 'focus:ring-amber-500 focus:border-amber-500' }} block w-full p-2.5 @error('no_bpjs') border-red-500 @enderror" 
                                        {{ $pendaftaran->pasien->no_bpjs ? 'readonly' : '' }} maxlength="13" pattern="\d{13}">
                                </div>
                                @if ($pendaftaran->pasien->no_bpjs)
                                    <p class="mt-1 text-xs text-gray-500">No. BPJS tidak dapat diubah</p>
                                @endif
                                @error('no_bpjs')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama <span class="text-red-500 {{ $pendaftaran->jenis_pasien === 'lama' ? 'hidden' : '' }}">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" name="nama" id="nama" value="{{ old('nama', $pendaftaran->pasien->nama) }}" 
                                        class="pl-10 {{ $pendaftaran->jenis_pasien === 'lama' ? 'bg-gray-100 cursor-not-allowed' : 'bg-gray-50' }} border border-gray-300 text-gray-900 text-sm rounded-lg {{ $pendaftaran->jenis_pasien === 'lama' ? '' : 'focus:ring-amber-500 focus:border-amber-500' }} block w-full p-2.5 @error('nama') border-red-500 @enderror" 
                                        {{ $pendaftaran->jenis_pasien === 'lama' ? 'readonly' : '' }} {{ $pendaftaran->jenis_pasien === 'baru' ? 'required' : '' }}>
                                </div>
                                @if ($pendaftaran->jenis_pasien === 'lama')
                                    <p class="mt-1 text-xs text-gray-500">Nama tidak dapat diubah untuk pasien lama</p>
                                @endif
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jenis Kelamin <span class="text-red-500 {{ $pendaftaran->jenis_pasien === 'lama' ? 'hidden' : '' }}">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-venus-mars text-gray-400"></i>
                                    </div>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="pl-10 {{ $pendaftaran->jenis_pasien === 'lama' ? 'bg-gray-100 cursor-not-allowed' : 'bg-gray-50' }} border border-gray-300 text-gray-900 text-sm rounded-lg {{ $pendaftaran->jenis_pasien === 'lama' ? '' : 'focus:ring-amber-500 focus:border-amber-500' }} block w-full p-2.5 @error('jenis_kelamin') border-red-500 @enderror" {{ $pendaftaran->jenis_pasien === 'lama' ? 'disabled' : '' }} {{ $pendaftaran->jenis_pasien === 'baru' ? 'required' : '' }}>
                                        <option value="laki-laki" {{ old('jenis_kelamin', $pendaftaran->pasien->jenis_kelamin) === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ old('jenis_kelamin', $pendaftaran->pasien->jenis_kelamin) === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @if ($pendaftaran->jenis_pasien === 'lama')
                                        <input type="hidden" name="jenis_kelamin" value="{{ $pendaftaran->pasien->jenis_kelamin }}">
                                    @endif
                                </div>
                                @if ($pendaftaran->jenis_pasien === 'lama')
                                    <p class="mt-1 text-xs text-gray-500">Jenis kelamin tidak dapat diubah untuk pasien lama</p>
                                @endif
                                @error('jenis_kelamin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Lahir <span class="text-red-500 {{ $pendaftaran->jenis_pasien === 'lama' ? 'hidden' : '' }}">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $pendaftaran->pasien->tanggal_lahir->format('Y-m-d')) }}" 
                                        class="pl-10 {{ $pendaftaran->pasien->tanggal_lahir ? 'bg-gray-100 cursor-not-allowed' : 'bg-gray-50' }} border border-gray-300 text-gray-900 text-sm rounded-lg {{ $pendaftaran->pasien->tanggal_lahir ? '' : 'focus:ring-amber-500 focus:border-amber-500' }} block w-full p-2.5 @error('tanggal_lahir') border-red-500 @enderror" 
                                        {{ $pendaftaran->pasien->tanggal_lahir ? 'readonly' : '' }} {{ $pendaftaran->jenis_pasien === 'baru' && !$pendaftaran->pasien->tanggal_lahir ? 'required' : '' }}>
                                </div>
                                @if ($pendaftaran->pasien->tanggal_lahir)
                                    <p class="mt-1 text-xs text-gray-500">Tanggal lahir tidak dapat diubah</p>
                                @endif
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tempat Lahir <span class="text-red-500 {{ $pendaftaran->jenis_pasien === 'lama' ? 'hidden' : '' }}">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $pendaftaran->pasien->tempat_lahir) }}" 
                                        class="pl-10 {{ $pendaftaran->jenis_pasien === 'lama' ? 'bg-gray-100 cursor-not-allowed' : 'bg-gray-50' }} border border-gray-300 text-gray-900 text-sm rounded-lg {{ $pendaftaran->jenis_pasien === 'lama' ? '' : 'focus:ring-amber-500 focus:border-amber-500' }} block w-full p-2.5 @error('tempat_lahir') border-red-500 @enderror" 
                                        {{ $pendaftaran->jenis_pasien === 'lama' ? 'readonly' : '' }} {{ $pendaftaran->jenis_pasien === 'baru' ? 'required' : '' }}>
                                </div>
                                @if ($pendaftaran->jenis_pasien === 'lama')
                                    <p class="mt-1 text-xs text-gray-500">Tempat lahir tidak dapat diubah untuk pasien lama</p>
                                @endif
                                @error('tempat_lahir')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                                    Alamat <span class="text-red-500 {{ $pendaftaran->jenis_pasien === 'lama' ? 'hidden' : '' }}">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-home text-gray-400"></i>
                                    </div>
                                    <textarea name="alamat" id="alamat" rows="3" class="pl-10 {{ $pendaftaran->jenis_pasien === 'lama' ? 'bg-gray-100 cursor-not-allowed' : 'bg-gray-50' }} border border-gray-300 text-gray-900 text-sm rounded-lg {{ $pendaftaran->jenis_pasien === 'lama' ? '' : 'focus:ring-amber-500 focus:border-amber-500' }} block w-full p-2.5 @error('alamat') border-red-500 @enderror" {{ $pendaftaran->jenis_pasien === 'lama' ? 'readonly' : '' }} {{ $pendaftaran->jenis_pasien === 'baru' ? 'required' : '' }}>{{ old('alamat', $pendaftaran->pasien->alamat) }}</textarea>
                                </div>
                                @if ($pendaftaran->jenis_pasien === 'lama')
                                    <p class="mt-1 text-xs text-gray-500">Alamat tidak dapat diubah untuk pasien lama</p>
                                @endif
                                @error('alamat')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No HP -->
                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">
                                    No. HP
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $pendaftaran->pasien->no_hp) }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5 @error('no_hp') border-red-500 @enderror" 
                                        maxlength="15" pattern="\d{10,15}">
                                </div>
                                @error('no_hp')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No KK -->
                            <div>
                                <label for="no_kk" class="block text-sm font-medium text-gray-700 mb-1">
                                    No. KK
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-address-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="no_kk" id="no_kk" value="{{ old('no_kk', $pendaftaran->pasien->no_kk) }}" 
                                        class="pl-10 {{ $pendaftaran->jenis_pasien === 'lama' ? 'bg-gray-100 cursor-not-allowed' : 'bg-gray-50' }} border border-gray-300 text-gray-900 text-sm rounded-lg {{ $pendaftaran->jenis_pasien === 'lama' ? '' : 'focus:ring-amber-500 focus:border-amber-500' }} block w-full p-2.5 @error('no_kk') border-red-500 @enderror" 
                                        {{ $pendaftaran->jenis_pasien === 'lama' ? 'readonly' : '' }} maxlength="16">
                                </div>
                                @if ($pendaftaran->jenis_pasien === 'lama')
                                    <p class="mt-1 text-xs text-gray-500">No. KK tidak dapat diubah untuk pasien lama</p>
                                @endif
                                @error('no_kk')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pekerjaan -->
                            <div>
                                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pekerjaan
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-briefcase text-gray-400"></i>
                                    </div>
                                    <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan', $pendaftaran->pasien->pekerjaan) }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5 @error('pekerjaan') border-red-500 @enderror">
                                </div>
                                @error('pekerjaan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Golongan Darah -->
                            <div>
                                <label for="golongan_darah" class="block text-sm font-medium text-gray-700 mb-1">
                                    Golongan Darah <span class="text-red-500 {{ $pendaftaran->pasien->golongan_darah ? 'hidden' : '' }}">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tint text-gray-400"></i>
                                    </div>
                                    <select name="golongan_darah" id="golongan_darah" class="pl-10 {{ $pendaftaran->pasien->golongan_darah ? 'bg-gray-100 cursor-not-allowed' : 'bg-gray-50' }} border border-gray-300 text-gray-900 text-sm rounded-lg {{ $pendaftaran->pasien->golongan_darah ? '' : 'focus:ring-amber-500 focus:border-amber-500' }} block w-full p-2.5 @error('golongan_darah') border-red-500 @enderror" {{ $pendaftaran->pasien->golongan_darah ? 'disabled' : '' }} {{ $pendaftaran->pasien->golongan_darah ? '' : 'required' }}>
                                        <option value="">Pilih Golongan Darah</option>
                                        <option value="A" {{ old('golongan_darah', $pendaftaran->pasien->golongan_darah) === 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('golongan_darah', $pendaftaran->pasien->golongan_darah) === 'B' ? 'selected' : '' }}>B</option>
                                        <option value="AB" {{ old('golongan_darah', $pendaftaran->pasien->golongan_darah) === 'AB' ? 'selected' : '' }}>AB</option>
                                        <option value="O" {{ old('golongan_darah', $pendaftaran->pasien->golongan_darah) === 'O' ? 'selected' : '' }}>O</option>
                                    </select>
                                    @if ($pendaftaran->pasien->golongan_darah)
                                        <input type="hidden" name="golongan_darah" value="{{ $pendaftaran->pasien->golongan_darah }}">
                                    @endif
                                </div>
                                @if ($pendaftaran->pasien->golongan_darah)
                                    <p class="mt-1 text-xs text-gray-500">Golongan darah tidak dapat diubah</p>
                                @endif
                                @error('golongan_darah')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Riwayat Alergi -->
                            <div class="md:col-span-2">
                                <label for="riwayat_alergi" class="block text-sm font-medium text-gray-700 mb-1">
                                    Riwayat Alergi
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-allergies text-gray-400"></i>
                                    </div>
                                    <textarea name="riwayat_alergi" id="riwayat_alergi" rows="3" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5 @error('riwayat_alergi') border-red-500 @enderror">{{ old('riwayat_alergi', $pendaftaran->pasien->riwayat_alergi) }}</textarea>
                                </div>
                                @error('riwayat_alergi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Riwayat Penyakit -->
                            <div class="md:col-span-2">
                                <label for="riwayat_penyakit" class="block text-sm font-medium text-gray-700 mb-1">
                                    Riwayat Penyakit
                                </label>
                                <div class="relative">
                                    <div class="absolute top-3 left-3 flex items酷派.
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-heartbeat text-gray-400"></i>
                                    </div>
                                    <textarea name="riwayat_penyakit" id="riwayat_penyakit" rows="3" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5 @error('riwayat_penyakit') border-red-500 @enderror">{{ old('riwayat_penyakit', $pendaftaran->pasien->riwayat_penyakit) }}</textarea>
                                </div>
                                @error('riwayat_penyakit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Umum Notification -->
                <div id="umum_notification" class="mt-4 text-sm text-gray-600 {{ $pendaftaran->jenis_pembayaran === 'umum' ? '' : 'hidden' }}">
                    Silahkan melakukan pembayaran di administrasi kami.
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('pendaftaran.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition-colors focus:ring-4 focus:ring-amber-300">
                        <i class="fas fa-save mr-2"></i>
                        Perbarui Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const jenisPembayaran = document.getElementById('jenis_pembayaran');
        const noBpjsContainer = document.getElementById('no_bpjs_container');
        const umumNotification = document.getElementById('umum_notification');
        const noBpjsInput = document.getElementById('no_bpjs');

        function togglePembayaranFields() {
            const isBpjs = jenisPembayaran.value === 'bpjs';
            noBpjsContainer.classList.toggle('hidden', !isBpjs);
            umumNotification.classList.toggle('hidden', isBpjs);
            noBpjsInput.required = isBpjs && !noBpjsInput.readOnly;
        }

        jenisPembayaran.addEventListener('change', togglePembayaranFields);
        togglePembayaranFields();
    </script>
@endpush
@endsection