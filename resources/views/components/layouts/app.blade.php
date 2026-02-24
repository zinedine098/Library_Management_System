<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LibLoan Admin') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-50" x-data="{ sidebarOpen: true }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'w-64' : 'w-0 md:w-20'"
            class="bg-slate-900 text-white flex-shrink-0 hidden md:block overflow-hidden transition-all duration-300 ease-in-out"
        >
            <div class="p-6" :class="!sidebarOpen && 'md:p-4'">
                <div class="flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold" x-show="sidebarOpen" x-transition>LibLoan Admin</a>
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold md:hidden" x-show="!sidebarOpen">L</a>
                </div>
            </div>
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : '' }}"
                   :class="!sidebarOpen && 'md:px-4 md:justify-center'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="ml-3" x-show="sidebarOpen" x-transition>Dashboard</span>
                </a>
                <a href="{{ route('books.index') }}"
                   class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition-colors {{ request()->routeIs('books.*') ? 'bg-slate-800 text-white' : '' }}"
                   :class="!sidebarOpen && 'md:px-4 md:justify-center'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="ml-3" x-show="sidebarOpen" x-transition>Books</span>
                </a>
                <a href="{{ route('categories.index') }}"
                   class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition-colors {{ request()->routeIs('categories.*') ? 'bg-slate-800 text-white' : '' }}"
                   :class="!sidebarOpen && 'md:px-4 md:justify-center'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span class="ml-3" x-show="sidebarOpen" x-transition>Categories</span>
                </a>
                <a href="{{ route('members.index') }}"
                   class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition-colors {{ request()->routeIs('members.*') ? 'bg-slate-800 text-white' : '' }}"
                   :class="!sidebarOpen && 'md:px-4 md:justify-center'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="ml-3" x-show="sidebarOpen" x-transition>Members</span>
                </a>
                <a href="{{ route('borrowings.index') }}"
                   class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition-colors {{ request()->routeIs('borrowings.*') ? 'bg-slate-800 text-white' : '' }}"
                   :class="!sidebarOpen && 'md:px-4 md:justify-center'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="ml-3" x-show="sidebarOpen" x-transition>Borrowings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-slate-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <!-- Sidebar Toggle Button (Desktop) -->
                        <button 
                            type="button" 
                            @click="sidebarOpen = !sidebarOpen"
                            class="hidden md:flex items-center justify-center w-10 h-10 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-md transition-colors"
                            title="Toggle Sidebar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="!sidebarOpen && 'transform scale-x-[-1]'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <!-- Mobile menu button -->
                        <button type="button" class="md:hidden text-slate-500 hover:text-slate-700" @click="document.getElementById('mobile-menu').classList.toggle('hidden')">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>

                    <div class="flex-1 px-4 md:hidden">
                        <span class="text-lg font-semibold">LibLoan Admin</span>
                    </div>

                    <div class="flex items-center space-x-4 ml-auto">
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-slate-700 hover:text-slate-900">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open"
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-slate-200 py-1 z-50"
                                 style="display: none;">
                                <a href="{{ route('profile.edit') }}"
                                   class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                    Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();"
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                        Sign out
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden md:hidden border-t border-slate-200">
                    <nav class="px-4 py-3 space-y-2">
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-slate-700 hover:bg-slate-100">Dashboard</a>
                        <a href="{{ route('books.index') }}" class="block px-3 py-2 rounded-md text-slate-700 hover:bg-slate-100">Books</a>
                        <a href="{{ route('categories.index') }}" class="block px-3 py-2 rounded-md text-slate-700 hover:bg-slate-100">Categories</a>
                        <a href="{{ route('members.index') }}" class="block px-3 py-2 rounded-md text-slate-700 hover:bg-slate-100">Members</a>
                        <a href="{{ route('borrowings.index') }}" class="block px-3 py-2 rounded-md text-slate-700 hover:bg-slate-100">Borrowings</a>
                    </nav>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
