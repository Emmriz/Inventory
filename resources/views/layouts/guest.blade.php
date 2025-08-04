
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGCCHQ- INVENTORY</title>
    <script src="https://cdn.tailwindcss.com"></script>
     <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('newlogo.png') }}" type="image/png">
</head>
<body class=" bg-slate-800">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-blue-900">GGCCHQ INVENTORY</h2>
                </div>
                <div class="flex justify-center">
                <img class=" items-center" src="{{ asset('newlogo.png') }}" alt="Description of image" width="120">
                </div>
            
                <p class="mt-2 text-gray-600 text-center">Only authorized users are allowed to login</p>
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter your email">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter your password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-900 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Sign in
                </button>
            </form>

            

            {{-- <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                <h3 class="font-medium text-gray-700 mb-2">Demo Accounts:</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <div><strong>Admin:</strong> admin@inventory.com / password</div>
                    <div><strong>User:</strong> user@inventory.com / password</div>
                </div>
            </div> --}}
        </div>
    </div>
</body>
</html>
