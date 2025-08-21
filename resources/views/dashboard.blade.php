@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            Welcome back, {{ auth()->user()->name }}
        </h1>
        <!-- <button class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-md font-medium">
            Generate Report
        </button> -->

        <a href="{{ route('reports.index') }}" 
   class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-md font-medium inline-block">
    Generate Report
</a>
        <a href="{{ route('events.index') }}" 
   class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-md font-medium inline-block">
    Register Event
</a>

    </div>

    <!-- Stats Grid -->
    @if(auth()->user()->role === 'admin')
        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase">Items</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalItems ?? '1,247' }}</p>
                        <p class="text-sm mt-2 text-blue-900">Number of Items</p>
                    </div>
                    <div class="text-blue-800">
                        <i class="fas fa-box text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase">Active Users</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeUsers ?? '34' }}</p>
                        <p class="text-sm mt-2 text-blue-900">Number of Departments Users</p>
                    </div>
                    <div class="text-blue-800">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase">Departments</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $departments ?? '8' }}</p>
                        <p class="text-sm mt-2 text-blue-900">Number of Departments</p>
                    </div>
                    <div class="text-blue-800">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase">Workers Pool</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalMembers }}</p>
                        <p class="text-sm mt-2 text-blue-900">Number of Workers</p>
                    </div>
                    <div class="text-blue-800">
                        <i class="fas fa-arrows-alt text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="grid gap-6 grid-cols-1 md:grid-cols-3">
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <div>
                        <p class="text-sm font-medium text-gray-600 uppercase">Departments</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $departments ?? '8' }}</p>
                        <p class="text-sm mt-2 text-blue-900">Number of Departments</p>
                    </div>
                    </div>
                    <div class="text-blue-500">
                        <i class="fas fa-box text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase">Items</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalItems ?? '1,247' }}</p>
                        <p class="text-sm mt-2 text-blue-900">Number of Items</p>
                    </div>
                    <div class="text-blue-800">
                        <i class="fas fa-box text-2xl"></i>
                    </div>
                </div>
            </div>

            
            <!-- <div class="bg-white p-6 rounded-lg shadow-sm border">
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
            </div> -->
        </div>
    @endif

    <h3 class="text-lg font-semibold text-gray-900 mb-4">UPCOMING CHURCH EVENTS</h3>
    {{-- Calendar --}}
    <div id="calendar" class="bg-white p-4 rounded shadow mb-8"></div>
    </div>
    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-7">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-semibold text-gray-900 mb-4"></h3>
            <div class="space-y-4">
               
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <div>
                            <img class="justify-center" src="{{ asset('dash2.jpg') }}" alt="Description of image" >
                        </div>
                        
                    </div>
                
                        
                   
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-semibold text-gray-900 mb-4"></h3>
            <div class="space-y-4">
                
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded border border-red-200">
                        <div>
                            <img class="justify-center" src="{{ asset('dash1.jpg') }}" alt="Description of image" >
                        </div>
                        
                    </div>
                
                        
                    
            </div>
        </div>
    </div>
    <!-- Calendar -->
     
   
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        events: "{{ route('events.get') }}",

        dateClick: function(info) {
            openModal();
            document.getElementById('start_date').value = info.dateStr;
            document.getElementById('end_date').value = info.dateStr;
        },

        eventClick: function(info) {
            openModal();
            document.getElementById('modalTitle').innerText = "Edit Event";
            document.getElementById('event_id').value = info.event.id;
            document.getElementById('title').value = info.event.title;
            document.getElementById('start_date').value = info.event.startStr.split("T")[0];
            document.getElementById('end_date').value = info.event.endStr ? info.event.endStr.split("T")[0] : '';
        }
    });

    calendar.render();

});

    </script>
@endsection