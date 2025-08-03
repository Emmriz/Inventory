@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
    <div class="space-y-6">

        <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="closeMessage('successMessage')">
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
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="closeMessage('errorMessage')">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" viewBox="0 0 20 20">
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">Users Management</h1>
            <button onclick="openModal('addUserModal')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-plus mr-2"></i>
                Add User
            </button>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 overflow-hidden">
                    @forelse($users ?? [] as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 flex items-center">
                                            <i class="fas fa-envelope text-xs mr-1"></i>
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->department->name ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($user->role === 'admin')
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">ADMIN</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">USER</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($user->status === 'active')
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">ACTIVE</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">INACTIVE</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->last_login ? $user->last_login->format('Y-m-d H:i') : 'Never' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button
                                    onclick="editUser('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->department_id }}', '{{ $user->phone }}', '{{ $user->status }}')"
                                    class="border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded text-sm">
                                    <i class="fas fa-edit text-xs mr-1"></i>Edit
                                </button>
                                <button
                                    onclick="managePermissions('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')"
                                    class="border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded text-sm">
                                    <i class="fas fa-shield-alt text-xs mr-1"></i>Permissions
                                </button>
                                @if(auth()->user()->role === 'admin')
                                <button onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No users found. Create your first user to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
        <div class="flex items-center justify-center min-h-screen pl-52">
            <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Add New User</h3>
                        <button onclick="closeModal('addUserModal')" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" id="name" name="name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" id="email" name="email" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                                <input type="password" id="password" name="password" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                                <select id="role" name="role" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Role</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <div>
                                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <select id="department_id" name="department_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Department</option>
                                    @foreach ($departments ?? [] as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select id="status" name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-6">
                            <button type="button" onclick="closeModal('addUserModal')"
                                class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Edit User</h3>
                        <button onclick="closeModal('editUserModal')" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="editUserForm" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" id="edit_name" name="name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" id="edit_email" name="email" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-1">Password (leave blank to keep current)</label>
                                <input type="password" id="edit_password" name="password"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="edit_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                <input type="password" id="edit_password_confirmation" name="password_confirmation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                                <select id="edit_role" name="role" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <div>
                                <label for="edit_department_id" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <select id="edit_department_id" name="department_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Department</option>
                                    @foreach ($departments ?? [] as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" id="edit_phone" name="phone"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select id="edit_status" name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" onclick="closeModal('editUserModal')"
                                class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Permissions Modal -->
    <div id="permissionsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Manage User Permissions</h3>
                        <button onclick="closeModal('permissionsModal')" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">User: <span id="permissionUserName" class="font-medium"></span></p>
                        <p class="text-sm text-gray-600">Current Role: <span id="permissionUserRole" class="font-medium"></span></p>
                    </div>

                    <form id="permissionsForm" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- System Permissions -->
                            <div class="space-y-4">
                                <h4 class="text-md font-semibold text-gray-800 border-b pb-2">System Permissions</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="view_users"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">View Users</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="create_users"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">Create Users</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="edit_users"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">Edit Users</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="delete_users"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">Delete Users</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Inventory Permissions -->
                            <div class="space-y-4">
                                <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Inventory Permissions</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="view_inventory"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">View Inventory</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="create_items"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">Create Items</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="edit_items"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">Edit Items</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="delete_items"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">Delete Items</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Department Permissions -->
                            <div class="space-y-4">
                                <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Department Permissions</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="view_departments"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">View Departments</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="manage_departments"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">Manage Departments</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Report Permissions -->
                            <div class="space-y-4">
                                <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Report Permissions</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="view_reports"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">View Reports</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="permissions[]" value="export_reports"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">Export Reports</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center">
                                <button type="button" onclick="selectAllPermissions()" 
                                    class="text-sm text-blue-600 hover:text-blue-800">
                                    Select All
                                </button>
                                <div class="flex gap-3">
                                    <button type="button" onclick="closeModal('permissionsModal')"
                                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Update Permissions
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Delete User</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Are you sure you want to delete "<span id="deleteUserName" class="font-medium"></span>"? 
                    This action cannot be undone.
                </p>
                
                <form id="deleteUserForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeModal('deleteUserModal')" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-300 hover:bg-gray-400 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                            Delete User
                        </button>
                    </div>
                </form>
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

        function editUser(userId, name, email, role, departmentId, phone, status) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_department_id').value = departmentId || '';
            document.getElementById('edit_phone').value = phone || '';
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_password_confirmation').value = '';
            document.getElementById('editUserForm').action = `/users/${userId}`;
            openModal('editUserModal');
        }

        async function managePermissions(userId, userName, userRole) {
            document.getElementById('permissionUserName').textContent = userName;
            document.getElementById('permissionUserRole').textContent = userRole.toUpperCase();
            document.getElementById('permissionsForm').action = `/users/${userId}/permissions`;

            // Reset all checkboxes
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);

            try {
                // Fetch current user permissions
                const response = await fetch(`/users/${userId}/permissions`);
                const data = await response.json();
                
                // Check the user's current permissions
                if (data.permissions) {
                    data.permissions.forEach(permission => {
                        const checkbox = document.querySelector(`input[value="${permission}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                }
            } catch (error) {
                console.error('Error fetching permissions:', error);
                // Fallback: set default permissions
                if (userRole === 'admin') {
                    checkboxes.forEach(checkbox => checkbox.checked = true);
                } else {
                    ['view_inventory', 'view_departments', 'view_reports'].forEach(perm => {
                        const checkbox = document.querySelector(`input[value="${perm}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                }
            }

            openModal('permissionsModal');
        }

        function selectAllPermissions() {
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
        }

        function openDeleteModal(userId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteUserForm').action = `/users/${userId}`;
            openModal('deleteUserModal');
        }

        function closeMessage(messageId) {
            document.getElementById(messageId).style.display = 'none';
        }

        // Form validation
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            const password = document.getElementById('edit_password').value;
            const passwordConfirm = document.getElementById('edit_password_confirmation').value;

            if (password && password !== passwordConfirm) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
        });
    </script>
@endsection