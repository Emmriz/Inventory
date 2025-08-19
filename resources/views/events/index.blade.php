@extends('layouts.app')

@section('title', 'Events Management')

@section('content')
<div class="space-y-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div id="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Events Management</h1>
        <button onclick="openModal()" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-md font-medium">
            <i class="fas fa-plus mr-2"></i>
            Add Event
        </button>
    </div>

    <p class="text-gray-600 mb-6">Manage your church events efficiently.</p>

    {{-- Calendar --}}
    <div id="calendar" class="bg-white p-4 rounded shadow mb-8"></div>

    {{-- Events Table --}}
    <h3 class="text-xl font-semibold mb-3">All Events</h3>
    <table class="min-w-full bg-white shadow rounded">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="px-4 py-2">Event Name</th>
                <th class="px-4 py-2">Start Date</th>
                <th class="px-4 py-2">End Date</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $event->title }}</td>
                    <td class="px-4 py-2">{{ $event->start_date }}</td>
                    <td class="px-4 py-2">{{ $event->end_date ?? '-' }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <button onclick="confirmDelete({{ $event->id }})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">No events found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal for Adding/Editing Events --}}
<div id="eventModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 id="modalTitle" class="text-lg font-bold mb-4">Add Event</h2>

        <form id="eventForm">
            @csrf
            <input type="hidden" id="event_id">

            <div class="mb-3">
                <label class="block font-medium">Event Title</label>
                <input type="text" id="title" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-3">
                <label class="block font-medium">Start Date</label>
                <input type="date" id="start_date" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-3">
                <label class="block font-medium">End Date</label>
                <input type="date" id="end_date" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-lg shadow-lg w-96">
    <div class="p-4 border-b">
      <h2 class="text-lg font-semibold text-gray-800">Delete Event</h2>
    </div>
    <div class="p-4 text-gray-700">
      Are you sure you want to delete this event? This action cannot be undone.
    </div>
    <div class="flex justify-end space-x-2 p-4 border-t">
      <button id="cancelDeleteBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800">
        Cancel
      </button>
      <button id="confirmDeleteBtn" class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white">
        Delete
      </button>
    </div>
  </div>
</div>

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

    // function refreshTable(events) {
    //     const tbody = document.querySelector("table tbody");
    //     tbody.innerHTML = '';
    //     if(events.length === 0){
    //         tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">No events found.</td></tr>`;
    //         return;
    //     }
    //     events.forEach(event => {
    //         tbody.innerHTML += `
    //             <tr class="border-t">
    //                 <td class="px-4 py-2">${event.title}</td>
    //                 <td class="px-4 py-2">${event.start}</td>
    //                 <td class="px-4 py-2">${event.end || '-'}</td>
    //                 <td class="px-4 py-2 flex space-x-2">
    //                     <button onclick="confirmDelete(${event.id})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
    //                         Delete
    //                     </button>
    //                 </td>
    //             </tr>
    //         `;
    //     });
    // }

    function refreshTableFromServer() {
    fetch("/events/list")
        .then(res => res.json())
        .then(events => {
            const tbody = document.querySelector("table tbody");
            tbody.innerHTML = '';
            if(events.length === 0){
                tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">No events found.</td></tr>`;
                return;
            }
            events.forEach(event => {
                tbody.innerHTML += `
                    <tr class="border-t">
                        <td class="px-4 py-2">${event.title}</td>
                        <td class="px-4 py-2">${event.start_date}</td>
                        <td class="px-4 py-2">${event.end_date || '-'}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <button onclick="confirmDelete(${event.id})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                `;
            });
        });
}


    function showSuccess(message) {
        const msgEl = document.createElement('div');
        msgEl.className = 'fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow';
        msgEl.innerText = message;
        document.body.appendChild(msgEl);
        setTimeout(() => msgEl.remove(), 3000);
    }

    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let id = document.getElementById('event_id').value;
        let url = id ? `/events/${id}/update-ajax` : `/events/store-ajax`;
        let method = id ? 'PUT' : 'POST';

        let payload = {
            title: document.getElementById('title').value,
            start: document.getElementById('start_date').value,
            end: document.getElementById('end_date').value,
        };

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            closeModal();
            showSuccess('Event saved successfully!');
            calendar.refetchEvents(); // refresh calendar
            fetch("/events/list")      // refresh table
                .then(res => res.json())
                .then(events => refreshTable(events));
        })
        .catch(err => console.error(err));
    });

    window.confirmDelete = function(id) {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');

        document.getElementById('confirmDeleteBtn').onclick = function() {
            fetch(`/events/${id}/delete-ajax`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(res => res.json())
            .then(data => {
                modal.classList.add('hidden');
                showSuccess('Event deleted successfully!');
                calendar.refetchEvents();
                fetch("/events/list")
                    .then(res => res.json())
                    .then(events => refreshTable(events));
            })
            .catch(err => console.error(err));
        }

        document.getElementById('cancelDeleteBtn').onclick = function() {
            modal.classList.add('hidden');
        }
    }

});

function openModal() {
    document.getElementById('eventModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('eventModal').classList.add('hidden');
    document.getElementById('eventForm').reset();
    document.getElementById('modalTitle').innerText = "Add Event";
    document.getElementById('event_id').value = '';
}
</script>
@endsection
