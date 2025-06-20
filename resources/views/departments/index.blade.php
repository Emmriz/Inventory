@extends('layouts.app')

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

    <!-- Filters -->
    {{-- @dd($ggccdept) --}}

    <!-- Items Table -->
   
</div>
@endsection