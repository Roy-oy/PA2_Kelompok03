@extends('layouts.app')

@section('title', 'Detail Pengguna Aplikasi')
@section('page_title', 'Detail Pengguna Aplikasi')
@section('page_subtitle', 'Informasi lengkap pengguna aplikasi mobile')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex justify-between mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <span>{{ $user->name }}</span>
                    @if($user->is_active)
                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Aktif
                        </span>
                    @else
                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Nonaktif
                        </span>
                    @endif
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Terdaftar pada {{ $user->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('app-users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('app-users.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-base font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    <i class="fas fa-user mr-2 text-indigo-500"></i>Informasi Dasar
                </h3>
                
                <div class="space-y-4">
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">ID</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->id }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Nama</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->name }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Email</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->email }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Nomor Telepon</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->phone ?? '-' }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">NIK</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->nik ?? '-' }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Jenis Kelamin</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->gender ?? '-' }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Tanggal Lahir</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->date_of_birth ? $user->date_of_birth->format('d M Y') : '-' }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Terdaftar Pada</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Terakhir Update</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->updated_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Login Terakhir</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum Pernah Login' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Patient Information -->
            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-base font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    <i class="fas fa-hospital-user mr-2 text-indigo-500"></i>Informasi Pasien
                </h3>
                
                @if($user->pasien)
                <div class="space-y-4">
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">No. Rekam Medis</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->pasien->no_rm }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Nama Pasien</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->pasien->nama }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Tempat, Tgl Lahir</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->pasien->tempat_lahir }}, {{ $user->pasien->tanggal_lahir->format('d M Y') }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Jenis Kelamin</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->pasien->jenis_kelamin }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Alamat</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->pasien->alamat }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">No. Telepon</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->pasien->no_telepon ?? '-' }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">No. BPJS</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->pasien->no_bpjs ?? '-' }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Golongan Darah</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->pasien->golongan_darah }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Pekerjaan</div>
                        <div class="w-2/3 text-sm text-gray-900">{{ $user->pasien->pekerjaan ?? '-' }}</div>
                    </div>
                    
                    <div class="pt-2">
                        <a href="{{ route('pasien.show', $user->pasien->id) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-user-injured mr-2"></i>
                            Lihat Detail Pasien
                        </a>
                    </div>
                </div>
                @else
                <div class="py-4 text-center">
                    <div class="text-gray-400 mb-2">
                        <i class="fas fa-user-injured text-4xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm">Pengguna belum terdaftar sebagai pasien.</p>
                    <p class="text-gray-400 text-xs mt-1">Pengguna dapat mendaftarkan diri sebagai pasien dari aplikasi mobile.</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Device Information (if available) -->
        @if($user->appUser && ($user->appUser->device_token || $user->appUser->app_version || $user->appUser->last_app_activity))
        <div class="mt-6 bg-gray-50 p-6 rounded-lg shadow-sm">
            <h3 class="text-base font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                <i class="fas fa-mobile-alt mr-2 text-indigo-500"></i>Informasi Perangkat
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-3 bg-white rounded-md border border-gray-100">
                    <h4 class="text-xs font-medium text-gray-500 uppercase mb-2">Versi Aplikasi</h4>
                    <p class="text-sm font-medium text-gray-900">{{ $user->appUser->app_version ?? 'Tidak tersedia' }}</p>
                </div>
                
                <div class="p-3 bg-white rounded-md border border-gray-100">
                    <h4 class="text-xs font-medium text-gray-500 uppercase mb-2">Aktivitas Terakhir</h4>
                    <p class="text-sm font-medium text-gray-900">{{ $user->appUser->last_app_activity ? $user->appUser->last_app_activity->format('d M Y H:i') : 'Tidak tersedia' }}</p>
                </div>
                
                <div class="p-3 bg-white rounded-md border border-gray-100">
                    <h4 class="text-xs font-medium text-gray-500 uppercase mb-2">Notifikasi</h4>
                    <p class="text-sm font-medium text-gray-900">
                        @if($user->appUser->notifications_enabled)
                            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                        @else
                            <span class="text-red-600"><i class="fas fa-times-circle mr-1"></i> Nonaktif</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 