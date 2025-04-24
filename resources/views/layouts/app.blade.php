<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chakkarakootam Sports Meet') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  
<!-- Add this in your layout file's <head> section -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!-- Add this before the closing </body> tag -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Buttons extension -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<!-- Optional: Buttons HTML5 support (for CSV/Excel if needed later) -->
<!-- <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script> -->

<!-- Required for legacy support (HTML5 download in some cases) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<style>
    #dataTable_wrapper, #dataTable2_wrapper {
        color:white;
    }
    .dataTables_wrapper .dataTables_length select{
        padding-right: 2rem !important;
    }
</style>
<style>
    [x-cloak] { display: none !important; }
</style>

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-900 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-gray-800 dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        <!-- Toastr notifications -->
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    toastr.success("{{ session('success') }}");
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    toastr.error("{{ session('error') }}");
                });
            </script>
        @endif
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": true,
                    "responsive": true
                });
                $('#dataTable2').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: false,
                    autoWidth: true,
                    responsive: true,
                    lengthChange: true, // 👈 enables dropdown to choose number of rows
                    lengthMenu: [ [10, 25, 50, 100, 500], [10, 25, 50, 100, 500] ],
                    pageLength: 10, // 👈 default rows per page
                    dom: 'Blfrtip', // 👈 'l' shows length menu (B = buttons, l = length menu)
                    buttons: ['excel', 'print']
                });

});
        </script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
            $('.select2').each(function () {
                const $select = $(this);
                const max = $select.data('max');
                let placeholder = $(this).data('placeholder');
                $select.select2({
                    placeholder: placeholder,
                    tags: true,
                    tokenSeparators: [','],
                    maximumSelectionLength: max
                });
            });
            $('.selectNew').each(function () {
                const $select = $(this);
                const max = $select.data('max');
                let placeholder = $(this).data('placeholder');
                $select.select2({
                    placeholder: placeholder,
                    tokenSeparators: [','],
                    maximumSelectionLength: max
                });
            });
        });
</script>
    </div>
</body>

</html>
