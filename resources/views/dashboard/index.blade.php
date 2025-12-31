<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - MoodFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

</div>

</body>
</html>