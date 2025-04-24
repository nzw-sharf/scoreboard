<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kalikkootam 2K25 Scoreboard</title>
    <link rel="icon" type="image/png" href="{{ asset('/favicon.png') }}" />
    <!-- Shortcut Icon for Older Browsers -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}" />
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="{{ asset('/favicon.png') }}" />
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://kalikkoottam2k25.bytoons.com">
    <meta property="og:title" content="Kalikkootam 2K25 - Chakkarakkootam UAE">
    <meta property="og:description" content="Live Score Portal">
    <meta property="og:image" content="{{ asset('/logo2.png') }}">

    <!-- Twsitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://kalikkoottam2k25.bytoons.com">
    <meta property="twitter:title" content="Kalikkootam 2K25 - Chakkarakkootam UAE">
    <meta property="twitter:description" content="Live Score Portal">
    <meta property="twitter:image" content="{{ asset('/logo2.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net"> 
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <!-- Add this in your layout file's <head> section -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!-- Add this before the closing </body> tag -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>

<body class="bg-black text-white flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <div class="flex">
        <img src="{{ asset('/logo.png') }}" class=" w-24 md:w-32 max-w-full h-auto object-contain mb-4 md:mb-6 me-8 block mx-auto" alt="Logo">
        <img src="{{ asset('/logo2.png') }}" class="w-24 md:w-32 max-w-full h-auto object-contain mb-4  md:mb-6 block mx-auto" alt="Logo">
    </div>
    <!--<img src="{{ asset('/tagline.png') }}" class=" w-1/2 md:w-48 h-auto object-contain mb-4  md:mb-6 block mx-auto" alt="Logo">-->
    <!--<h1 class="text-2xl text-center font-semibold mb-4 md:mb-16">Kalikkootam 2K25 Scoreboard</h1>-->
    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="w-full">
            @yield('content')
        </main>
    </div>
    <div class="flex flex-col item-center justify-center mt-8">
        <a href="{{url('/')}}" class=" text-white text-sm py-2 mb-4 px-4 underline transition-all">
            back to Home
        </a>
        <a class="text-center text-sm text-yellow-400 hover:text-yellow-500">Powered by Kalikkootam</a>
    </div>
        @yield('scriptAdd')
</body>

</html>
