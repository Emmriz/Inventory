
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
        <div class="max-w-md w-full space-y-3 p-5 bg-white rounded-lg shadow-md">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-blue-900">GGCCHQ INVENTORY</h2>
                </div>
                <div class="flex justify-center">
                    <img class=" items-center" src="{{ asset('newlogo.png') }}" alt="Description of image" width="120">
                </div>
            
                <p class="mt-1 text-gray-600 text-center">Only authorized users are allowed to login</p>

                {{ $slot }}

            </div>
        </div>
    </div>
</body>
</html>
