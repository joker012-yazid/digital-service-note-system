<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Digital Service Note System') }}</title>

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                :root { color-scheme: light; font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif; }
                * { box-sizing: border-box; }
                body { margin: 0; background: #f6f7f9; color: #172033; }
                a { color: inherit; }
                .fallback-shell { max-width: 1280px; margin: 0 auto; padding: 0 20px; }
                .fallback-panel { background: #fff; border: 1px solid #d8dde7; border-radius: 8px; }
                .fallback-grid { display: grid; gap: 20px; }
                @media (min-width: 1024px) { .fallback-grid { grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr); } }
            </style>
        @endif
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-950 antialiased">
        <header class="border-b border-slate-200 bg-white">
            <div class="fallback-shell mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-md bg-slate-950 text-sm font-bold text-white">
                        LP
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-amber-600">LaptopPro</p>
                        <h1 class="text-xl font-semibold text-slate-950">Digital Service Note System</h1>
                    </div>
                </div>

                <nav class="flex flex-wrap gap-2 text-sm font-medium text-slate-700" aria-label="Main navigation">
                    <a href="{{ url('/') }}" class="rounded-md px-3 py-2 hover:bg-slate-100">Main Form</a>
                    <a href="{{ url('/#search-records') }}" class="rounded-md px-3 py-2 hover:bg-slate-100">Search Records</a>
                    <a href="{{ url('/settings') }}" class="rounded-md px-3 py-2 hover:bg-slate-100">Settings</a>
                </nav>
            </div>
        </header>

        <main class="fallback-shell mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </body>
</html>
