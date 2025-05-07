<!-- Sidebar -->
<aside class="bg-white shadow-lg h-full text-gray-700" x-data="{ activeDropdown: null }">
    <!-- Logo -->
    <div class="px-6 py-5 border-b border-gray-200 bg-white">
        <div class="flex items-center">
            <a href="/dashboard" class="nav-link">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 mr-3">
            <div>
                <h1 class="text-xl font-bold text-gray-800">PUSKESMAS</h1>
                <p class="text-xs text-gray-500">Siborong-borong</p>
            </a>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="px-3 py-4 h-[calc(100%-13rem)] overflow-y-auto">
        <div class="mb-3 px-3">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menu Utama</p>
        </div>
        <ul class="space-y-1">
            <li>
                <a href="/dashboard" class="{{ request()->is('dashboard') ? 'flex items-center px-4 py-2.5 text-gray-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors group' : 'flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group' }}">
                    <i class="fas fa-home w-5 h-5 mr-3 {{ request()->is('dashboard') ? 'text-green-600' : 'text-gray-500 group-hover:text-green-600' }} transition-colors"></i>
                    <span class="{{ request()->is('dashboard') ? 'font-medium' : '' }}">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/pasien" class="{{ request()->is('pasien*') ? 'flex items-center px-4 py-2.5 text-gray-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors group' : 'flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group' }}">
                    <i class="fas fa-users w-5 h-5 mr-3 {{ request()->is('pasien*') ? 'text-green-600' : 'text-gray-500 group-hover:text-green-600' }} transition-colors"></i>
                    <span class="{{ request()->is('pasien*') ? 'font-medium' : '' }}">Pasien</span>
                </a>
            </li>
            <li>
                <a href="/antrian" class="{{ request()->is('antrian*') ? 'flex items-center px-4 py-2.5 text-gray-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors group' : 'flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group' }}">
                    <i class="fas fa-users w-5 h-5 mr-3 {{ request()->is('antrian*') ? 'text-green-600' : 'text-gray-500 group-hover:text-green-600' }} transition-colors"></i>
                    <span class="{{ request()->is('antrian*') ? 'font-medium' : '' }}">Antrian</span>
                </a>
            </li>
            <li>
                <a href="/app-users" class="{{ request()->is('app-users*') ? 'flex items-center px-4 py-2.5 text-gray-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors group' : 'flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group' }}">
                    <i class="fas fa-user-circle w-5 h-5 mr-3 {{ request()->is('app-users*') ? 'text-green-600' : 'text-gray-500 group-hover:text-green-600' }} transition-colors"></i>
                    <span class="{{ request()->is('app-users*') ? 'font-medium' : '' }}">Pengguna Aplikasi</span>
                </a>
            </li>
            <li>
                <a href="/dokter" class="{{ request()->is('dokter*') ? 'flex items-center px-4 py-2.5 text-gray-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors group' : 'flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group' }}">
                    <i class="fas fa-user-md w-5 h-5 mr-3 {{ request()->is('dokter*') ? 'text-green-600' : 'text-gray-500 group-hover:text-green-600' }} transition-colors"></i>
                    <span class="{{ request()->is('dokter*') ? 'font-medium' : '' }}">Dokter</span>
                </a>
            </li>
            <li>
                <a href="/jadwal_dokter" class="{{ request()->is('jadwal_dokter*') ? 'flex items-center px-4 py-2.5 text-gray-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors group' : 'flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group' }}">
                    <i class="fas fa-calendar-alt w-5 h-5 mr-3 {{ request()->is('jadwal_dokter*') ? 'text-green-600' : 'text-gray-500 group-hover:text-green-600' }} transition-colors"></i>
                    <span class="{{ request()->is('jadwal_dokter*') ? 'font-medium' : '' }}">Jadwal_Dokter</span>
                </a>
            </li>
            <li>
                <a href="/medical-records" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group">
                    <i class="fas fa-notes-medical w-5 h-5 mr-3 text-gray-500 group-hover:text-green-600 transition-colors"></i>
                    <span>Rekam Medis</span>
                </a>
            </li>   
            <li>
                <button @click="activeDropdown = activeDropdown === 'services' ? null : 'services'" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group">
                    <div class="flex items-center">
                        <i class="fas fa-hand-holding-medical w-5 h-5 mr-3 text-gray-500 group-hover:text-green-600 transition-colors"></i>
                        <span>Layanan</span>
                    </div>
                    <i class="fas" :class="activeDropdown === 'services' ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                </button>
                <div x-show="activeDropdown === 'services'" x-collapse class="pl-12 pr-3 py-1 mt-1 space-y-1">
                    <a href="/klaster" class="{{ request()->is('klaster*') ? 'flex items-center px-4 py-2.5 text-gray-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors group' : 'flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group' }}">
                        <i class="fas fa-layer-group w-5 h-5 mr-3 {{ request()->is('klaster*') ? 'text-green-600' : 'text-gray-500 group-hover:text-green-600' }} transition-colors"></i>
                        <span class="{{ request()->is('klaster*') ? 'font-medium' : '' }}">Klaster</span>
                    </a>
                    <a href="/berita" class="{{ request()->is('berita*') ? 'flex items-center px-4 py-2.5 text-gray-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors group' : 'flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group' }}">
                        <i class="fas fa-layer-group w-5 h-5 mr-3 {{ request()->is('berita*') ? 'text-green-600' : 'text-gray-500 group-hover:text-green-600' }} transition-colors"></i>
                        <span class="{{ request()->is('berita*') ? 'font-medium' : '' }}">Berita</span>
                    </a>
                    <a href="/kategori_berita" class="{{ request()->is('kategori_berita*') ? 'flex items-center px-4 py-2.5 text-gray-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors group' : 'flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group' }}">
                        <i class="fas fa-layer-group w-5 h-5 mr-3 {{ request()->is('berita*') ? 'text-green-600' : 'text-gray-500 group-hover:text-green-600' }} transition-colors"></i>
                        <span class="{{ request()->is('kategori_berita*') ? 'font-medium' : '' }}">Kategori Berita</span>
                    </a>
                    <a href="/pengumuman" class="{{ request()->is('pengumuman*') ? 'flex items-center px-4 py-2.5 text-gray-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors group' : 'flex items-center px-4 py-2.5 text-gray-600 hover:bg-green-50 hover:text-gray-800 rounded-lg transition-colors group' }}">
                        <i class="fas fa-layer-group w-5 h-5 mr-3 {{ request()->is('pengumuman*') ? 'text-green-600' : 'text-gray-500 group-hover:text-green-600' }} transition-colors"></i>
                        <span class="{{ request()->is('pengumuman*') ? 'font-medium' : '' }}">Pengumuman</span>
                    </a>
                </div>
            </li>
        </ul>
    </nav>
</aside> 