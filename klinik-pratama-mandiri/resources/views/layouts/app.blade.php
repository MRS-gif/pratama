<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Beranda') - {{ config('klinik.nama') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800">
@php
    $menu = match (auth()->user()->role ?? null) {
        'pasien'   => [['pasien.index', 'Antrean Saya'], ['pasien.booking', 'Booking Antrean']],
        'admin'    => [['admin.antrean', 'Antrean Hari Ini'], ['admin.walkin', 'Daftar di Loket']],
        'dokter'   => [['dokter.antrean', 'Antrean Saya'], ['dokter.riwayat', 'Rekam Medis']],
        'apoteker' => [['apotek.resep', 'Resep Masuk'], ['apotek.obat', 'Stok Obat']],
        default    => [],
    };
@endphp
@auth
<nav class="bg-teal-700 text-white shadow">
    <div class="max-w-6xl mx-auto px-4 py-3 flex flex-wrap items-center gap-4">
        <span class="font-bold text-lg">🏥 {{ config('klinik.nama') }}</span>
        <div class="flex gap-1 flex-1">
            @foreach ($menu as [$rute, $label])
                <a href="{{ route($rute) }}" class="px-3 py-1 rounded hover:bg-teal-600 {{ request()->routeIs($rute) ? 'bg-teal-900' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
        <span class="text-sm opacity-90">{{ auth()->user()->name }} <em class="not-italic bg-teal-900 rounded px-2 py-0.5 text-xs uppercase">{{ auth()->user()->role }}</em></span>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="text-sm bg-white/20 hover:bg-white/30 rounded px-3 py-1">Keluar</button>
        </form>
    </div>
</nav>
@endauth

<main class="max-w-6xl mx-auto px-4 py-6">
    @if (session('ok'))
        <div class="mb-4 rounded bg-green-100 border border-green-300 text-green-800 px-4 py-3">✅ {{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-3">
            <ul class="list-disc ml-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif
    @yield('isi')
</main>
</body>
</html>
