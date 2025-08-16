@extends('layouts.app')
@section('title', 'Manage Departments Report')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold mb-4">Manage Departments Report</h1>

    <div class="flex justify-between pb-4">

        <a href="{{ route('reports.index') }}" class="text-blue-900 hover:text-blue-800 mb-2 inline-block">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Reports
        </a>
    </div>
</div>
<div class="space-y-2 border border-gray-400 pl-4 rounded-lg">
    <form action="{{ route('reports.departments') }}" method="GET" class="flex gap-4 mb-4 mt-4">

    <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded">Filter</button>
    <a href="{{ route('reports.departments.pdf', request()->all()) }}" class="px-4 py-2 bg-red-600 text-white rounded">Download PDF</a>
    </form>

    
</div>

<table class="min-w-full divide-y divide-gray-200 text-center">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Department Name</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Manager</th>
        
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($departments ?? [] as $department)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $department->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $department->description ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $department->manager->name ?? 'N/A' }}</td>
                <!-- <td class="px-6 py-4 whitespace-nowrap space-x-2">
                    <button onclick="openEditDepartmentModal({{ $department->id }})" 
                            class="bg-blue-900 hover:bg-blue-800 text-white px-3 py-1 rounded text-sm">
                        Edit
                    </button>
                    <button onclick="openDeleteDepartmentModal({{ $department->id }}, '{{ $department->name }}')" 
                            class="bg-red-800 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                        Delete
                    </button>
                </td> -->
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No departments found.</td>
            </tr>
        @endforelse
    </tbody>

    
</table>
<div class="mt-4">
 {{ $departments->links() }}
</div>


@endsection
