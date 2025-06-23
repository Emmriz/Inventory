@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Users Management</h1>
        <button onclick="openModal('addUserModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
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
            <tbody class="bg-white divide-y divide-gray-200">
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
                                        @if($user->phone)
                                            <i class="fas fa-phone text-xs ml-3 mr-1"></i>
                                            {{ $user->phone }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->department->name ?? 'Unassigned' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role === 'admin')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">ADMIN</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">USER</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->status === 'active')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">ACTIVE</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">INACTIVE</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->last_login ? $user->last_login->format('Y-m-d H:i') : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="editUser('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->department_id }}', '{{ $user->phone }}', '{{ $user->status }}')" 
                                    class="border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded text-sm">
                                <i class="fas fa-edit text-xs mr-1"></i>Edit
                            </button>
                            <button onclick="managePermissions('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')" 
                                    class="border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded text-sm">
                                <i class="fas fa-shield-alt text-xs mr-1"></i>Permissions
                            </button>
                        </td>
                    </tr>
                @empty
                    <!-- Sample data when no users exist -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium mr-3">J</div>
                                <div>
                                    <div class="font-medium">John Smith</div>
                                    <div class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-envelope text-xs mr-1"></i>
                                        john@inventory.com
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">IT</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">ADMIN</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">ACTIVE</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button class="border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded text-sm">
                                <i class="fas fa-edit text-xs mr-1"></i>Edit
                            </button>
                            <button class="border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded text-sm">
                                <i class="fas fa-shield-alt text-xs mr-1"></i>Permissions
                            </button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add New User</h3>
                    <button onclick="closeModal('addUserModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" id="name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select id="role" name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Role</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select id="department_id" name="department_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Department</option>
                            @foreach($departments ?? [] as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeModal('addUserModal')" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
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
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
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
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" id="edit_name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" id="edit_email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-1">Password (leave blank to keep current)</label>
                        <input type="password" id="edit_password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" id="edit_password_confirmation" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select id="edit_role" name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="edit_department_id" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select id="edit_department_id" name="department_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Department</option>
                            @foreach($departments ?? [] as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" id="edit_phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="edit_status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeModal('editUserModal')" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
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
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Manage User Permissions</h3>
                    <button onclick="closeModal('permissionsModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600">User: <span id="permissionUserName" class="font-medium"></span></p>
                    <p class="text-sm text-gray-600">Current Role: <span id="permissionUserRole" class="font-medium"></span></p>
                </div>
                
                <form id="permissionsForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">System Permissions</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_view_users" name="permissions[]" value="view_users" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_view_users" class="ml-2 text-sm text-gray-700">View Users</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_create_users" name="permissions[]" value="create_users" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_create_users" class="ml-2 text-sm text-gray-700">Create Users</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_edit_users" name="permissions[]" value="edit_users" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_edit_users" class="ml-2 text-sm text-gray-700">Edit Users</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_delete_users" name="permissions[]" value="delete_users" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_delete_users" class="ml-2 text-sm text-gray-700">Delete Users</label>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Inventory Permissions</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_view_inventory" name="permissions[]" value="view_inventory" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_view_inventory" class="ml-2 text-sm text-gray-700">View Inventory</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_create_items" name="permissions[]" value="create_items" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_create_items" class="ml-2 text-sm text-gray-700">Create Items</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_edit_items" name="permissions[]" value="edit_items" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_edit_items" class="ml-2 text-sm text-gray-700">Edit Items</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_delete_items" name="permissions[]" value="delete_items" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_delete_items" class="ml-2 text-sm text-gray-700">Delete Items</label>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Department Permissions</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_view_departments" name="permissions[]" value="view_departments" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_view_departments" class="ml-2 text-sm text-gray-700">View Departments</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_manage_departments" name="permissions[]" value="manage_departments" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_manage_departments" class="ml-2 text-sm text-gray-700">Manage Departments</label>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Report Permissions</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_view_reports" name="permissions[]" value="view_reports" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_view_reports" class="ml-2 text-sm text-gray-700">View Reports</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="perm_export_reports" name="permissions[]" value="export_reports" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="perm_export_reports" class="ml-2 text-sm text-gray-700">Export Reports</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeModal('permissionsModal')" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Permissions
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

function editUser(userId, name, email, role, departmentId, phone, status) {
    // Populate the edit form
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_department_id').value = departmentId || '';
    document.getElementById('edit_phone').value = phone || '';
    document.getElementById('edit_status').value = status;
    
    // Clear password fields
    document.getElementById('edit_password').value = '';
    document.getElementById('edit_password_confirmation').value = '';
    
    // Set form action
    document.getElementById('editUserForm').action = `/users/${userId}`;
    
    // Open modal
    openModal('editUserModal');
}

function managePermissions(userId, userName, userRole) {
    // Set user info
    document.getElementById('permissionUserName').textContent = userName;
    document.getElementById('permissionUserRole').textContent = userRole.toUpperCase();
    
    // Set form action
    document.getElementById('permissionsForm').action = `/users/${userId}/permissions`;
    
    // Reset all checkboxes
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // If user is admin, check all permissions by default
    if (userRole === 'admin') {
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    } else {
        // For regular users, check basic permissions
        document.getElementById('perm_view_inventory').checked = true;
        document.getElementById('perm_view_departments').checked = true;
    }
    
    // Open modal
    openModal('permissionsModal');
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