<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Puskesmas Dashboard') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            ultralight: '#f8f9fa',
                            verylight: '#f1f3f5',
                            light: '#e9ecef',
                            medium: '#dee2e6',
                            DEFAULT: '#ced4da',
                            dark: '#adb5bd',
                            deeper: '#6c757d'
                        },
                        green: {
                            50: '#f0fdf4',   /* Lightest green */
                            100: '#dcfce7',  /* Very light green */
                            200: '#bbf7d0',  /* Light green */
                            300: '#86efac',  /* Green */
                            400: '#4ade80',  /* Medium green */
                            500: '#22c55e',  /* Primary green */
                            600: '#16a34a',  /* Dark green */
                            700: '#15803d',  /* Darker green */
                            800: '#166534',  /* Very dark green */
                            900: '#14532d',  /* Deepest green */
                            950: '#052e16',  /* Almost black green */
                        },
                        success: '#ced4da',
                        danger: '#dee2e6',
                        warning: '#e9ecef',
                        info: '#f1f3f5'
                    }
                }
            }
        }
    </script>

    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- Summernote JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.10.3/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            /* Base colors */
            --body-bg: #f0fdf4; /* Background hijau sangat terang */
            --card-bg: #ffffff; /* White card background */
            --sidebar-bg: #ffffff; /* White sidebar background */
            --sidebar-hover: var(--green-50); /* Green hover for sidebar */
            --sidebar-active: var(--green-100); /* Lighter green for active items */
            --header-bg: #ffffff; /* White header background */
            --footer-bg: #ffffff; /* White footer background */
            --text-primary: #343a40; /* Dark grey text */
            --text-secondary: #6c757d; /* Medium grey text */
            --text-sidebar: #343a40; /* Dark grey sidebar text */
            --text-sidebar-muted: #adb5bd; /* Light grey muted text */
            --border-color: #dee2e6; /* Light grey border */
            
            /* Green palette */
            --green-50: #f0fdf4;
            --green-100: #dcfce7;
            --green-200: #bbf7d0;
            --green-300: #86efac;
            --green-400: #4ade80;
            --green-500: #22c55e;
            --green-600: #16a34a;
            --green-700: #15803d;
            --green-800: #166534;
            --green-900: #14532d;
            --green-950: #052e16;
        }
        
        body {
            background-color: var(--body-bg); /* Keep green background */
            overflow-x: hidden;
            color: #343a40; /* Dark grey text */
            font-family: 'Inter', 'Segoe UI', Roboto, -apple-system, BlinkMacSystemFont, sans-serif;

        }
        
        /* Fix sidebar to viewport */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 16rem; /* 64px = 16rem */
            overflow: hidden;
            z-index: 50;
            transition: transform 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            background-color: #ffffff; /* White sidebar background */
        }
        
        /* Fix header to viewport */
        .header-fixed {
            position: fixed;
            top: 0;
            right: 0;
            left: 16rem; /* Match sidebar width */
            z-index: 40;
            transition: left 0.3s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            height: 4.5rem;
        }
        
        /* Main content area that scrolls */
        .content-scrollable {
            margin-top: 4.5rem; /* Match header height */
            margin-left: 16rem; /* Match sidebar width */
            padding: 1.5rem;
            padding-bottom: 5rem; /* Add space for footer */
            height: calc(100vh - 4.5rem); /* Viewport height minus header height */
            overflow-y: auto;
            transition: margin-left 0.3s ease;
            background-color: transparent; /* Transparent background to show the teal background */
        }
        
        /* Footer positioning */
        .footer-container {
            position: fixed;
            bottom: 0;
            right: 0;
            left: 16rem; /* Match sidebar width */
            z-index: 30;
            transition: left 0.3s ease;
            background-color: white;
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #adb5bd;
        }
        
        /* Collapsed sidebar state */
        .sidebar-collapsed .sidebar-fixed {
            transform: translateX(-16rem);
        }
        
        .sidebar-collapsed .header-fixed,
        .sidebar-collapsed .footer-container {
            left: 0;
        }
        
        .sidebar-collapsed .content-scrollable {
            margin-left: 0;
        }
        
        /* Mobile responsive styles */
        @media (max-width: 1024px) {
            .sidebar-fixed {
                transform: translateX(-16rem);
            }
            
            .header-fixed,
            .footer-container {
                left: 0;
            }
            
            .content-scrollable {
                margin-left: 0;
            }
            
            .sidebar-expanded .sidebar-fixed {
                transform: translateX(0);
            }
            
            .sidebar-expanded .header-fixed,
            .sidebar-expanded .footer-container {
                left: 16rem;
            }
            
            .sidebar-expanded .content-scrollable {
                margin-left: 16rem;
            }
        }
        
        /* Animation for dropdowns */
        [x-cloak] { 
            display: none !important; 
        }
        
        .fade-in-down {
            animation: fadeInDown 0.2s ease-in-out;
        }
        
        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    <!-- Extra Styles -->
    @stack('styles')
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" :class="{ 'sidebar-collapsed': !sidebarOpen, 'sidebar-expanded': sidebarOpen }">
    <!-- Sidebar (Fixed) -->
    <div class="sidebar-fixed">
        @include('layouts.partials.sidebar')
    </div>
    
    <!-- Backdrop for mobile sidebar -->
    <div x-show="sidebarOpen && window.innerWidth < 1024" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" x-cloak></div>
    
    <!-- Header (Fixed) -->
    <div class="header-fixed">
        @include('layouts.partials.header')
    </div>
    
    <!-- Main Content (Scrollable) -->
    <main class="content-scrollable">
        @yield('content')
    </main>
    
    <!-- Footer (Fixed) -->
    <div class="footer-container">
        @include('layouts.partials.footer')
    </div>

    <!-- Scripts Stack -->
    @stack('scripts')
    
    <!-- Sidebar toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleSidebar');
            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    // Toggle Alpine.js state
                    if (window.Alpine) {
                        window.Alpine.store('sidebarState', window.Alpine.store('sidebarState') || { open: false });
                        window.Alpine.store('sidebarState').open = !window.Alpine.store('sidebarState').open;
                        window.Alpine.data('main').sidebarOpen = window.Alpine.store('sidebarState').open;
                    }
                });
            }
            
            // Handle responsive design
            window.addEventListener('resize', function() {
                const width = window.innerWidth;
                if (width < 1024) {
                    // Mobile view - sidebar closed by default
                    if (window.Alpine) {
                        window.Alpine.data('main') && (window.Alpine.data('main').sidebarOpen = false);
                    }
                    document.body.classList.add('sidebar-collapsed');
                    document.body.classList.remove('sidebar-expanded');
                } else {
                    // Desktop view - sidebar open by default
                    if (window.Alpine) {
                        window.Alpine.data('main') && (window.Alpine.data('main').sidebarOpen = true);
                    }
                    document.body.classList.remove('sidebar-collapsed');
                    document.body.classList.add('sidebar-expanded');
                }
            });
            
            // Add some animations to dropdowns
            const dropdowns = document.querySelectorAll('[x-show]');
            dropdowns.forEach(dropdown => {
                if (!dropdown.hasAttribute('x-transition')) {
                    dropdown.classList.add('fade-in-down');
                }
            });
        });
    </script>
</body>
</html> 