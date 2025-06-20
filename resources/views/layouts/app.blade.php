<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGCCHQ INVENTORY - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
        <!-- Sidebar -->
        <div :class="sidebarCollapsed ? 'w-16' : 'w-64'" class="bg-gray-900 text-white h-screen flex flex-col transition-all duration-300">
            <!-- Header -->
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <h1 x-show="!sidebarCollapsed" class="text-xl font-bold"><img class="justify-center" src="{{ asset('newlogo.png') }}" alt="Description of image" width="130"></h1>
                    <button @click="sidebarCollapsed = !sidebarCollapsed" class="text-white hover:bg-gray-700 p-1 rounded">
                        <i :class="sidebarCollapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left'" class="text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- User Info -->
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div x-show="!sidebarCollapsed">
                        <div class="text-sm font-medium">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="text-xs text-gray-400 capitalize">{{ auth()->user()->role ?? 'user' }}</div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    @if(auth()->user()->role === 'admin')
                        <li><a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                            <i class="fas fa-tachometer-alt"></i>
                            <span x-show="!sidebarCollapsed">Dashboard</span>
                        </a></li>
                        <li><a href="{{ route('items.index') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('items.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                            <i class="fas fa-box"></i>
                            <span x-show="!sidebarCollapsed">Items</span>
                        </a></li>
                        <li><a href="{{ route('departments.index') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('departments.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                            <i class="fas fa-building"></i>
                            <span x-show="!sidebarCollapsed">Departments</span>
                        </a></li>
                        <li><a href="{{ route('users.index') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                            <i class="fas fa-users"></i>
                            <span x-show="!sidebarCollapsed">Users</span>
                        </a></li>
                        <li><a href="{{ route('reports') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('reports') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                            <i class="fas fa-chart-bar"></i>
                            <span x-show="!sidebarCollapsed">Reports</span>
                        </a></li>
                        <li><a href="{{ route('settings') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('settings') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                            <i class="fas fa-cog"></i>
                            <span x-show="!sidebarCollapsed">Settings</span>
                        </a></li>
                    @else
                        <li><a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                            <i class="fas fa-tachometer-alt"></i>
                            <span x-show="!sidebarCollapsed">Dashboard</span>
                        </a></li>
                        <li><a href="{{ route('items.index') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('items.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                            <i class="fas fa-box"></i>
                            <span x-show="!sidebarCollapsed">Items</span>
                        </a></li>
                        <li><a href="{{ route('my-department') }}" class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('my-department') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition-colors">
                            <i class="fas fa-building"></i>
                            <span x-show="!sidebarCollapsed">My Department</span>
                        </a></li>

                        
                    @endif
                </ul>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 p-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                        <span x-show="!sidebarCollapsed">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-2xl font-semibold text-gray-800">
                            @if(auth()->user()->role === 'admin')
                                Admin Dashboard
                            @else
                                User Dashboard
                            @endif
                        </h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" placeholder="Search items..." class="pl-10 pr-4 py-2 w-64 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-bell"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>