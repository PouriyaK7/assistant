<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('node_modules/sweetalert2/dist/sweetalert2.min.css') }}">
        <script src="{{ asset('node_modules/sweetalert2/dist/sweetalert2.min.js') }}"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        <script>
            window.addEventListener('updated',  e => {
                Swal.fire({
                    title: e.detail.title,
                    icon: e.detail.icon,
                    iconColor: e.detail.iconColor,
                    timer: 3000,
                    toast: true,
                    position: 'bottom-right',
                    timerProgressBar: true,
                    showConfirmButton: false,
                })
            });
            window.addEventListener('show-confirm-dialog', e => {
                Swal.fire({
                    title: e.detail.title,
                    text: e.detail.text,
                    icon: e.detail.icon,
                    showCancelButton: true,
                    confirmButtonText: e.detail.confirmButtonText,
                    cancelButtonText: e.detail.cancelButtonText,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit(e.detail.onConfirmed, e.detail.data);
                    }
                });
            });
        </script>
    </body>
</html>
