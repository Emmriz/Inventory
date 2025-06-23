@extends('layouts.app')

@section('title', 'Items Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Items Management</h1>
        <a href="{{route('items.create')}}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
            <i class="fas fa-plus mr-2"></i>
            Add Item
        </a>
    </div>

    <!-- Filters -->
    <div class="flex gap-4 items-center">
        <div class="relative flex-1 max-w-md">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Search items..." class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <select class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="all">All Status</option>
            <option value="active">Active</option>
            <option value="low_stock">Low Stock</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas fa-box mr-2 text-gray-400"></i>
                                <span class="font-medium">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->role }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->status === 'active')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">ACTIVE</span>
                            @elseif($user->status === 'low_stock')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">LOW STOCK</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">INACTIVE</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button class="border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded text-sm">Edit</button>
                            @if(auth()->user()->role === 'admin')
                                <button class="border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded text-sm">Delete</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <!-- Sample data when no items exist -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas fa-box mr-2 text-gray-400"></i>
                                <span class="font-medium">Office Chair</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">OFC-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Office</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">25</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">10</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$150</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">ACTIVE</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button class="border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded text-sm">Edit</button>
                            <button class="border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded text-sm">Delete</button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection