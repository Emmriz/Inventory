@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            Welcome back, {{ auth()->user()->name }}
        </h1>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
            Quick Add Item
        </button>
    </div>

    <!-- Stats Grid -->
    @if(auth()->user()->role === 'admin')
        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Items</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalItems ?? '1,247' }}</p>
                        <p class="text-sm mt-2 text-green-600">↑ 12% from last month</p>
                    </div>
                    <div class="text-blue-500">
                        <i class="fas fa-box text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Users</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeUsers ?? '34' }}</p>
                        <p class="text-sm mt-2 text-green-600">↑ 3% from last month</p>
                    </div>
                    <div class="text-blue-500">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Departments</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $departments ?? '8' }}</p>
                        <p class="text-sm mt-2 text-green-600">↑ 0% from last month</p>
                    </div>
                    <div class="text-blue-500">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Low Stock Items</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $lowStockItems ?? '23' }}</p>
                        <p class="text-sm mt-2 text-red-600">↓ 5% from last month</p>
                    </div>
                    <div class="text-blue-500">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="grid gap-6 grid-cols-1 md:grid-cols-3">
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">My Department Items</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $departmentItems ?? '156' }}</p>
                        <p class="text-sm mt-2 text-green-600">↑ 8% from last month</p>
                    </div>
                    <div class="text-blue-500">
                        <i class="fas fa-box text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Low Stock Alerts</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $lowStockAlerts ?? '5' }}</p>
                        <p class="text-sm mt-2 text-red-600">↓ 2% from last month</p>
                    </div>
                    <div class="text-blue-500">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Recent Transactions</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $recentTransactions ?? '12' }}</p>
                        <p class="text-sm mt-2 text-green-600">↑ 15% from last month</p>
                    </div>
                    <div class="text-blue-500">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Transactions</h3>
            <div class="space-y-4">
               
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <div>
                            <img class="justify-center" src="{{ asset('dash2.jpg') }}" alt="Description of image" >
                        </div>
                        
                    </div>
                
                        
                   
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Low Stock Alerts</h3>
            <div class="space-y-4">
                
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded border border-red-200">
                        <div>
                            <img class="justify-center" src="{{ asset('dash1.jpg') }}" alt="Description of image" >
                        </div>
                        
                    </div>
                
                        
                    
            </div>
        </div>
    </div>
</div>
@endsection