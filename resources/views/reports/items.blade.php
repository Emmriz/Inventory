@extends('layouts.app')
@section('title', 'Manage Items Report')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold mb-4">Manage Items Report</h1>

    <div class="flex justify-between pb-4">

        <a href="{{ route('reports.index') }}" class="text-blue-900 hover:text-blue-800 mb-2 inline-block">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Reports
        </a>
    </div>
</div>
<div class="space-y-2 border border-gray-400 pl-4 rounded-lg">
    <form action="{{ route('reports.items') }}" method="GET" class="flex gap-4 mb-4 mt-4">
    <select name="department_id" class="border p-2 rounded">
        <option value="">All Departments</option>
        @foreach($departments as $department)
            <option value="{{ $department->id }}" @if(request('department_id') == $department->id) selected @endif>{{ $department->name }}</option>
        @endforeach
    </select>

    <input type="date" name="from_date" value="{{ request('from_date') }}" class="border p-2 rounded">
    <input type="date" name="to_date" value="{{ request('to_date') }}" class="border p-2 rounded">

    <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded">Filter</button>
    <a href="{{ route('reports.items.pdf', request()->all()) }}" class="px-4 py-2 bg-red-600 text-white rounded">Download PDF</a>
    </form>

    <!-- <table class="min-w-full border-collapse border items-center border-gray-300">
    <thead>
        <tr class="bg-gray-100">
            <th class="border px-2 py-1">Item Name</th>
            <th class="border px-2 py-1">SKU</th>
            <th class="border px-2 py-1">Department</th>
            <th class="border px-2 py-1">Quantity</th>
            <th class="border px-2 py-1">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr class="items-center">
            <td class="border px-2 py-1">{{ $item->name }}</td>
            <td class="border px-2 py-1">{{ $item->sku }}</td>
            <td class="border px-2 py-1">{{ $item->department->name ?? 'N/A' }}</td>
            <td class="border px-2 py-1">{{ $item->quantity }}</td>
            <td class="border px-2 py-1">{{ strtoupper(str_replace('_', ' ', $item->status)) }}</td>
        </tr>
        @endforeach
    </tbody>
    </table> -->
</div>
<table class="min-w-full divide-y divide-gray-200 text-center mt-6">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200" id="itemsTableBody">
        @forelse($items ?? [] as $item)
            <tr class="item-row" data-status="{{ $item->status }}" data-name="{{ strtolower($item->name) }}" data-sku="{{ strtolower($item->sku) }}">
                <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $item->sku }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $item->department->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($item->status === 'in_use')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">IN USE</span>
                    @elseif($item->status === 'not_in_use')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">NOT-IN USE</span>
                    @else
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">DAMAGED</span>
                    @endif
                </td>
                <!-- <td class="px-6 py-4 whitespace-nowrap space-x-2">
                    <button onclick="openEditModal({{ $item->id }})" 
                            class="bg-blue-900 hover:bg-blue-800 text-white px-3 py-1 rounded text-sm">
                        Edit
                    </button>
                    <button onclick="openDeleteModal({{ $item->id }}, '{{ $item->name }}')" 
                            class="bg-red-800 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                        Delete
                    </button>
                </td> -->
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No items found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection
