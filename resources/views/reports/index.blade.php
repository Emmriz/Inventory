@extends('layouts.app')
@section('title', 'Reports')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold mb-24">Reports Management</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-10 border border-red-800 rounded-md p-8">

        <!-- Items Report Card -->
        <a href="{{ route('reports.items') }}" class="block p-6 bg-blue-900 hover:bg-blue-800 text-white rounded-lg shadow-lg transition duration-300">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-box text-4xl mb-4"></i>
                <span class="text-xl font-semibold">Items Report</span>
            </div>
        </a>

        <!-- Departments Report Card -->
        <a href="{{ route('reports.departments') }}" class="block p-6 bg-green-900 hover:bg-green-800 text-white rounded-lg shadow-lg transition duration-300">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-building text-4xl mb-4"></i>
                <span class="text-xl font-semibold">Departments Report</span>
            </div>
        </a>

        <!-- Members Report Card -->
        <a href="{{ route('reports.members') }}" class="block p-6 bg-purple-900 hover:bg-purple-800 text-white rounded-lg shadow-lg transition duration-300">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-users text-4xl mb-4"></i>
                <span class="text-xl font-semibold">Members Report</span>
            </div>
        </a>
    </div>
</div>
@endsection
