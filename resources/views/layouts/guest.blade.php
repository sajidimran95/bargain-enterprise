<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('bargain.company_name') }} — Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="be-body" style="overflow:auto">
    <div class="flex min-h-screen flex-col items-center justify-center px-4" style="background:#e8e8e8">
        <div class="mb-4 text-center">
            <div class="text-[11px] font-semibold uppercase tracking-wide text-white px-3 py-1" style="background:#1e3a5f">
                {{ config('bargain.company_name') }}
            </div>
            <p class="mt-2 text-[13px] font-semibold text-gray-800">Bargain Enterprise POS/ERP</p>
            <p class="text-[11px] text-gray-500">Wholesale &amp; Tobacco Distribution</p>
        </div>

        <div class="w-full max-w-md border bg-white" style="border-color:#c8c8c8">
            <div class="border-b px-3 py-2 text-[12px] font-semibold" style="border-color:#c8c8c8;background:#eee">
                Sign In
            </div>
            <div class="px-4 py-4">
                {{ $slot }}
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>
