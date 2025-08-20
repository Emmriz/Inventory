@extends('layouts.app')
@section('title', 'Members Report')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold mb-4">Members Report</h1>

    <div class="flex justify-between pb-4">
        <a href="{{ route('reports.index') }}" class="text-blue-900 hover:text-blue-800 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Reports
        </a>
    </div>

    <!-- Department filter & search -->
    <div class="space-y-2 border border-gray-400 pl-4 pr-4 rounded-lg">
        <div class="flex gap-4 mb-4 mt-4">
            <select id="departmentFilter" class="border p-2 rounded">
                <option value="">All Departments</option>
                @foreach($departments ?? [] as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>

            <input type="text" id="searchInput" placeholder="Search members..." class="border p-2 rounded flex-1">

            <a href="{{ route('reports.members.pdf', request()->all()) }}" class="px-4 py-2 bg-red-600 text-white rounded">Download PDF</a>
        </div>
    </div>

    <!-- Members Table -->
    <table class="min-w-full divide-y divide-gray-200 text-center mt-6">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">S/N</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200" id="membersTableBody">
            @forelse($members as $index => $member)
                <tr class="member-row" data-department="{{ $member->department_id }}" data-name="{{ strtolower($member->name) }}" data-email="{{ strtolower($member->email) }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ ($members->currentPage() - 1) * $members->perPage() + $index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->department->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->phone_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->address }}</td>
                </tr>
            @empty
                <tr id="noMembersRow">
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No members found.</td>
                </tr>
            @endforelse
            <!-- Hidden row for filtered “no matches” -->
            <tr id="noMembersFiltered" style="display: none;">
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No matching members found.</td>
            </tr>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $members->links('pagination::tailwind') }}
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const departmentFilter = document.getElementById('departmentFilter');

    function filterMembers() {
        const searchTerm = searchInput.value.toLowerCase();
        const departmentValue = departmentFilter.value;
        const rows = document.querySelectorAll('.member-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const department = row.getAttribute('data-department');

            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesDepartment = !departmentValue || department === departmentValue;

            if (matchesSearch && matchesDepartment) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('noMembersFiltered').style.display = (visibleCount === 0) ? '' : 'none';
    }

    searchInput.addEventListener('input', filterMembers);
    departmentFilter.addEventListener('change', filterMembers);
</script>
@endsection
