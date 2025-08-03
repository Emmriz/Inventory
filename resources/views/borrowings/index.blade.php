@extends('layouts.app')

@section('title', 'Item Borrowing Management')

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
            <h1 class="text-3xl font-bold text-gray-900">Item Borrowing Management</h1>
            @if(auth()->user()->role === 'admin')
                <button onclick="openModal('borrowItemModal')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-hand-holding mr-2"></i>
                    Borrow Item
                </button>
            @endif
        </div>

        <!-- Borrowings Table -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrower</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Taken</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($borrowings ?? [] as $borrowing)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $borrowing->item->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">SKU: {{ $borrowing->item->sku ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $borrowing->department->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $borrowing->borrower_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $borrowing->date_taken->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $borrowing->return_date->format('Y-m-d') }}
                                @if($borrowing->actual_return_date)
                                    <div class="text-xs text-green-600">
                                        Returned: {{ $borrowing->actual_return_date->format('Y-m-d') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($borrowing->status === 'borrowed')
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">BORROWED</span>
                                @elseif($borrowing->status === 'returned')
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">RETURNED</span>
                                @elseif($borrowing->status === 'overdue')
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">OVERDUE</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                @if(auth()->user()->role === 'admin')
                                    @if($borrowing->status !== 'returned')
                                        <form method="POST" action="{{ route('borrowings.return', $borrowing->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm"
                                                onclick="return confirm('Mark this item as returned?')">
                                                <i class="fas fa-undo text-xs mr-1"></i>Return
                                            </button>
                                        </form>
                                    @endif
                                    <button onclick="openDeleteModal({{ $borrowing->id }}, '{{ $borrowing->item->name ?? 'Item' }} - {{ $borrowing->borrower_name }}')" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-hand-holding text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">No borrowing records found</p>
                                    <p class="text-sm">Start borrowing items to see records here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Borrow Item Modal -->
    <div id="borrowItemModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-lg max-w-lg w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Borrow Item</h3>
                        <button onclick="closeModal('borrowItemModal')" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form action="{{ route('borrowings.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                            <select id="department_id" name="department_id" required onchange="loadItems(this.value)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="item_id" class="block text-sm font-medium text-gray-700 mb-1">Item *</label>
                            <select id="item_id" name="item_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Department First</option>
                            </select>
                        </div>

                        <div>
                            <label for="borrower_name" class="block text-sm font-medium text-gray-700 mb-1">Borrower Name *</label>
                            <input type="text" id="borrower_name" name="borrower_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="date_taken" class="block text-sm font-medium text-gray-700 mb-1">Date Taken *</label>
                                <input type="date" id="date_taken" name="date_taken" required value="{{ date('Y-m-d') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="return_date" class="block text-sm font-medium text-gray-700 mb-1">Return Date *</label>
                                <input type="date" id="return_date" name="return_date" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Any additional notes..."></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" onclick="closeModal('borrowItemModal')"
                                class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Borrow Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteBorrowingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Borrowing Record</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Are you sure you want to delete the borrowing record for "<span id="deleteBorrowingName" class="font-medium"></span>"? 
                    This action cannot be undone.
                </p>
                
                <form id="deleteBorrowingForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeModal('deleteBorrowingModal')" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-300 hover:bg-gray-400 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                            Delete Record
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
            // Reset form if it's the borrow modal
            if (modalId === 'borrowItemModal') {
                document.getElementById('item_id').innerHTML = '<option value="">Select Department First</option>';
                document.getElementById('department_id').value = '';
            }
        }

        function closeMessage(messageId) {
            document.getElementById(messageId).style.display = 'none';
        }

        function openDeleteModal(borrowingId, borrowingName) {
            document.getElementById('deleteBorrowingName').textContent = borrowingName;
            document.getElementById('deleteBorrowingForm').action = `/borrowings/${borrowingId}`;
            openModal('deleteBorrowingModal');
        }

        async function loadItems(departmentId) {
            const itemSelect = document.getElementById('item_id');
            
            if (!departmentId) {
                itemSelect.innerHTML = '<option value="">Select Department First</option>';
                return;
            }

            itemSelect.innerHTML = '<option value="">Loading items...</option>';
            console.log('Loading items for department:', departmentId);

            try {
                // GET requests don't need CSRF token
                const response = await fetch(`/borrowings/items-by-department/${departmentId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const items = await response.json();
                console.log('Items received:', items);

                if (items.length === 0) {
                    itemSelect.innerHTML = '<option value="">No available items in this department</option>';
                } else {
                    itemSelect.innerHTML = '<option value="">Select Item</option>';
                    items.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = `${item.name} (${item.sku})`;
                        itemSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading items:', error);
                itemSelect.innerHTML = '<option value="">Error loading items</option>';
            }
        }

        // Set minimum return date to today
        document.getElementById('return_date').min = new Date().toISOString().split('T')[0];
        
        // Update minimum return date when date_taken changes
        document.getElementById('date_taken').addEventListener('change', function() {
            document.getElementById('return_date').min = this.value;
        });
    </script>


    {{-- <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            // Reset form if it's the borrow modal
            if (modalId === 'borrowItemModal') {
                document.getElementById('item_id').innerHTML = '<option value="">Select Department First</option>';
                document.getElementById('department_id').value = '';
            }
        }

        function closeMessage(messageId) {
            document.getElementById(messageId).style.display = 'none';
        }

        function openDeleteModal(borrowingId, borrowingName) {
            document.getElementById('deleteBorrowingName').textContent = borrowingName;
            document.getElementById('deleteBorrowingForm').action = `/borrowings/${borrowingId}`;
            openModal('deleteBorrowingModal');
        }

        async function loadItems(departmentId) {
            const itemSelect = document.getElementById('item_id');
            
            if (!departmentId) {
                itemSelect.innerHTML = '<option value="">Select Department First</option>';
                return;
            }

            itemSelect.innerHTML = '<option value="">Loading items...</option>';

            try {
                const response = await fetch(`/borrowings/items-by-department/${departmentId}`);
                const items = await response.json();

                if (items.length === 0) {
                    itemSelect.innerHTML = '<option value="">No available items in this department</option>';
                } else {
                    itemSelect.innerHTML = '<option value="">Select Item</option>';
                    items.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = `${item.name} (${item.sku})`;
                        itemSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading items:', error);
                itemSelect.innerHTML = '<option value="">Error loading items</option>';
            }
        }

        // Set minimum return date to today
        document.getElementById('return_date').min = new Date().toISOString().split('T')[0];
        
        // Update minimum return date when date_taken changes
        document.getElementById('date_taken').addEventListener('change', function() {
            document.getElementById('return_date').min = this.value;
        });
    </script> --}}

   
@endsection