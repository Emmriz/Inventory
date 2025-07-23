@extends('layouts.app')

@section('title', 'Departments Management')

@section('content')
<div class="space-y-6">
    <!--START Success/Error Messages -->
    @if(session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="closeMessage('successMessage')">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif

    @if(session('error'))
        <div id="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="closeMessage('errorMessage')">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif
    <!-- END Success/Error Messages -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Departments Management</h1>
        <button onclick="openModal('addDepartmentModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
            <i class="fas fa-plus mr-2"></i>
            Add Department
        </button>
    </div>

        <!-- Main Content -->
       
            <!-- Departments Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($departments as $department)
                    <div class="bg-white p-6 rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-building text-blue-500 mr-3 text-xl"></i>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $department->name }}</h3>
                            </div>
                            {{-- <button onclick="openEditModal({{ $department->id }})" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-edit"></i>
                            </button> --}}
                            <button 
    onclick="openEditModal(this)" 
    data-id="{{ $department->id }}"
    data-name="{{ $department->name }}"
    data-description="{{ $department->description }}"
    data-manager-id="{{ $department->manager_id }}"
    class="text-gray-400 hover:text-gray-600"
>
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
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $department->members_count }} members
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-box mr-1"></i>
                                    {{ $department->items_count }} items
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200 flex gap-2">
                            <a href="{{ route('departments.show', $department->id) }}" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-center transition-colors">
                                View Details
                            </a>
                            <button onclick="openDeleteModal({{ $department->id }})" class="bg-red-200 hover:bg-red-200 text-red-600 px-3 py-2 rounded-md transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        
</div>

    <!-- Add Department Modal -->
    <div id="addDepartmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Department</h3>
                    <button onclick="closeModal('addDepartmentModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ route('departments.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Department Name</label>
                        <input type="text" id="name" name="name" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" required rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label for="manager_id" class="block text-sm font-medium text-gray-700">Manager</label>
                        <select id="manager_id" name="manager_id" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Manager</option>
                             @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeModal('addDepartmentModal')" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-300 hover:bg-gray-400 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                            Add Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div id="editDepartmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit Department</h3>
                    <button onclick="closeModal('editDepartmentModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="editDepartmentForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700">Department Name</label>
                        <input type="text" id="edit_name" name="name" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="edit_description" name="description" required rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label for="edit_manager_id" class="block text-sm font-medium text-gray-700">Manager</label>
                        <select id="edit_manager_id" name="manager_id" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Manager</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeModal('editDepartmentModal')" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-300 hover:bg-gray-400 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                            Update Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteDepartmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Department</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Are you sure you want to delete this department? This action cannot be undone.
                </p>
                <div class="flex justify-center space-x-3">
                    <button onclick="closeModal('deleteDepartmentModal')" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-300 hover:bg-gray-400 rounded-md">
                        Cancel
                    </button>
                    <form id="deleteDepartmentForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                            Delete
                        </button>
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

        function closeMessage(messageId) {
            document.getElementById(messageId).classList.add('hidden');
        }

        // function openEditModal(departmentId) {
        //     fetch(`/departments/${departmentId}`)
        //         .then(response => response.json())
        //         .then(data => {
        //             document.getElementById('edit_name').value = data.name;
        //             document.getElementById('edit_description').value = data.description;
        //             document.getElementById('edit_manager_id').value = data.manager_id || '';
        //             document.getElementById('editDepartmentForm').action = `/departments/${departmentId}`;
        //             openModal('editDepartmentModal');
        //         });
        // }

        // function openDeleteModal(departmentId) {
        //     document.getElementById('deleteDepartmentForm').action = `/departments/${departmentId}`;
        //     openModal('deleteDepartmentModal');
        // }


        function openEditModal(button) {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const description = button.getAttribute('data-description');
        const managerId = button.getAttribute('data-manager-id');

        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_manager_id').value = managerId || '';
        document.getElementById('editDepartmentForm').action = `/departments/${id}`;

        openModal('editDepartmentModal');
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    </script>

@endsection
