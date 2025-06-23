{{-- @extends('layouts.app')

@section('title', 'Items Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Department Management</h1>
        <a href="{{route('departments.create')}}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
            <i class="fas fa-plus mr-2"></i>
            Add Department
        </a>
    </div>
   
</div>
@endsection --}}

@extends('layouts.app')

@section('title', 'Departments Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Departments Management</h1>
        <button onclick="openModal('addDepartmentModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
            <i class="fas fa-plus mr-2"></i>
            Add Department
        </button>
    </div>
    @if (session('success'))
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow z-50">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(() => {
            const alert = document.getElementById('successMessage');
            if (alert) alert.style.display = 'none';
        }, 6000); // 6000ms = 6 seconds
    </script>
@endif

    <!-- Departments Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ggccdept as $department)
            <div class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-building text-blue-500 text-2xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $department->name }}</h3>
                    </div>
                    <button onclick="openEditModal('{{ $department->id }}')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                
                <p class="text-gray-600 mb-4">{{ $department->description }}</p>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Manager:</span>
                        <span class="font-medium">{{ $department->manager->name ?? 'Not assigned' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-users text-sm mr-1"></i>
                            {{ $department->users_count ?? 0 }} users
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-box text-sm mr-1"></i>
                            {{ $department->items_count ?? 0 }} items
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200 flex gap-2">
                    <button onclick="viewDepartment('{{ $department->id }}')" class="flex-1 border border-gray-300 hover:bg-gray-50 px-3 py-2 rounded text-sm">
                        View Details
                    </button>
                    <button onclick="deleteDepartment('{{ $department->id }}')" class="border border-red-300 text-red-600 hover:bg-red-50 px-3 py-2 rounded text-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <!-- Sample data when no departments exist -->
            <div class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-building text-blue-500 text-2xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Information Technology</h3>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <p class="text-gray-600 mb-4">Manages all IT infrastructure and software</p>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Manager:</span>
                        <span class="font-medium">John Smith</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-users text-sm mr-1"></i>
                            12 users
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-box text-sm mr-1"></i>
                            156 items
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 flex gap-2">
                    <button class="flex-1 border border-gray-300 hover:bg-gray-50 px-3 py-2 rounded text-sm">
                        View Details
                    </button>
                    <button class="border border-red-300 text-red-600 hover:bg-red-50 px-3 py-2 rounded text-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Add Department Modal -->
<div id="addDepartmentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Department</h3>
                    <button onclick="closeModal('addDepartmentModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ route('departments.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="manager_id" value="{{ auth()->user()->id }}" required>
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Department Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3" required value="{{ old('description') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- <div>
                        <label for="manager_id" class="block text-sm font-medium text-gray-700 mb-1">Manager</label>
                        <select id="manager_id" name="manager_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Manager</option>
                            @foreach($user as $users)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeModal('addDepartmentModal')" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Add Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function openEditModal(departmentId) {
    // Implementation for editing department
    console.log('Edit department:', departmentId);
}

function viewDepartment(departmentId) {
    // Implementation for viewing department details
    console.log('View department:', departmentId);
}

function deleteDepartment(departmentId) {
    if (confirm('Are you sure you want to delete this department?')) {
        // Implementation for deleting department
        console.log('Delete department:', departmentId);
    }
}
</script>
@endsection