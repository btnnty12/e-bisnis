<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - MoodFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<!-- ================= NAVBAR ================= -->
<nav class="bg-white shadow fixed w-full z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Brand -->
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-bold text-gray-800">
                    MoodFood Admin
                </h1>
            </div>

            <!-- Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('dashboard.index') }}"
                   class="font-medium text-blue-600 border-b-2 border-blue-600 pb-1">
                    Dashboard
                </a>
                <a href="{{ route('dashboard.menus') }}"
                   class="text-gray-600 hover:text-gray-900">
                    Menu
                </a>
                <a href="{{ route('dashboard.categories') }}"
                   class="text-gray-600 hover:text-gray-900">
                    Kategori Mood
                </a>
                <a href="{{ route('dashboard.moods') }}"
                   class="text-gray-600 hover:text-gray-900">
                    Mood
                </a>
                <a href="{{ route('statistics.index') }}"
                   class="text-gray-600 hover:text-gray-900">
                    Statistik
                </a>
            </div>

            <!-- Back to Home -->
            <div>
                <a href="{{ route('home') }}"
                   class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition text-sm font-medium">
                    ⬅ Kembali ke Home
                </a>
            </div>

        </div>
    </div>
</nav>

<!-- ================= CONTENT ================= -->
<div class="pt-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <h2 class="text-3xl font-bold text-gray-900 mb-8">
        Dashboard Overview
    </h2>

    <!-- ================= STAT CARDS ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        <!-- Card -->
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Menu</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ $stats['total_menus'] ?? 0 }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Kategori</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ $stats['total_categories'] ?? 0 }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Mood</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ $stats['total_moods'] ?? 0 }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Tenant</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ $stats['total_tenants'] ?? 0 }}
            </p>
        </div>

    </div>

    <!-- ================= QUICK ACTION ================= -->
    <div class="bg-white rounded-xl shadow p-6 mb-10">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">
            Quick Actions
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

            <a href="{{ route('dashboard.menus') }}"
               class="p-5 rounded-xl border hover:bg-blue-50 transition">
                <p class="text-lg font-semibold text-gray-800">
                    ➕ Tambah Menu
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola menu tenant
                </p>
            </a>

            <a href="{{ route('dashboard.categories') }}"
               class="p-5 rounded-xl border hover:bg-green-50 transition">
                <p class="text-lg font-semibold text-gray-800">
                    🏷️ Tambah Kategori
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Kategori berdasarkan mood
                </p>
            </a>

            <a href="{{ route('statistics.index') }}"
               class="p-5 rounded-xl border hover:bg-purple-50 transition">
                <p class="text-lg font-semibold text-gray-800">
                    📊 Statistik
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Lihat performa sistem
                </p>
            </a>

        </div>
    </div>

    <!-- ================= REVENUE DASHBOARD ================= -->
    <div class="bg-white rounded-xl shadow p-6 mb-10">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">Revenue Dashboard</h3>

        <!-- Filter -->
        <form method="get" action="{{ route('dashboard.index') }}" class="flex flex-wrap gap-4 mb-6">
            <div class="flex flex-col">
                <label class="text-gray-700 font-medium mb-1">Start Date</label>
                <input type="date" name="start" value="{{ $startDate ?? '' }}" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div class="flex flex-col">
                <label class="text-gray-700 font-medium mb-1">End Date</label>
                <input type="date" name="end" value="{{ $endDate ?? '' }}" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">Filter</button>
        </form>

        <!-- Chart Total Revenue -->
        <canvas id="revenueChart" class="mb-6"></canvas>

        <!-- Chart Per Tenant -->
        <canvas id="tenantChart" class="mb-6"></canvas>

        <!-- Revenue Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Total Revenue</th>
                        @foreach($tenants ?? [] as $tenant)
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">{{ $tenant }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tenantData as $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ $row['date'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-medium">
                                Rp {{ number_format(array_sum($row['tenants']),0,',','.') }}
                            </td>
                            @foreach($tenants ?? [] as $tenant)
                                <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                                    Rp {{ number_format($row['tenants'][$tenant] ?? 0,0,',','.') }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + count($tenants ?? []) }}" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ================= CHART.JS SCRIPT ================= -->
<script>
    // Total Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(array_map(fn($r) => $r->date, $data ?? [])),
            datasets: [{
                label: 'Total Revenue (Rp)',
                data: @json(array_map(fn($r) => $r->total, $data ?? [])),
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true }
    });

    // Per Tenant Chart
    const tenantCtx = document.getElementById('tenantChart').getContext('2d');
    new Chart(tenantCtx, {
        type: 'line',
        data: {
            labels: @json(array_map(fn($r) => $r['date'] ?? '', $tenantData ?? [])),
            datasets: [
                @foreach($tenants ?? [] as $index => $tenant)
                {
                    label: "{{ $tenant }}",
                    data: @json(array_map(fn($r) => $r['tenants'][$tenant] ?? 0, $tenantData ?? [])),
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3,
                    borderColor: "hsl({{ ($index * 137.5) % 360 }}, 70%, 50%)"
                },
                @endforeach
            ]
        },
        options: { responsive: true }
    });
</script>

</body>
</html>
