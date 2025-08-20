@extends('layouts.app')

@section('title', 'Items Management')

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
        <h1 class="text-3xl font-bold text-gray-900">Items Management</h1>
        <button onclick="openModal('addItemModal')" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-md font-medium">
            <i class="fas fa-plus mr-2"></i>
            Add Item
        </button>
    </div>

    <!-- Filters -->
    <div class="flex gap-4 items-center">
        <div class="relative flex-1 max-w-md">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="searchInput" placeholder="Search items..." class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">All Status</option>
            <option value="in_use">In Use</option>
            <option value="not_in_use">Not-In use</option>
            <option value="damaged">Damaged</option>
        </select>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <!-- S/N COLUMN -->
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S/N</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="itemsTableBody">
                @forelse($items as $item)
                    <tr class="item-row" data-status="{{ $item->status }}" data-name="{{ strtolower($item->name) }}" data-sku="{{ strtolower($item->sku) }}">
                        <!-- S/N CELL (continuous across pages) -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $items->firstItem() + $loop->index }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas fa-box mr-2 text-gray-400"></i>
                                <span class="font-medium">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->sku }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->department->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status === 'in_use')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">IN USE</span>
                            @elseif($item->status === 'not_in_use')
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">NOT-IN USE</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">DAMAGED</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="openEditModal({{ $item->id }})" 
                                    class="bg-blue-900 hover:bg-blue-800 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            @if(auth()->user()->role === 'admin')
                                <button onclick="openDeleteModal({{ $item->id }}, '{{ $item->name }}')" 
                                        class="bg-red-800 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tailwind Pagination + summary --}}
    @if ($items->hasPages())
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4">
            <div class="text-sm text-gray-600">
                Showing <span class="font-semibold">{{ $items->firstItem() }}</span>
                to <span class="font-semibold">{{ $items->lastItem() }}</span>
                of <span class="font-semibold">{{ $items->total() }}</span> results
            </div>
            <div>
                {{ $items->onEachSide(1)->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Add Item Modal -->
<div id="addItemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add New Item</h3>
                <button onclick="closeModal('addItemModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('items.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="add_name" class="block text-sm font-medium text-gray-700">Item Name</label>
                    <input type="text" id="add_name" name="name" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="add_sku" class="block text-sm font-medium text-gray-700">SKU</label>
                    <input type="text" id="add_sku" name="sku" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="add_department_id" class="block text-sm font-medium text-gray-700">Department</label>
                    <select id="add_department_id" name="department_id" required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Department</option>
                        @foreach($departments ?? [] as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="add_quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" id="add_quantity" name="quantity" required min="0" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="add_status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="add_status" name="status" required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="in_use">In Use</option>
                        <option value="not_in_use">Not-In use</option>
                        <option value="damaged">Damaged</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeModal('addItemModal')" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-800 hover:bg-red-700 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-900 hover:bg-blue-800 rounded-md">
                        Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editItemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Item</h3>
                <button onclick="closeModal('editItemModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editItemForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Item Name</label>
                    <input type="text" id="edit_name" name="name" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="edit_sku" class="block text-sm font-medium text-gray-700">SKU</label>
                    <input type="text" id="edit_sku" name="sku" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="edit_department_id" class="block text-sm font-medium text-gray-700">Department</label>
                    <select id="edit_department_id" name="department_id" required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Department</option>
                        @foreach($departments ?? [] as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="edit_quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" id="edit_quantity" name="quantity" required min="0" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="edit_status" name="status" required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="in_use">In Use</option>
                        <option value="not_in_use">Not-In use</option>
                        <option value="damaged">Damaged</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeModal('editItemModal')" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-800 hover:bg-red-700 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-900 hover:bg-blue-800 rounded-md">
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteItemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Item</h3>
            <p class="text-sm text-gray-500 mb-4">
                Are you sure you want to delete "<span id="deleteItemName" class="font-medium"></span>"? 
                This action cannot be undone.
            </p>
            
            <form id="deleteItemForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeModal('deleteItemModal')" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-300 hover:bg-gray-400 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                        Delete Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Edit modal function
function openEditModal(itemId) {
    fetch(`/items/${itemId}`)
        .then(response => response.json())
        .then(item => {
            document.getElementById('edit_name').value = item.name;
            document.getElementById('edit_sku').value = item.sku;
            document.getElementById('edit_department_id').value = item.department_id || '';
            document.getElementById('edit_quantity').value = item.quantity;
            document.getElementById('edit_status').value = item.status;
            document.getElementById('editItemForm').action = `/items/${item.id}`;
            openModal('editItemModal');
        })
        .catch(() => alert('Failed to load item information'));
}

// Delete modal function
function openDeleteModal(itemId, itemName) {
    document.getElementById('deleteItemName').textContent = itemName;
    document.getElementById('deleteItemForm').action = `/items/${itemId}`;
    openModal('deleteItemModal');
}

// Close message notifications
function closeMessage(messageId) {
    document.getElementById(messageId).style.display = 'none';
}

// Auto-hide success/error messages after 5 seconds
setTimeout(function() {
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    if (successMessage) successMessage.style.display = 'none';
    if (errorMessage) errorMessage.style.display = 'none';
}, 5000);

// Search and filter functionality (client-side for current page)
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    filterItems(searchTerm, statusFilter);
});

document.getElementById('statusFilter').addEventListener('change', function() {
    const statusFilter = this.value;
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    filterItems(searchTerm, statusFilter);
});

function filterItems(searchTerm, statusFilter) {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        const sku = row.getAttribute('data-sku');
        const status = row.getAttribute('data-status');
        const matchesSearch = name.includes(searchTerm) || sku.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
    });
}

// Close modals when clicking outside
window.onclick = function(event) {
    const modals = ['addItemModal', 'editItemModal', 'deleteItemModal'];
    modals.forEach(function(modalId) {
        const modal = document.getElementById(modalId);
        if (event.target === modal) closeModal(modalId);
    });
}
</script>
@endsection
