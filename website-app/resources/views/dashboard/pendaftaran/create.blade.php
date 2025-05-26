@extends('layouts.app')

@section('title', 'Tambah Pendaftaran')
@section('page_title', 'Tambah Pendaftaran')
@section('page_subtitle', 'Menambahkan pendaftaran pasien baru')

@section('content')
<div class="px-4 py-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <a href="{{ route('pendaftaran.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-plus text-green-600 mr-3"></i>
                Tambah Pendaftaran Pasien
            </h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Formulir Pendaftaran Pasien
            </h2>
            <p class="text-green-100 text-sm mt-1">Isi data pendaftaran dengan lengkap dan benar</p>
        </div>
        <div class="p-6">
            <form action="{{ route('pendaftaran.store') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Form Sections -->
                <div class="grid grid-cols-1 gap-8">
                    <!-- Informasi Pendaftaran -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-clipboard text-green-600 mr-2"></i>
                            Informasi Pendaftaran
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jenis Pasien -->
                            <div>
                                <label for="jenis_pasien" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jenis Pasien <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user-tag text-gray-400"></i>
                                    </div>
                                    <select name="jenis_pasien" id="jenis_pasien" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('jenis_pasien') border-red-500 @enderror" required>
                                        <option value="baru" {{ old('jenis_pasien') === 'baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="lama" {{ old('jenis_pasien') === 'lama' ? 'selected' : '' }}>Lama</option>
                                    </select>
                                </div>
                                @error('jenis_pasien')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                    <select name="jenis_pembayaran" id="jenis_pembayaran" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('jenis_pembayaran') border-red-500 @enderror" required>
                                        <option value="bpjs" {{ old('jenis_pembayaran') === 'bpjs' ? 'selected' : '' }}>BPJS</option>
                                        <option value="umum" {{ old('jenis_pembayaran') === 'umum' ? 'selected' : '' }}>Umum</option>
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
                                    <select name="cluster_id" id="cluster_id" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('cluster_id') border-red-500 @enderror" required>
                                        <option value="">Pilih Cluster</option>
                                        @foreach ($clusters as $cluster)
                                            <option value="{{ $cluster->id }}" {{ old('cluster_id') == $cluster->id ? 'selected' : '' }}>{{ $cluster->nama }}</option>
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
                                    <input type="date" name="tanggal_daftar" id="tanggal_daftar" value="{{ old('tanggal_daftar', now()->toDateString()) }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('tanggal_daftar') border-red-500 @enderror" 
                                        required>
                                </div>
                                @error('tanggal_daftar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pasien -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-3 pb-2 border-b border-gray-200">
                            <i class="fas fa-user text-green-600 mr-2"></i>
                            Informasi Pasien
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIK -->
                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">
                                    NIK <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('nik') border-red-500 @enderror" 
                                        required maxlength="16" pattern="\d{16}">
                                </div>
                                @error('nik')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No BPJS -->
                            <div id="no_bpjs_container" class="{{ old('jenis_pembayaran') === 'bpjs' ? '' : 'hidden' }}">
                                <label for="no_bpjs" class="block text-sm font-medium text-gray-700 mb-1">
                                    No. BPJS <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                    <input type="text" name="no_bpjs" id="no_bpjs" value="{{ old('no_bpjs') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('no_bpjs') border-red-500 @enderror" 
                                        maxlength="13" pattern="\d{13}">
                                </div>
                                @error('no_bpjs')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('nama') border-red-500 @enderror" 
                                        required>
                                </div>
                                @error('nama')
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
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('jenis_kelamin') border-red-500 @enderror" required>
                                        <option value="laki-laki" {{ old('jenis_kelamin') === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ old('jenis_kelamin') === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('tanggal_lahir') border-red-500 @enderror" 
                                        required>
                                </div>
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tempat Lahir <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('tempat_lahir') border-red-500 @enderror" 
                                        required>
                                </div>
                                @error('tempat_lahir')
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
                                    <textarea name="alamat" id="alamat" rows="3" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('alamat') border-red-500 @enderror" required>{{ old('alamat') }}</textarea>
                                </div>
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
                                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('no_hp') border-red-500 @enderror" 
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
                                    <input type="text" name="no_kk" id="no_kk" value="{{ old('no_kk') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('no_kk') border-red-500 @enderror" 
                                        maxlength="16">
                                </div>
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
                                    <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}" 
                                        class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('pekerjaan') border-red-500 @enderror">
                                </div>
                                @error('pekerjaan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Golongan Darah -->
                            <div>
                                <label for="golongan_darah" class="block text-sm font-medium text-gray-700 mb-1">
                                    Golongan Darah <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tint text-gray-400"></i>
                                    </div>
                                    <select name="golongan_darah" id="golongan_darah" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('golongan_darah') border-red-500 @enderror" required>
                                        <option value="">Pilih Golongan Darah</option>
                                        <option value="A" {{ old('golongan_darah') === 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('golongan_darah') === 'B' ? 'selected' : '' }}>B</option>
                                        <option value="AB" {{ old('golongan_darah') === 'AB' ? 'selected' : '' }}>AB</option>
                                        <option value="O" {{ old('golongan_darah') === 'O' ? 'selected' : '' }}>O</option>
                                    </select>
                                </div>
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
                                    <textarea name="riwayat_alergi" id="riwayat_alergi" rows="3" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('riwayat_alergi') border-red-500 @enderror">{{ old('riwayat_alergi') }}</textarea>
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
                                    <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                        <i class="fas fa-heartbeat text-gray-400"></i>
                                    </div>
                                    <textarea name="riwayat_penyakit" id="riwayat_penyakit" rows="3" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('riwayat_penyakit') border-red-500 @enderror">{{ old('riwayat_penyakit') }}</textarea>
                                </div>
                                @error('riwayat_penyakit')
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
                                    <textarea name="keluhan" id="keluhan" rows="3" class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 @error('keluhan') border-red-500 @enderror" required>{{ old('keluhan') }}</textarea>
                                </div>
                                @error('keluhan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Umum Notification -->
                <div id="umum_notification" class="mt-4 text-sm text-gray-600 {{ old('jenis_pembayaran') === 'umum' ? '' : 'hidden' }}">
                    Silahkan melakukan pembayaran di administrasi kami.
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end items-center space-x-3 mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('pendaftaran.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors focus:ring-4 focus:ring-green-300">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const jenisPasien = document.getElementById('jenis_pasien');
        const jenisPembayaran = document.getElementById('jenis_pembayaran');
        const nikInput = document.getElementById('nik');
        const noBpjsContainer = document.getElementById('no_bpjs_container');
        const noBpjsInput = document.getElementById('no_bpjs');
        const umumNotification = document.getElementById('umum_notification');
        const fields = {
            nama: document.getElementById('nama'),
            jenis_kelamin: document.getElementById('jenis_kelamin'),
            tanggal_lahir: document.getElementById('tanggal_lahir'),
            tempat_lahir: document.getElementById('tempat_lahir'),
            alamat: document.getElementById('alamat'),
            no_hp: document.getElementById('no_hp'),
            no_kk: document.getElementById('no_kk'),
            pekerjaan: document.getElementById('pekerjaan'),
            golongan_darah: document.getElementById('golongan_darah'),
            riwayat_alergi: document.getElementById('riwayat_alergi'),
            riwayat_penyakit: document.getElementById('riwayat_penyakit')
        };

        function togglePembayaranFields() {
            const isBpjs = jenisPembayaran.value === 'bpjs';
            noBpjsContainer.classList.toggle('hidden', !isBpjs);
            umumNotification.classList.toggle('hidden', isBpjs);
            noBpjsInput.required = isBpjs;
            if (!isBpjs) {
                noBpjsInput.value = '';
            }
        }

        async function fetchPasienData(nik) {
            if (!nik || nik.length !== 16) return;
            try {
                const response = await fetch(`/api/pasien/${nik}`);
                if (response.ok) {
                    const pasien = await response.json();
                    fields.nama.value = pasien.nama || '';
                    fields.jenis_kelamin.value = pasien.jenis_kelamin || '';
                    fields.tanggal_lahir.value = pasien.tanggal_lahir || '';
                    fields.tempat_lahir.value = pasien.tempat_lahir || '';
                    fields.alamat.value = pasien.alamat || '';
                    fields.no_hp.value = pasien.no_hp || '';
                    fields.no_kk.value = pasien.no_kk || '';
                    fields.pekerjaan.value = pasien.pekerjaan || '';
                    fields.golongan_darah.value = pasien.golongan_darah || '';
                    fields.riwayat_alergi.value = pasien.riwayat_alergi || '';
                    fields.riwayat_penyakit.value = pasien.riwayat_penyakit || '';
                    noBpjsInput.value = pasien.no_bpjs || '';

                    // Set read-only for immutable fields if set
                    const isLama = jenisPasien.value === 'lama';
                    fields.tanggal_lahir.readOnly = isLama && pasien.tanggal_lahir;
                    fields.tanggal_lahir.classList.toggle('bg-gray-100', isLama && pasien.tanggal_lahir);
                    fields.tanggal_lahir.classList.toggle('cursor-not-allowed', isLama && pasien.tanggal_lahir);
                    fields.golongan_darah.disabled = isLama && pasien.golongan_darah;
                    fields.golongan_darah.classList.toggle('bg-gray-100', isLama && pasien.golongan_darah);
                    fields.golongan_darah.classList.toggle('cursor-not-allowed', isLama && pasien.golongan_darah);
                    noBpjsInput.readOnly = isLama && pasien.no_bpjs;
                    noBpjsInput.classList.toggle('bg-gray-100', isLama && pasien.no_bpjs);
                    noBpjsInput.classList.toggle('cursor-not-allowed', isLama && pasien.no_bpjs);

                    // Set read-only for other fields for lama patients
                    if (isLama) {
                        fields.nama.readOnly = true;
                        fields.nama.classList.add('bg-gray-100', 'cursor-not-allowed');
                        fields.jenis_kelamin.disabled = true;
                        fields.jenis_kelamin.classList.add('bg-gray-100', 'cursor-not-allowed');
                        fields.tempat_lahir.readOnly = true;
                        fields.tempat_lahir.classList.add('bg-gray-100', 'cursor-not-allowed');
                        fields.alamat.readOnly = true;
                        fields.alamat.classList.add('bg-gray-100', 'cursor-not-allowed');
                        fields.no_kk.readOnly = true;
                        fields.no_kk.classList.add('bg-gray-100', 'cursor-not-allowed');
                    } else {
                        // Reset read-only for baru patients
                        fields.nama.readOnly = false;
                        fields.nama.classList.remove('bg-gray-100', 'cursor-not-allowed');
                        fields.jenis_kelamin.disabled = false;
                        fields.jenis_kelamin.classList.remove('bg-gray-100', 'cursor-not-allowed');
                        fields.tempat_lahir.readOnly = false;
                        fields.tempat_lahir.classList.remove('bg-gray-100', 'cursor-not-allowed');
                        fields.alamat.readOnly = false;
                        fields.alamat.classList.remove('bg-gray-100', 'cursor-not-allowed');
                        fields.no_kk.readOnly = false;
                        fields.no_kk.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    }
                } else {
                    // Clear fields if pasien not found
                    Object.values(fields).forEach(field => field.value = '');
                    noBpjsInput.value = '';
                    Object.values(fields).forEach(field => {
                        field.readOnly = false;
                        field.disabled = false;
                        field.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    });
                    noBpjsInput.readOnly = false;
                    noBpjsInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                }
            } catch (error) {
                console.error('Error fetching pasien data:', error);
            }
        }

        jenisPembayaran.addEventListener('change', togglePembayaranFields);
        jenisPasien.addEventListener('change', () => {
            if (nikInput.value) {
                fetchPasienData(nikInput.value);
            }
        });
        nikInput.addEventListener('input', () => {
            if (jenisPasien.value === 'lama' && nikInput.value.length === 16) {
                fetchPasienData(nikInput.value);
            } else {
                Object.values(fields).forEach(field => {
                    field.value = '';
                    field.readOnly = false;
                    field.disabled = false;
                    field.classList.remove('bg-gray-100', 'cursor-not-allowed');
                });
                noBpjsInput.value = '';
                noBpjsInput.readOnly = false;
                noBpjsInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            }
        });
        togglePembayaranFields();
    </script>
@endpush
@endsection