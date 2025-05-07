<!-- Header -->
<header class="bg-white shadow-sm w-full border-b border-gray-200">
    <div class="flex justify-between items-center px-6 py-4 ">
        <!-- Left side -->
        <div class="flex items-center">
            <button class="text-gray-700 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition-colors lg:hidden" id="toggleSidebar">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <div class="ml-4">
                <h2 class="text-xl font-semibold text-gray-800">@yield('page_title', 'Dashboard')</h2>
                <p class="text-sm text-gray-600">@yield('page_subtitle', 'Selamat Datang di Sistem Informasi Puskesmas')</p>
            </div>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-3">
            <!-- Search -->
            <div class="hidden md:block relative">
                <input type="text" placeholder="Cari..." class="w-48 lg:w-64 py-1.5 px-3 pr-8 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-300 focus:border-gray-300 outline-none text-sm transition-all" />
                <button class="absolute right-2.5 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="p-2 text-gray-700 bg-gray-100 hover:text-gray-900 hover:bg-gray-200 rounded-full transition-colors focus:outline-none relative">
                    <i class="fas fa-bell"></i>
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                </button>
                
                <!-- Dropdown -->
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-50" x-cloak>
                    <div class="px-4 py-2 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-gray-800">Notifikasi</h3>
                            <span class="text-xs font-medium bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full">5 Baru</span>
                        </div>
                    </div>
                    
                    <div class="max-h-72 overflow-y-auto">
                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100">
                            <div class="flex">
                                <div class="shrink-0">
                                    <span class="inline-block p-2 rounded-full bg-gray-100 text-gray-700">
                                        <i class="fas fa-user-plus text-sm"></i>
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">Pasien baru terdaftar</p>
                                    <p class="text-xs text-gray-500">30 menit yang lalu</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex">
                                <div class="shrink-0">
                                    <span class="inline-block p-2 rounded-full bg-gray-100 text-gray-700">
                                        <i class="fas fa-calendar-check text-sm"></i>
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">Pengingat: Jadwal Rapat</p>
                                    <p class="text-xs text-gray-500">Hari ini, 14:00 WIB</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="px-4 py-2 border-t text-center">
                        <a href="/notifications" class="text-sm text-gray-700 hover:underline">Lihat Semua Notifikasi</a>
                    </div>
                </div>
            </div>
            
            <!-- User profile -->
            <div class="relative ml-2" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center text-sm text-gray-700 hover:text-gray-900 focus:outline-none">
                    <img class="h-8 w-8 rounded-full object-cover border-2 border-gray-200" src="https://ui-avatars.com/api/?name=Admin+User&background=15803d&color=ffffff" alt="User avatar">
                    <span class="ml-2 mr-1 hidden md:inline-block font-medium">Admin User</span>
                    <i class="fas fa-chevron-down text-xs hidden md:inline-block"></i>
                </button>
                
                <!-- Dropdown menu -->
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50" x-cloak>
                    <div class="px-4 py-3 border-b bg-gray-50">
                        <p class="text-sm font-medium text-gray-800">Admin User</p>
                        <p class="text-xs text-gray-500 truncate">admin@puskesmas.com</p>
                    </div>
                    <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2 text-gray-500"></i> Profil
                    </a>
                    <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2 text-gray-500"></i> Pengaturan
                    </a>
                    <hr class="my-1">
                    <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50" 
                       onclick="event.preventDefault(); document.getElementById('header-logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                    <form id="header-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header> 