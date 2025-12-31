@php
  use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>MoodFood - Integrated</title>
  <style>
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    @keyframes pop {
      0% { transform: scale(1); }
      50% { transform: scale(1.6); }
      100% { transform: scale(1.35); }
    }
    .animate-fadeIn {
      animation: fadeIn 0.5s ease-out;
    }
    .animate-slideUp {
      animation: slideUp 0.6s ease-out;
    }
    .mood-card {
      transition: all 0.3s ease;
      cursor: pointer;
    }
    .mood-card:hover {
      transform: translateY(-8px) scale(1.05);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .page-section {
      display: none;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 1rem;
    }
    .page-section.active {
      display: block;
      animation: fadeIn 0.3s ease-out;
    }
    .bottom-nav-item {
      transition: all 0.3s ease;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
    }
    .bottom-nav-item:hover {
      transform: translateY(-5px);
    }
    .bottom-nav-item.active {
      color: #3b82f6;
    }
    .nav-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 9999px;
      transition: all 0.3s ease;
    }
    .bottom-nav-item.active .nav-icon {
      background: #e5e7eb;
      transform: translateY(-2px);
    }
    .dot {
      width: 10px;
      height: 10px;
      border-radius: 9999px;
      background: #d1d5db;
      transition: all 0.35s ease;
    }
    .dot.active {
      background: #111;
      transform: scale(1.35);
      opacity: 1;
      animation: pop 0.35s ease;
    }
    .stat-card {
      transition: all 0.3s ease;
    }
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .card {
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="bg-gradient-to-br from-white via-gray-50 to-gray-100 min-h-screen">

  <!-- Top Navigation -->
  <div class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
      <h1 class="text-xl font-bold text-gray-800">MoodFood</h1>
      <div class="flex items-center space-x-4">
                @auth
          <div class="text-right">
            <p class="text-xs text-gray-600">Selamat datang,</p>
            <p class="text-sm font-semibold text-gray-800">
                {{ auth()->user()->name }}
            </p>
            <p class="text-xs text-gray-500 capitalize">
                {{ auth()->user()->role }}
            </p>
          </div>

          <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit"
              class="text-gray-700 hover:text-red-600 text-sm font-medium px-3 py-1 rounded hover:bg-gray-100">
              Logout
            </button>
          </form>
        @endauth
        <button onclick="window.location.href='{{ route('landing') }}'" 
          class="text-gray-700 hover:text-gray-900 text-xl font-bold p-2 rounded-full hover:bg-gray-200">
          ☜
        </button>
      </div>
    </div>
  </div>

  <!-- Page 1: Home (Mood Selection) -->
  <div id="page-1" class="page-section active" style="padding-top: 80px;">
    <div class="flex flex-col items-center min-h-screen pb-20">
  <!-- Welcome Banner -->
      <div class="mt-8 flex items-center bg-gradient-to-r from-lime-300 to-lime-400 px-4 sm:px-6 md:px-8 py-4 sm:py-5 rounded-xl shadow-lg space-x-3 sm:space-x-4 mx-4 sm:mx-0 w-full max-w-md animate-slideUp">
    <div class="flex-1">
      <p class="text-xs sm:text-sm font-semibold text-gray-700">Selamat datang di</p>
      <h1 class="font-extrabold text-base sm:text-lg md:text-xl text-gray-800">MOOD FOOD</h1>
      <p class="text-xs sm:text-sm text-gray-600">Mall Citra Land, Jakarta Barat</p>
    </div>
    <div class="text-2xl sm:text-3xl md:text-4xl animate-bounce" style="animation-duration: 2s;">👤</div>
  </div>

  <!-- Question -->
  <div class="mt-6 sm:mt-8 text-center px-4 animate-fadeIn" style="animation-delay: 0.2s;">
    <p class="font-semibold text-gray-700 text-sm sm:text-base md:text-lg">Apa mood kamu hari ini?</p>
    <p class="text-gray-500 text-xs sm:text-sm mt-1">Pilih mood dan kami akan merekomendasikan makanan terbaik!</p>
  </div>

  <!-- Mood Options -->
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-3 sm:gap-4 mt-6 sm:mt-8 px-4 w-full max-w-2xl mx-auto">
    @php
        $emojiMap = [
            'senang' => '😊',
            'sedih' => '😔',
            'stress' => '🤯',
            'lelah' => '😴',
            'biasa-aja' => '😐',
            'excited' => '🤩',
        ];
        $defaultEmojis = ['🙂','😎','😇','🤔','🤗','😋','🤓','😺','😸'];
    @endphp

    @foreach($moods as $mood)
        @php
            $key = strtolower(str_replace(' ', '-', $mood->mood_name));
            $emoji = $emojiMap[$key] ?? $defaultEmojis[crc32($key) % count($defaultEmojis)];
        @endphp

        <a href="{{ route('mood.show', $key, false) }}" 
           class="mood-card flex flex-col items-center bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl px-4 sm:px-6 py-4 sm:py-5 shadow-md transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <p class="text-2xl sm:text-3xl md:text-4xl mb-2">{{ $emoji }}</p>
            <span class="font-semibold text-xs sm:text-sm md:text-base">{{ $mood->mood_name }}</span>
        </a>
    @endforeach
</div>

  <!-- Statistik Card -->
      <div class="stat-card bg-gradient-to-br from-gray-100 to-gray-200 px-6 sm:px-8 py-4 sm:py-5 rounded-xl shadow-md mt-6 sm:mt-8 text-center mx-4 sm:mx-0 w-full max-w-md animate-fadeIn" style="animation-delay: 0.4s;">
    <p class="font-semibold text-sm sm:text-base mb-3 sm:mb-4 text-gray-700">📊 Statistik Hari Ini</p>
    <div class="flex justify-center space-x-6 sm:space-x-8 text-xs sm:text-sm font-semibold">
      <span class="text-red-500">
        <span class="block text-lg sm:text-xl md:text-2xl font-bold">{{ $stats['total_users'] ?? 0 }}</span>
        <span class="text-xs sm:text-sm">Pengguna</span>
      </span>
    <span class="text-blue-600">
      <span class="block text-lg sm:text-xl md:text-2xl font-bold">{{ $stats['total_tenants'] ?? 0 }}</span>
      <span class="text-xs sm:text-sm">Tenant</span>
    </span>
      <span class="text-green-600">
        <span class="text-green-600">
  <span class="block text-lg sm:text-xl md:text-2xl font-bold">
    {{ $stats['avg_rating'] }}
  </span>
  <span class="text-xs sm:text-sm">Rating</span>
</span>
        </div>
      </div>
    </div>
  </div>

 @auth
  @if(auth()->user()->role === 'customer')

  <!--- Form Rating (CUSTOMER ONLY) --->
  <div class="flex justify-center items-center mt-6">
    <div class="bg-white px-6 py-4 rounded-xl shadow-md text-center w-full max-w-sm">
      <p class="font-semibold text-gray-700 mb-3">⭐ Beri Rating Aplikasi</p>

      <form id="ratingForm" class="flex justify-center items-center gap-3">
        <select name="rating" class="border rounded-lg px-3 py-2 text-sm">
          <option value="5">⭐⭐⭐⭐⭐ (5)</option>
          <option value="4">⭐⭐⭐⭐ (4)</option>
          <option value="3">⭐⭐⭐ (3)</option>
          <option value="2">⭐⭐ (2)</option>
          <option value="1">⭐ (1)</option>
        </select>

        <button
          type="submit"
          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
          Kirim
        </button>
      </form>

      <p class="text-xs text-gray-500 mt-2">
        Rating kamu akan mempengaruhi rating sistem
      </p>
    </div>
  </div>

  @endif
@endauth

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('ratingForm');

  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const rating = form.querySelector('[name="rating"]').value;

    fetch('/rating', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ rating })
    })
    .then(res => {
      if (!res.ok) throw new Error('Gagal kirim rating');
      return res.json();
    })
    .then(() => {
      alert('Terima kasih atas ratingnya ⭐');
      location.reload();
    })
    .catch(err => {
      alert('Kamu harus login untuk memberi rating');
      console.error(err);
    });
  });
});
</script>

  <!-- Search & Popular Menus (Customer-focused) -->
  @auth
  @if(auth()->user()->role === 'customer')
    <!-- Search & Popular Menus (Customer-focused) -->
    <div class="max-w-3xl w-full mx-auto px-4 mt-6">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold">Temukan Makanan</h3>
        <a href="{{ route('mood.explore') }}" class="text-sm text-blue-600">
  Lihat semua
</a>
      </div>
      <div class="mb-4">
        <input id="home-search" type="search" placeholder="Cari menu atau tenant..." class="w-full border rounded px-3 py-2" />
      </div>
      <div id="popular-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($menus->take(6) as $menu)
          <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-4">
            <img src="{{ $menu->image ?? asset('img/placeholder.png') }}" class="w-20 h-20 object-cover rounded" />
            <div class="flex-1">
              <h4 class="font-semibold">{{ $menu->menu_name }}</h4>
              <p class="text-sm text-gray-500">{{ $menu->tenant->tenant_name ?? '' }}</p>
              <p class="text-sm text-gray-700 mt-1">Rp {{ number_format($menu->price ?? 0, 0, ',', '.') }}</p>
            </div>
            <div>
              <button onclick="openMood('{{ Str::slug($menu->category->mood->mood_name ?? 'biasa-aja') }}', {{ $menu->category->mood_id ?? 0 }}, '{{ addslashes($menu->category->mood->mood_name ?? 'Mood') }}')" class="px-3 py-2 bg-lime-500 text-white rounded">Rekomendasi</button>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif
@endauth

  <!-- Page 2: Dashboard Overview (Admin/Tenant Only) -->
<div id="page-2" class="page-section" style="padding-top: 80px; display: none;">
  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Dashboard Overview</h2>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Total Menu -->
      <div class="bg-white shadow-lg rounded-lg p-5 flex items-center space-x-4">
        <div class="flex-shrink-0 text-blue-500">
          <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <div>
          <p class="text-sm text-gray-500">Total Menu</p>
          <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_menus'] ?? 0 }}</p>
        </div>
      </div>

      <!-- Total Kategori -->
      <div class="bg-white shadow-lg rounded-lg p-5 flex items-center space-x-4">
        <div class="flex-shrink-0 text-green-500">
          <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
          </svg>
        </div>
        <div>
          <p class="text-sm text-gray-500">Total Kategori</p>
          <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_categories'] ?? 0 }}</p>
        </div>
      </div>

      <!-- Total Mood -->
      <div class="bg-white shadow-lg rounded-lg p-5 flex items-center space-x-4">
        <div class="flex-shrink-0 text-purple-500">
          <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <p class="text-sm text-gray-500">Total Mood</p>
          <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_moods'] ?? 0 }}</p>
        </div>
      </div>

      <!-- Total Tenant -->
      <div class="bg-white shadow-lg rounded-lg p-5 flex items-center space-x-4">
        <div class="flex-shrink-0 text-red-500">
          <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
        </div>
        <div>
          <p class="text-sm text-gray-500">Total Tenant</p>
          <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_tenants'] ?? 0 }}</p>
        </div>
      </div>
    </div>
  
<!-- Quick Actions -->
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

<!-- Recommendation Modal -->
      <div id="recommendation-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-60 z-50 flex items-center justify-center px-4 py-6">
        <div class="bg-white rounded-lg w-full max-w-3xl shadow-lg overflow-hidden">
          <div class="p-4 border-b flex justify-between items-center">
            <h3 id="recommendation-title" class="text-lg font-semibold">Rekomendasi</h3>
            <button onclick="closeRecommendation()" class="text-gray-600 hover:text-gray-800">Tutup ✕</button>
          </div>
          <div class="p-4">
            <div id="recommendation-list" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
          </div>
        </div>
      </div>
          </button>
        </div>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @foreach($moods as $mood)
    const ctx{{ $mood->id }} = document.getElementById('chart-mood-{{ $mood->id }}').getContext('2d');
    const data{{ $mood->id }} = @json(array_values($stats['mood_history'][$mood->id] ?? []));
    const labels{{ $mood->id }} = @json(array_keys($stats['mood_history'][$mood->id] ?? []));

    new Chart(ctx{{ $mood->id }}, {
        type: 'line',
        data: {
            labels: labels{{ $mood->id }},
            datasets: [{
                label: '{{ $mood->mood_name }}',
                data: data{{ $mood->id }},
                borderColor: 'rgba(59, 130, 246, 1)',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                tension: 0.3,
                fill: true,
                pointRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: '#e5e7eb' } }
            }
        }
    });
    @endforeach
});

// Modal Recommendation
function openMood(slug, moodId, moodName) {
    document.getElementById('recommendation-title').innerText = "Rekomendasi untuk " + moodName;
    const list = document.getElementById('recommendation-list');
    list.innerHTML = "<p class='col-span-full text-gray-500'>Loading...</p>";
    document.getElementById('recommendation-modal').classList.remove('hidden');

    fetch(`/api/recommendation/mood/${moodId}`)
        .then(res => res.json())
        .then(data => {
            list.innerHTML = '';
            if(data.length === 0) {
                list.innerHTML = "<p class='col-span-full text-gray-500'>Tidak ada menu.</p>";
            } else {
                data.forEach(item => {
                    list.innerHTML += `
                        <div class="bg-gray-100 p-4 rounded-lg shadow hover:shadow-md flex flex-col h-full">
                            ${item.image ? `<img src="${item.image}" alt="${item.menu_name}" class="h-32 w-full object-cover rounded mb-3">` : ''}
                            <h4 class="font-semibold text-gray-900 mb-1 truncate">${item.menu_name}</h4>
                            <p class="text-sm text-gray-600 mb-2 line-clamp-3">${item.description ?? ''}</p>
                            <button class="mt-auto bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600 transition">Lihat Detail</button>
                        </div>
                    `;
                });
            }
        })
        .catch(err => {
            list.innerHTML = "<p class='col-span-full text-red-500'>Gagal load data.</p>";
            console.error(err);
        });
}

function closeRecommendation() {
    document.getElementById('recommendation-modal').classList.add('hidden');
}
</script>


  <!-- Page 3: Dashboard Menus (Admin/Tenant Only) -->
  <div id="page-3" class="page-section" style="padding-top: 80px; display: none;">
    <div class="max-w-7xl mx-auto py-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Pengelolaan Menu</h2>
        <button onclick="openMenuModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
          + Tambah Menu
        </button>
      </div>

      @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          {{ session('success') }}
        </div>
      @endif

      <!-- Menu Table -->
      <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Menu</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mood</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($menus as $menu)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ $menu->menu_name }}</div>
                  @if($menu->description)
                  <div class="text-sm text-gray-500">{{ Str::limit($menu->description, 50) }}</div>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  Rp {{ number_format($menu->price, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $menu->tenant->tenant_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $menu->category->category_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $menu->category->mood->mood_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button onclick="editMenu({{ $menu->id }}, '{{ addslashes($menu->menu_name) }}', {{ $menu->price }}, '{{ addslashes($menu->description ?? '') }}', {{ $menu->tenant_id }}, {{ $menu->category_id }})" 
                    class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                  <form action="{{ route('dashboard.menus.delete', $menu->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Page 4: Dashboard Categories (Admin/Tenant Only) -->
  <div id="page-4" class="page-section" style="padding-top: 80px; display: none;">
    <div class="max-w-7xl mx-auto py-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Pengelolaan Kategori Mood</h2>
        <button onclick="openCategoryModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
          + Tambah Kategori
        </button>
      </div>

      @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          {{ session('success') }}
        </div>
      @endif

      <!-- Categories Table -->
      <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mood</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($categories as $category)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ $category->category_name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ $category->mood->mood_name }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button onclick="editCategory({{ $category->id }}, '{{ addslashes($category->category_name) }}', {{ $category->mood_id }})" 
                    class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                  <form action="{{ route('dashboard.categories.delete', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Page 5: Dashboard Moods (Admin/Tenant Only) -->
  <div id="page-5" class="page-section" style="padding-top: 80px; display: none;">
    <div class="max-w-7xl mx-auto py-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Pengelolaan Mood</h2>
        <button onclick="openMoodModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
          + Tambah Mood
        </button>
      </div>

      @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          {{ session('success') }}
        </div>
      @endif

      <!-- Moods Table -->
      <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mood</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($moods as $mood)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ $mood->mood_name }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-500">{{ $mood->description ?? '-' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    {{ $mood->categories_count }} kategori
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button onclick="editMood({{ $mood->id }}, '{{ addslashes($mood->mood_name) }}', '{{ addslashes($mood->description ?? '') }}')" 
                    class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                  <form action="{{ route('dashboard.moods.delete', $mood->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus mood ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

@php
    $user = auth()->user();
@endphp

@if($user && $user->role !== 'admin')
<div id="page-6" class="page-section" style="padding-top: 80px;">
    <div class="max-w-7xl mx-auto py-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold text-gray-900">Pengelolaan Tenant untuk Event</h2>
            <button onclick="openTenantModal()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                + Tambah Tenant ke Event
            </button>
        </div>

        <div id="tenant-success" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"></div>

        <div class="mb-4">
            <input type="text" id="tenant-search" placeholder="Cari tenant..." 
                class="w-full sm:w-1/3 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <!-- Tenant / Event Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tenant-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tenant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Berakhir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tenants as $tenant)
                            @forelse($tenant->events as $event)
                                <tr data-id="{{ $tenant->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $tenant->tenant_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $tenant->location }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $event->event_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->pivot->start_date ? \Carbon\Carbon::parse($event->pivot->start_date)->format('d-m-Y') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->pivot->end_date ? \Carbon\Carbon::parse($event->pivot->end_date)->format('d-m-Y') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($event->pivot->active)
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Aktif</span>
                                        @else
                                            <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded-full text-xs font-semibold">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editTenantEvent({{ $tenant->id }}, {{ $event->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                        <button onclick="deleteTenantEvent({{ $tenant->id }}, {{ $event->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tenant ini belum mendaftar di event manapun</td>
                                </tr>
                            @endforelse
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada tenant sama sekali</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Tenant ke Event -->
<div id="tenant-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-11/12 sm:w-2/3 md:w-1/2 p-6 relative">
        <h3 class="text-xl font-bold mb-4">Tambah Tenant ke Event</h3>
        <button onclick="closeTenantModal()" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">&times;</button>

        <form id="tenant-event-form">
            <div class="mb-4">
                <label class="block mb-2 font-medium">Pilih Tenant</label>
                <select id="tenant_id" class="w-full border rounded px-3 py-2">
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}">{{ $tenant->tenant_name }} - {{ $tenant->location }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Pilih Event</label>
                <select id="event_id" class="w-full border rounded px-3 py-2">
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->event_name }} - {{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('d-m-Y') : '-' }} s/d {{ $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('d-m-Y') : '-' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Tanggal Mulai</label>
                <input type="date" id="pivot_start_date" class="w-full border rounded px-3 py-2" value="{{ date('Y-m-d') }}">
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Tanggal Berakhir</label>
                <input type="date" id="pivot_end_date" class="w-full border rounded px-3 py-2" value="{{ date('Y-m-d') }}">
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="pivot_active" checked class="form-checkbox">
                    <span class="ml-2">Aktif</span>
                </label>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="submitTenantEvent()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openTenantModal() {
    document.getElementById('tenant-modal').classList.remove('hidden');
}
function closeTenantModal() {
    document.getElementById('tenant-modal').classList.add('hidden');
}

// AJAX submit function
function submitTenantEvent() {
    const tenantId = document.getElementById('tenant_id').value;
    const eventId = document.getElementById('event_id').value;
    const startDate = document.getElementById('pivot_start_date').value;
    const endDate = document.getElementById('pivot_end_date').value;
    const active = document.getElementById('pivot_active').checked;

    fetch("{{ route('tenant.event.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            tenant_id: tenantId,
            event_id: eventId,
            start_date: startDate,
            end_date: endDate,
            active: active
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            const tbody = document.querySelector("#tenant-table tbody");
            const newRow = document.createElement("tr");
            newRow.setAttribute("data-id", data.tenant.id);
            newRow.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">${data.tenant.tenant_name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.tenant.location}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${data.tenant.event_name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.tenant.start_date}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.tenant.end_date}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    ${data.tenant.active ? '<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Aktif</span>' : '<span class="bg-gray-200 text-gray-600 px-2 py-1 rounded-full text-xs font-semibold">Tidak Aktif</span>'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button onclick="editTenantEvent(${data.tenant.id}, ${data.tenant.event_id})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                    <button onclick="deleteTenantEvent(${data.tenant.id}, ${data.tenant.event_id})" class="text-red-600 hover:text-red-900">Hapus</button>
                </td>
            `;
            tbody.appendChild(newRow);

            document.getElementById('tenant-success').textContent = "Tenant berhasil ditambahkan ke event!";
            document.getElementById('tenant-success').classList.remove('hidden');

            closeTenantModal();
        }
    })
    .catch(err => console.error(err));
}
</script>
@endif

  <!-- Page 7: Statistics (Admin/Tenant Only) -->
  <div id="page-7" class="page-section" style="padding-top: 80px; display: none;">
    <div class="max-w-7xl mx-auto py-6">
      <h1 class="text-4xl font-bold text-gray-800 mb-2">Statistik MoodFood</h1>
      <p class="text-gray-600 mb-6">Laporan statistik interaksi pengguna</p>

      <!-- Navigation -->
      <div class="mb-6 flex gap-4">
        <button onclick="showBeforeAfter()" id="btn-before-after" 
          class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
          Statistik Sebelum & Sesudah
        </button>
        <button onclick="showPerEvent()" id="btn-per-event" 
          class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition">
          Statistik Per Event
        </button>
      </div>

      <!-- Before After Section -->
      <div id="section-before-after" class="space-y-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h2 class="text-2xl font-bold text-gray-800 mb-4">Statistik Sebelum & Sesudah</h2>
          <form id="form-before-after" class="mb-6">
            <div class="flex gap-4 items-end">
              <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                <input type="date" id="date-input" name="date" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              </div>
              <button type="submit" 
                class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                Tampilkan Statistik
              </button>
            </div>
          </form>
          <div id="before-after-results" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="card bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-4">Sebelum</h3>
                <div id="before-stats" class="space-y-2"></div>
              </div>
              <div class="card bg-green-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-800 mb-4">Sesudah</h3>
                <div id="after-stats" class="space-y-2"></div>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="card bg-white rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Mood (Sebelum)</h3>
                <canvas id="chart-mood-before"></canvas>
              </div>
              <div class="card bg-white rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Mood (Sesudah)</h3>
                <canvas id="chart-mood-after"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Per Event Section -->
      <div id="section-per-event" class="hidden space-y-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h2 class="text-2xl font-bold text-gray-800 mb-4">Statistik Per Event</h2>
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Event</label>
            <select id="event-select" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">Semua Event</option>
              @foreach($events as $event)
                <option value="{{ $event->id }}">{{ $event->event_name }}</option>
              @endforeach
            </select>
          </div>
          <button onclick="loadEventStats()" 
            class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition mb-6">
            Tampilkan Statistik
          </button>
          <div id="per-event-results" class="hidden">
            <div id="event-stats-content"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

 <!-- Bottom Navigation -->
<div id="bottom-nav" class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg z-50 transition-transform duration-150 ease-out">
  <div class="max-w-7xl mx-auto px-4 py-3 flex justify-center space-x-8 sm:space-x-12 text-center">
    <div class="bottom-nav-item active" onclick="showPage(1)">
      <span class="nav-icon text-xl sm:text-2xl mb-1 w-8 h-8 sm:w-9 sm:h-9">🏠</span>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Home</span>
    </div>
    @auth
  @if(in_array(auth()->user()->role, ['admin', 'tenant']))
    <div class="bottom-nav-item" onclick="showPage(2)">
      <span class="nav-icon text-xl sm:text-2xl mb-1 w-8 h-8 sm:w-9 sm:h-9">📊</span>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Dashboard</span>
    </div>
    <div class="bottom-nav-item" onclick="showPage(3)">
      <span class="nav-icon text-xl sm:text-2xl mb-1 w-8 h-8 sm:w-9 sm:h-9">🍽️</span>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Menu</span>
    </div>
    <div class="bottom-nav-item" onclick="showPage(4)">
      <span class="nav-icon text-xl sm:text-2xl mb-1 w-8 h-8 sm:w-9 sm:h-9">📁</span>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Kategori</span>
    </div>
    <div class="bottom-nav-item" onclick="showPage(5)">
      <span class="nav-icon text-xl sm:text-2xl mb-1 w-8 h-8 sm:w-9 sm:h-9">😊</span>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Mood</span>
    </div>

    <!-- Tenant Button: hanya untuk tenant, bukan admin -->
    @if(auth()->user()->role === 'tenant')
      <div class="bottom-nav-item" onclick="showPage(6)">
        <span class="nav-icon text-xl sm:text-2xl mb-1 w-8 h-8 sm:w-9 sm:h-9">🏢</span>
        <span class="text-xs sm:text-sm text-gray-600 font-medium">Tenant</span>
      </div>
    @endif

    <div class="bottom-nav-item" onclick="showPage(7)">
      <span class="nav-icon text-xl sm:text-2xl mb-1 w-8 h-8 sm:w-9 sm:h-9">📈</span>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Statistik</span>
    </div>
  @endif
@endauth
  </div>
</div>

<script>
  const bottomNav = document.getElementById('bottom-nav');
  let lastScroll = window.scrollY;
  let ticking = false;

  function updateNav(scrollPos) {
    if (scrollPos > lastScroll && scrollPos > 50) {
      // scroll ke bawah -> sembunyikan
      bottomNav.style.transform = 'translateY(100%)';
    } else {
      // scroll ke atas -> tampilkan
      bottomNav.style.transform = 'translateY(0)';
    }
    lastScroll = scrollPos;
    ticking = false;
  }

  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(() => updateNav(window.scrollY));
      ticking = true;
    }
  });

  // Hover / fokus -> selalu muncul
  bottomNav.addEventListener('mouseenter', () => bottomNav.style.transform = 'translateY(0)');
  bottomNav.addEventListener('mouseleave', () => {
    if (window.scrollY > 50) bottomNav.style.transform = 'translateY(100%)';
  });

  // Untuk kontainer scroll di halaman Mood atau Statistik
  document.querySelectorAll('.page-section').forEach(page => {
    page.addEventListener('scroll', (e) => {
      if (!ticking) {
        window.requestAnimationFrame(() => updateNav(e.target.scrollTop));
        ticking = true;
      }
    });
  });
</script>

  <!-- Menu Modal -->
  <div id="menu-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4" id="menu-modal-title">Tambah Menu Baru</h3>
        <form id="menu-form" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="_method" id="menu-form-method" value="POST">
          <input type="hidden" name="menu_id" id="menu-id">
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu</label>
            <input type="text" name="menu_name" id="menu_name" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
            <input type="number" name="price" id="price" step="0.01" min="0" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" id="description" rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Menu</label>
            <input type="file" name="image" id="image"
              accept="image/*"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tenant</label>
            <select name="tenant_id" id="tenant_id" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Pilih Tenant</option>
              @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}">{{ $tenant->tenant_name }} - {{ $tenant->location }}</option>
              @endforeach
            </select>
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select name="category_id" id="category_id" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Pilih Kategori</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}">
                  {{ $category->category_name }} ({{ $category->mood->mood_name }})
                </option>
              @endforeach
            </select>
          </div>
          
          <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeMenuModal()" 
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
              Batal
            </button>
            <button type="submit" 
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Category Modal -->
  <div id="category-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4" id="category-modal-title">Tambah Kategori Baru</h3>
        <form id="category-form" method="POST">
          @csrf
          <input type="hidden" name="_method" id="category-form-method" value="POST">
          <input type="hidden" name="category_id" id="category-id">
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
            <input type="text" name="category_name" id="category_name" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Mood</label>
            <select name="mood_id" id="mood_id" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Pilih Mood</option>
              @foreach($moods as $mood)
                <option value="{{ $mood->id }}">{{ $mood->mood_name }}</option>
              @endforeach
            </select>
          </div>
          
          <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeCategoryModal()" 
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
              Batal
            </button>
            <button type="submit" 
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Mood Modal -->
  <div id="mood-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4" id="mood-modal-title">Tambah Mood Baru</h3>
        <form id="mood-form" method="POST">
          @csrf
          <input type="hidden" name="_method" id="mood-form-method" value="POST">
          <input type="hidden" name="mood_id" id="mood-id">
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mood</label>
            <input type="text" name="mood_name" id="mood_name" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" id="mood_description" rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
          </div>
          
          <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeMoodModal()" 
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
              Batal
            </button>
            <button type="submit" 
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    let currentPage = 1;
    let moodBeforeChart = null;
    let moodAfterChart = null;

    function showPage(pageNum) {
      @auth
        @if(!in_array(auth()->user()->role, ['admin', 'tenant']))
          // Authenticated non-admin (customer) can only access page 1
          if (pageNum !== 1) {
            alert('Akses ditolak. Hanya admin dan tenant yang dapat mengakses halaman ini.');
            return;
          }
        @endif
      @endauth
      @guest
        // Guests cannot access admin pages
        if (pageNum !== 1) {
          alert('Akses ditolak. Silakan login sebagai admin atau tenant.');
          return;
        }
      @endguest
      
      // Hide all pages
      document.querySelectorAll('.page-section').forEach(page => {
        page.classList.remove('active');
        page.style.display = 'none';
      });
      
      // Show selected page
      const targetPage = document.getElementById(`page-${pageNum}`);
      if (targetPage) {
        targetPage.classList.add('active');
        targetPage.style.display = 'block';
      }
      currentPage = pageNum;
      
      // Update navigation
      document.querySelectorAll('.bottom-nav-item').forEach((item, idx) => {
        const pageIndex = idx + 1;
        item.classList.toggle('active', pageIndex === pageNum);
        // nav-icon highlight handled by .bottom-nav-item.active .nav-icon
      });
      
      // Scroll to top
      window.scrollTo(0, 0);
    }

    // Menu Modal Functions
    function openMenuModal() {
      document.getElementById('menu-modal').classList.remove('hidden');
      document.getElementById('menu-modal-title').textContent = 'Tambah Menu Baru';
      document.getElementById('menu-form').action = '{{ route("dashboard.menus.store") }}';
      document.getElementById('menu-form-method').value = 'POST';
      document.getElementById('menu-form').reset();
      document.getElementById('menu-id').value = '';
    }

    function closeMenuModal() {
      document.getElementById('menu-modal').classList.add('hidden');
    }

    function editMenu(id, name, price, description, tenantId, categoryId) {
      document.getElementById('menu-modal').classList.remove('hidden');
      document.getElementById('menu-modal-title').textContent = 'Edit Menu';
      document.getElementById('menu-form').action = '{{ route("dashboard.menus.update", ":id") }}'.replace(':id', id);
      document.getElementById('menu-form-method').value = 'PUT';
      document.getElementById('menu-id').value = id;
      document.getElementById('menu_name').value = name;
      document.getElementById('price').value = price;
      document.getElementById('description').value = description || '';
      document.getElementById('tenant_id').value = tenantId;
      document.getElementById('category_id').value = categoryId;
    }

    // Category Modal Functions
    function openCategoryModal() {
      document.getElementById('category-modal').classList.remove('hidden');
      document.getElementById('category-modal-title').textContent = 'Tambah Kategori Baru';
      document.getElementById('category-form').action = '{{ route("dashboard.categories.store") }}';
      document.getElementById('category-form-method').value = 'POST';
      document.getElementById('category-form').reset();
      document.getElementById('category-id').value = '';
    }

    function closeCategoryModal() {
      document.getElementById('category-modal').classList.add('hidden');
    }

    function editCategory(id, name, moodId) {
      document.getElementById('category-modal').classList.remove('hidden');
      document.getElementById('category-modal-title').textContent = 'Edit Kategori';
      document.getElementById('category-form').action = '{{ route("dashboard.categories.update", ":id") }}'.replace(':id', id);
      document.getElementById('category-form-method').value = 'PUT';
      document.getElementById('category-id').value = id;
      document.getElementById('category_name').value = name;
      document.getElementById('mood_id').value = moodId;
    }

    // Mood Modal Functions
    function openMoodModal() {
      document.getElementById('mood-modal').classList.remove('hidden');
      document.getElementById('mood-modal-title').textContent = 'Tambah Mood Baru';
      document.getElementById('mood-form').action = '{{ route("dashboard.moods.store") }}';
      document.getElementById('mood-form-method').value = 'POST';
      document.getElementById('mood-form').reset();
      document.getElementById('mood-id').value = '';
    }

    function closeMoodModal() {
      document.getElementById('mood-modal').classList.add('hidden');
    }

    function editMood(id, name, description) {
      document.getElementById('mood-modal').classList.remove('hidden');
      document.getElementById('mood-modal-title').textContent = 'Edit Mood';
      document.getElementById('mood-form').action = '{{ route("dashboard.moods.update", ":id") }}'.replace(':id', id);
      document.getElementById('mood-form-method').value = 'PUT';
      document.getElementById('mood-id').value = id;
      document.getElementById('mood_name').value = name;
      document.getElementById('mood_description').value = description || '';
    }

    // Statistics Functions
    function showBeforeAfter() {
      document.getElementById('section-before-after').classList.remove('hidden');
      document.getElementById('section-per-event').classList.add('hidden');
      document.getElementById('btn-before-after').classList.remove('bg-gray-300', 'text-gray-700');
      document.getElementById('btn-before-after').classList.add('bg-blue-600', 'text-white');
      document.getElementById('btn-per-event').classList.remove('bg-blue-600', 'text-white');
      document.getElementById('btn-per-event').classList.add('bg-gray-300', 'text-gray-700');
    }

    function showPerEvent() {
      document.getElementById('section-before-after').classList.add('hidden');
      document.getElementById('section-per-event').classList.remove('hidden');
      document.getElementById('btn-per-event').classList.remove('bg-gray-300', 'text-gray-700');
      document.getElementById('btn-per-event').classList.add('bg-blue-600', 'text-white');
      document.getElementById('btn-before-after').classList.remove('bg-blue-600', 'text-white');
      document.getElementById('btn-before-after').classList.add('bg-gray-300', 'text-gray-700');
    }

    document.getElementById('form-before-after').addEventListener('submit', async function(e) {
      e.preventDefault();
      const date = document.getElementById('date-input').value;
      
      try {
        const response = await fetch(`/statistics/before-after?date=${date}`, {
          headers: {
            'Accept': 'application/json'
          }
        });
        const data = await response.json();
        displayBeforeAfter(data);
      } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data');
      }
    });

    function displayBeforeAfter(data) {
      document.getElementById('before-after-results').classList.remove('hidden');
      
      const beforeStats = document.getElementById('before-stats');
      beforeStats.innerHTML = `
        <p class="text-gray-700"><span class="font-semibold">Total Interaksi:</span> ${data.before.total_interactions}</p>
        <p class="text-gray-700"><span class="font-semibold">Pengguna Unik:</span> ${data.before.unique_users}</p>
      `;

      const afterStats = document.getElementById('after-stats');
      afterStats.innerHTML = `
        <p class="text-gray-700"><span class="font-semibold">Total Interaksi:</span> ${data.after.total_interactions}</p>
        <p class="text-gray-700"><span class="font-semibold">Pengguna Unik:</span> ${data.after.unique_users}</p>
      `;

      if (moodBeforeChart) moodBeforeChart.destroy();
      if (moodAfterChart) moodAfterChart.destroy();

      moodBeforeChart = new Chart(document.getElementById('chart-mood-before'), {
        type: 'doughnut',
        data: {
          labels: data.before.by_mood.map(m => m.mood_name),
          datasets: [{
            data: data.before.by_mood.map(m => m.total),
            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899']
          }]
        }
      });

      moodAfterChart = new Chart(document.getElementById('chart-mood-after'), {
        type: 'doughnut',
        data: {
          labels: data.after.by_mood.map(m => m.mood_name),
          datasets: [{
            data: data.after.by_mood.map(m => m.total),
            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899']
          }]
        }
      });
    }

    async function loadEventStats() {
  const eventId = document.getElementById('event-select').value;
  const url = eventId 
    ? `/statistics/per-event?event_id=${eventId}`
    : '/statistics/per-event';

  console.log('Fetching URL:', url); // debug

  try {
    const response = await fetch(url, {
      headers: { 'Accept': 'application/json' }
    });

    // Debug: cek status dan raw response
    console.log('Status:', response.status);
    const text = await response.text();
    console.log('Raw response:', text);

    let data;
    try {
      data = JSON.parse(text);
    } catch (err) {
      console.error('JSON parse error:', err);
      alert('Gagal membaca data JSON dari server.');
      return;
    }

    displayEventStats(data, eventId);
  } catch (error) {
    console.error('Fetch error:', error);
    alert('Terjadi kesalahan saat mengambil data.');
  }
}

function displayEventStats(data, eventId) {
  const content = document.getElementById('event-stats-content');
  document.getElementById('per-event-results').classList.remove('hidden');

  try {
    if (eventId) {
      // single event
      const byMood = data.by_mood || [];
      const byMenu = data.by_menu || [];

      content.innerHTML = `
        <div class="mb-6">
          <h3 class="text-xl font-bold text-gray-800 mb-2">${data.event?.event_name || 'Unknown Event'}</h3>
          <p class="text-gray-600 mb-4">${data.event?.description || ''}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-sm text-gray-600">Total Interaksi</p>
            <p class="text-2xl font-bold text-blue-600">${data.total_interactions || 0}</p>
          </div>
          <div class="bg-green-50 rounded-lg p-4">
            <p class="text-sm text-gray-600">Pengguna Unik</p>
            <p class="text-2xl font-bold text-green-600">${data.unique_users || 0}</p>
          </div>
          <div class="bg-purple-50 rounded-lg p-4">
            <p class="text-sm text-gray-600">Mood Terpopuler</p>
            <p class="text-2xl font-bold text-purple-600">${byMood[0]?.mood_name || 'N/A'}</p>
          </div>
        </div>
      `;

      // Chart
      setTimeout(() => {
        new Chart(document.getElementById('chart-event-mood'), {
          type: 'bar',
          data: {
            labels: byMood.map(m => m.mood_name),
            datasets: [{ label: 'Interaksi', data: byMood.map(m => m.total), backgroundColor: '#3B82F6' }]
          },
          options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
      }, 100);

    } else {
      // all events
      content.innerHTML = `
        <div class="space-y-4">
          ${(data.events || []).map(event => `
            <div class="card bg-white rounded-lg p-6 border border-gray-200">
              <div class="flex justify-between items-start mb-4">
                <div>
                  <h3 class="text-xl font-bold text-gray-800">${event.event?.event_name || 'Unknown Event'}</h3>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold">
                  ${event.total_interactions || 0} interaksi
                </span>
              </div>
            </div>
          `).join('')}
        </div>
      `;
    }

  } catch (err) {
    console.error('Render error:', err);
    content.innerHTML = '<p class="text-red-500">Gagal menampilkan data statistik event.</p>';
  }
}


    // Close modals when clicking outside
    window.onclick = function(event) {
      const menuModal = document.getElementById('menu-modal');
      const categoryModal = document.getElementById('category-modal');
      const moodModal = document.getElementById('mood-modal');
      
      if (event.target == menuModal) closeMenuModal();
      if (event.target == categoryModal) closeCategoryModal();
      if (event.target == moodModal) closeMoodModal();
    }

    // Initialize - show page 1 and set display
    document.querySelectorAll('.page-section').forEach(page => {
      page.style.display = 'none';
    });
    document.getElementById('page-1').style.display = 'block';
    showPage(1);
  </script>
  <script>

    function openMood(slug, moodId, moodName) {
      // Open mood page (single-page-like) — fetch recommendations and show modal
      const titleEl = document.getElementById('recommendation-title');
      const listEl = document.getElementById('recommendation-list');
      titleEl.innerText = `Rekomendasi untuk ${moodName}`;
      listEl.innerHTML = `<p class="text-gray-500">Memuat...</p>`;
      document.getElementById('recommendation-modal').classList.remove('hidden');

      fetch(`/api/recommendation/mood/${moodId}`)
        .then(r => r.json())
        .then(data => {
          if (!Array.isArray(data) || data.length === 0) {
            listEl.innerHTML = `<p class="text-gray-500">Tidak ada rekomendasi untuk mood ini.</p>`;
            return;
          }
          listEl.innerHTML = '';
          data.forEach(menu => {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-lg shadow p-4 flex flex-col';
            card.innerHTML = `
              <div class="flex items-start space-x-4">
                <img src="${menu.image ?? '/img/placeholder.png'}" class="w-20 h-20 object-cover rounded" />
                <div class="flex-1">
                  <h4 class="font-semibold">${menu.menu_name}</h4>
                  <p class="text-sm text-gray-500">${menu.tenant?.tenant_name ?? ''}</p>
                  <p class="text-sm text-gray-700 mt-2">Rp ${menu.price ?? 0}</p>
                </div>
              </div>
              <div class="mt-3">
                <button class="px-3 py-2 bg-lime-500 text-white rounded select-btn">Pilih</button>
              </div>
            `;
            const btn = card.querySelector('.select-btn');
            btn.addEventListener('click', () => selectMenuPublic(menu.id, moodId, menu.menu_name));
            listEl.appendChild(card);
          });
        }).catch(err => {
          console.error(err);
          listEl.innerHTML = `<p class="text-red-500">Gagal memuat data.</p>`;
        });
    }

    function closeRecommendation(){
      document.getElementById('recommendation-modal').classList.add('hidden');
    }

    function selectMenuPublic(menuId, moodId, menuName){
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      fetch('{{ route('interactions.public.store', [], false) }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ menu_id: menuId, mood_id: moodId })
      }).then(r => r.json()).then(data => {
        alert(`Terima kasih! Anda memilih ${menuName}.`);
        closeRecommendation();
      }).catch(err => {
        console.error(err);
        alert('Gagal menyimpan pilihan. Coba lagi.');
      });
    }

    // Home search filter for popular grid
    document.getElementById('home-search')?.addEventListener('input', function(e){
      const q = e.target.value.toLowerCase();
      document.querySelectorAll('#popular-grid > div').forEach(card => {
        const text = card.innerText.toLowerCase();
        card.style.display = text.includes(q) ? '' : 'none';
      });
    });
  </script>
</body>
</html>
