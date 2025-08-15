@extends('layouts.app')

@section('title', 'Settings Management')

@section('content')

<div class="p-6 content-area transition-colors duration-300 
    {{ $theme === 'dark' ? 'bg-gray-900 text-white' : 'bg-white text-black' }} 
    {{ $accent ? 'accent-' . $accent : '' }}">
    
    <h1 class="text-3xl font-bold">Settings Management</h1>

    <!-- Theme Toggle -->
    <div class="mb-8 mt-8 border border-blue-600 rounded-md p-8">
        <div class="flex items-center justify-between">
            <span class="text-lg font-semibold">Dark Mode</span>
            <form id="themeForm" action="{{ route('theme.toggle') }}" method="POST">
                @csrf
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="themeToggle" name="theme" class="sr-only peer"
                        {{ $theme === 'dark' ? 'checked' : '' }}
                        onchange="document.getElementById('themeForm').submit();">
                    <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-blue-600 transition-colors"></div>
                </label>
            </form>
        </div>
    </div>

    <!-- Accent Color Options -->
    <div class="mb-8 mt-12 border border-blue-600 rounded-md p-8">
        <h2 class="text-lg font-semibold mb-4">Accent Color</h2>
        <div class="grid grid-cols-4 gap-4">
            <button type="button" onclick="setAccent('blue')" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Blue</button>
            <button type="button" onclick="setAccent('green')" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Green</button>
            <button type="button" onclick="setAccent('purple')" class="bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded">Purple</button>
            <button type="button" onclick="resetAccent()" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">Reset</button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function setAccent(color) {
        fetch("{{ route('accent.set') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ color: color })
        }).then(() => location.reload());
    }

    function resetAccent() {
        fetch("{{ route('accent.reset') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        }).then(() => location.reload());
    }
</script>

<style>
    /* Accent hover colors */
    .content-area.accent-blue a:hover { color: #3b82f6; }
    .content-area.accent-green a:hover { color: #10b981; }
    .content-area.accent-purple a:hover { color: #8b5cf6; }

    .accent-blue h1, .accent-blue h2, .accent-blue button { color: #3b82f6; }
    .accent-green h1, .accent-green h2, .accent-green button { color: #10b981; }
    .accent-purple h1, .accent-purple h2, .accent-purple button { color: #8b5cf6; }
</style>

@endsection
