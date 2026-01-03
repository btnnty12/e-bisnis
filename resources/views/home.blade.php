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

  <!-- Page 6: Statistics (Admin/Tenant Only) -->
<div id="page-6" class="page-section relative z-10"
     style="padding-top:80px; padding-bottom:120px; display:none;">
  <div class="max-w-7xl mx-auto py-6">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Statistik MoodFood</h1>
    <p class="text-gray-600 mb-6">Laporan statistik interaksi pengguna</p>

    <div class="mb-6 flex gap-4">
      <button type="button" id="btn-before-after"
        class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold">
        Statistik Sebelum & Sesudah
      </button>
      <button type="button" id="btn-per-event"
        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold">
        Statistik Per Event
      </button>
    </div>

    <div id="section-before-after" class="space-y-6">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Statistik Sebelum & Sesudah</h2>

        <div class="mb-6 flex gap-4 items-end">
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
            <input type="date" id="date-input"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg">
          </div>
          <button type="button" id="btn-show-before-after"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold">
            Tampilkan Statistik
          </button>
        </div>

        <div id="before-after-results" class="hidden">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-50 rounded-lg p-6">
              <h3 class="text-lg font-semibold text-blue-800 mb-4">Sebelum</h3>
              <div id="before-stats"></div>
            </div>
            <div class="bg-green-50 rounded-lg p-6">
              <h3 class="text-lg font-semibold text-green-800 mb-4">Sesudah</h3>
              <div id="after-stats"></div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div id="section-per-event" class="hidden space-y-6">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Statistik Per Event</h2>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Event</label>
          <select id="event-select" class="w-full px-4 py-2 border rounded-lg">
            <option value="">Semua Event</option>
            @foreach($events as $event)
              <option value="{{ $event->id }}" data-pay="{{ $event->pay ?? '' }}">{{ $event->event_name }}</option>
            @endforeach
          </select>
        </div>

        <button type="button" id="btn-show-event"
          class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold">
          Tampilkan Statistik
        </button>

        <div id="per-event-results" class="hidden mt-6">
          <div id="event-stats-content"></div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const beforeBtn = document.getElementById('btn-before-after');
  const eventBtn  = document.getElementById('btn-per-event');

  const beforeSec = document.getElementById('section-before-after');
  const eventSec  = document.getElementById('section-per-event');

  if (beforeBtn && eventBtn && beforeSec && eventSec) {

    beforeBtn.addEventListener('click', function () {
      beforeSec.classList.remove('hidden');
      eventSec.classList.add('hidden');

      beforeBtn.classList.remove('bg-gray-300','text-gray-700');
      beforeBtn.classList.add('bg-blue-600','text-white');

      eventBtn.classList.remove('bg-blue-600','text-white');
      eventBtn.classList.add('bg-gray-300','text-gray-700');
    });

    eventBtn.addEventListener('click', function () {
      eventSec.classList.remove('hidden');
      beforeSec.classList.add('hidden');

      eventBtn.classList.remove('bg-gray-300','text-gray-700');
      eventBtn.classList.add('bg-blue-600','text-white');

      beforeBtn.classList.remove('bg-blue-600','text-white');
      beforeBtn.classList.add('bg-gray-300','text-gray-700');
    });
  }

  const btnBeforeAfter = document.getElementById('btn-show-before-after');
  if (btnBeforeAfter) {
    btnBeforeAfter.addEventListener('click', async function () {
      btnBeforeAfter.disabled = true;
      try {
        await loadBeforeAfterStats();
      } catch (e) {
        console.error(e);
      } finally {
        btnBeforeAfter.disabled = false;
      }
    });
  }

  const btnEvent = document.getElementById('btn-show-event');
  if (btnEvent) {
    btnEvent.addEventListener('click', async function () {
      btnEvent.disabled = true;
      try {
        await loadEventStats();
      } catch (e) {
        console.error(e);
      } finally {
        btnEvent.disabled = false;
      }
    });
  }

});
</script>


 <!-- Bottom Navigation -->
<div id="bottom-nav"
  class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg z-[9999] pointer-events-auto transition-transform duration-150 ease-out">
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

    <div class="bottom-nav-item" onclick="showPage(6)">
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
      // hide all pages
      document.querySelectorAll('.page-section').forEach(page => {
        page.classList.remove('active');
        page.style.display = 'none';
        page.style.pointerEvents = 'none';
      });

      const targetPage = document.getElementById(`page-${pageNum}`);
      if (!targetPage) {
        console.warn('Requested page not found:', pageNum);
        return;
      }

      // show target
      targetPage.classList.add('active');
      targetPage.style.display = 'block';
      targetPage.style.pointerEvents = 'auto';
      currentPage = pageNum;

      // update nav active state
      const navItems = Array.from(document.querySelectorAll('.bottom-nav-item'));
      navItems.forEach((item, idx) => item.classList.toggle('active', idx === pageNum - 1));

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
      // populate fields if inputs exist
      const nameInput = document.querySelector('#category-modal input[name="category_name"]');
      if (nameInput) nameInput.value = name || '';
      const moodSelect = document.querySelector('#category-modal select[name="mood_id"]');
      if (moodSelect) moodSelect.value = moodId || '';
    }

    // Mood Modal Functions
    function openMoodModal() {
      const modal = document.getElementById('mood-modal');
      if (!modal) return;
      modal.classList.remove('hidden');
      const title = document.getElementById('mood-modal-title');
      if (title) title.textContent = 'Tambah Mood Baru';
      const form = document.getElementById('mood-form');
      if (form) {
        form.action = '{{ route("dashboard.moods.store") }}';
        // reset if inputs exist
        try { form.reset(); } catch(e){}
        const methodEl = document.getElementById('mood-form-method');
        if (methodEl) methodEl.value = 'POST';
      }
    }

    function closeMoodModal() {
      const modal = document.getElementById('mood-modal');
      if (!modal) return;
      modal.classList.add('hidden');
    }

    function editMood(id, name, description) {
      const modal = document.getElementById('mood-modal');
      if (!modal) return;
      modal.classList.remove('hidden');
      const title = document.getElementById('mood-modal-title');
      if (title) title.textContent = 'Edit Mood';
      const form = document.getElementById('mood-form');
      if (form) {
        try { form.action = '{{ route("dashboard.moods.update", ":id") }}'.replace(':id', id); } catch(e){}
        const methodEl = document.getElementById('mood-form-method');
        if (methodEl) methodEl.value = 'PUT';
        const nameInput = form.querySelector('input[name="mood_name"]');
        if (nameInput) nameInput.value = name || '';
        const descInput = form.querySelector('textarea[name="description"]');
        if (descInput) descInput.value = description || '';
      }
    }

function showBeforeAfter() {
  const before = document.getElementById('section-before-after');
  const event = document.getElementById('section-per-event');

  if (before) before.classList.remove('hidden');
  if (event) event.classList.add('hidden');
}

function showPerEvent() {
  const before = document.getElementById('section-before-after');
  const event = document.getElementById('section-per-event');

  if (event) event.classList.remove('hidden');
  if (before) before.classList.add('hidden');
}

async function loadBeforeAfterStats() {
  const dateInput = document.getElementById('date-input');
  const date = dateInput?.value;

  if (!date) {
    alert('Silakan pilih tanggal');
    return;
  }

  const beforeBox = document.getElementById('before-stats');
  const afterBox = document.getElementById('after-stats');
  const wrapper = document.getElementById('before-after-results');

  if (beforeBox) beforeBox.innerHTML = `<p class="text-gray-500">Memuat...</p>`;
  if (afterBox) afterBox.innerHTML = `<p class="text-gray-500">Memuat...</p>`;
  if (wrapper) wrapper.classList.remove('hidden');

  try {
    const res = await fetch(`/statistics/before-after?date=${date}`, {
      headers: { Accept: 'application/json' }
    });

    if (!res.ok) throw new Error();

    const data = await res.json();
    renderBeforeAfterStats(data);
  } catch (err) {
    console.error(err);
    alert('Gagal memuat statistik');
  }
}

function renderMoodTable(byMood) {
  if (!byMood || byMood.length === 0) {
    return `<p class="text-sm text-gray-500">Belum ada data mood</p>`;
  }

  let rows = '';
  byMood.forEach(mood => {
    rows += `
      <tr>
        <td class="border px-3 py-2">${mood.mood_name}</td>
        <td class="border px-3 py-2 text-center">${mood.total}</td>
      </tr>
    `;
  });

  return `
    <table class="w-full border mt-4">
      <thead>
        <tr class="bg-gray-100">
          <th class="border px-3 py-2 text-left">Mood</th>
          <th class="border px-3 py-2 text-center">Total Interaksi</th>
        </tr>
      </thead>
      <tbody>
        ${rows}
      </tbody>
    </table>
  `;
}

function renderBeforeAfterStats(data) {
  const wrapper = document.getElementById('before-after-results');
  const beforeBox = document.getElementById('before-stats');
  const afterBox = document.getElementById('after-stats');

  if (!wrapper || !beforeBox || !afterBox) return;

  wrapper.classList.remove('hidden');

  const before = data.before || {};
  const after = data.after || {};

  function statCard(title, value, subtitle = '') {
    return `
      <div class="p-4 bg-white rounded-lg shadow-sm">
        <div class="text-xs text-gray-500">${title}</div>
        <div class="text-2xl font-bold text-gray-900 mt-2">${value}</div>
        ${subtitle ? `<div class="text-xs text-gray-400 mt-1">${subtitle}</div>` : ''}
      </div>
    `;
  }

  beforeBox.innerHTML = `
  <div class="grid grid-cols-1 gap-3">
    ${statCard('Total Interaksi', before.total_interactions ?? 0)}
    ${statCard('User Unik', before.unique_users ?? 0)}
  </div>

  <h4 class="mt-4 font-semibold text-gray-700">Statistik per Mood</h4>
  ${renderMoodTable(before.by_mood)}
`;

  afterBox.innerHTML = `
  <div class="grid grid-cols-1 gap-3">
    ${statCard('Total Interaksi', after.total_interactions ?? 0)}
    ${statCard('User Unik', after.unique_users ?? 0)}
  </div>

  <h4 class="mt-4 font-semibold text-gray-700">Statistik per Mood</h4>
  ${renderMoodTable(after.by_mood)}
`;

  // render comparison chart (single canvas placed below the two boxes)
  let chartContainer = document.getElementById('before-after-chart-container');
  if (!chartContainer) {
    chartContainer = document.createElement('div');
    chartContainer.id = 'before-after-chart-container';
    chartContainer.className = 'mt-4 p-4 bg-white rounded-lg shadow-sm';
    wrapper.appendChild(chartContainer);
  }

  chartContainer.innerHTML = `<canvas id="chart-before-after" style="max-height:260px;"></canvas>`;

  // build dataset
  const labels = ['Total Interaksi', 'User Unik'];
  const beforeVals = [before.total_interactions ?? 0, before.unique_users ?? 0];
  const afterVals = [after.total_interactions ?? 0, after.unique_users ?? 0];

  // destroy previous chart if exists
  if (window.beforeAfterChart) {
    try { window.beforeAfterChart.destroy(); } catch(e){}
  }

  const ctx = document.getElementById('chart-before-after').getContext('2d');
  window.beforeAfterChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [
        { label: 'Sebelum', data: beforeVals, backgroundColor: 'rgba(59,130,246,0.6)' },
        { label: 'Sesudah', data: afterVals, backgroundColor: 'rgba(16,185,129,0.6)' }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'top' } },
      scales: { y: { beginAtZero: true } }
    }
  });
}

function renderEventStats(data) {
  const container = document.getElementById('event-stats-content');
  container.innerHTML = '';

  // ===== LIST SEMUA EVENT =====
  if (data.events && Array.isArray(data.events)) {
    data.events.forEach(item => {
      const id = item.event?.id ?? '';
      const publicVal = item.pay ?? item.public_access ?? item.public_access_count ?? item.public_accesses ?? '';
      container.innerHTML += `
        <div class="border rounded-lg p-4 mb-3 bg-white shadow">
          <h3 class="font-bold text-lg">${item.event.event_name}</h3>
          <p>Total Akses: ${item.total_interactions}</p>
          <p class="text-sm text-gray-500">Akses Publik: ${publicVal}</p>
        </div>
      `;
    });
    return;
  }

  // ===== DETAIL 1 EVENT =====
  const item = data[0];

  container.innerHTML = `
    <div class="bg-white rounded-lg shadow p-4">
      <h3 class="text-xl font-bold mb-2">${item.event.event_name}</h3>
      <p class="mb-2">Total Akses: ${item.total_interactions}</p>

      <h4 class="font-semibold mt-4">Statistik Mood</h4>
      <ul class="list-disc pl-6">
        ${item.by_mood.map(m => `
          <li>${m.mood_name}: ${m.total}</li>
        `).join('')}
      </ul>
    </div>
  `;
}

async function loadEventStats() {
  const select = document.getElementById('event-select');
  const eventId = select?.value;

  const content = document.getElementById('event-stats-content');
  const wrapper = document.getElementById('per-event-results');
  if (content) content.innerHTML = `<p class="text-gray-500">Memuat...</p>`;
  if (wrapper) wrapper.classList.remove('hidden');

  // prefer web route (Blade will produce correct path) then API
  const tryUrls = [];
  const webPerEvent = '{{ route("statistics.per-event") }}';
  const apiPerEvent = '/api/statistics/per-event';

  if (eventId) {
    tryUrls.push(`${webPerEvent}?event_id=${eventId}`);
    tryUrls.push(`${apiPerEvent}?event_id=${eventId}`);
    tryUrls.push(`${webPerEvent}?eventId=${eventId}`);
    tryUrls.push(`${webPerEvent}?id=${eventId}`);
  } else {
    tryUrls.push(webPerEvent);
    tryUrls.push(apiPerEvent);
  }

  let attemptResults = [];
  let lastError = null;
  for (const url of tryUrls) {
    try {
      console.debug('Trying statistics URL:', url);
      const res = await fetch(url, { headers: { Accept: 'application/json' } });
      const text = await res.text();
      // log raw response to console for debugging
      console.debug('Response status', res.status, 'body:', text);
      attemptResults.push({ url, status: res.status, body: text });

      if (!res.ok) {
        lastError = `HTTP ${res.status} for ${url}`;
        continue; // try next
      }

      // try parse JSON
      let data;
      try { data = JSON.parse(text); } catch(e) { data = text; }

      // nothing meaningful? continue
      if ((Array.isArray(data) && data.length === 0) || (!data) || (typeof data === 'string' && data.trim() === '')) {
        lastError = `Empty response for ${url}`;
        continue;
      }

      // got data
      displayEventStats(data);
      return;
    } catch (err) {
      console.error('Error fetching', url, err);
      lastError = err.message || String(err);
      attemptResults.push({ url, error: lastError });
      // try next URL
    }
  }
 
   // All attempts failed
  const errMsg = `Gagal memuat statistik event. Terakhir: ${lastError ?? ''}`;
  console.error(errMsg, attemptResults);
  if (content) {
    let html = `<p class="text-red-500 mb-2">${errMsg}</p>`;
    html += `<div class="text-xs text-gray-500 mb-2">Percobaan:</div><ul class="text-xs text-gray-600 list-disc list-inside">`;
    attemptResults.forEach(a => {
      if (a.status) html += `<li>${a.url} — HTTP ${a.status}</li>`;
      else html += `<li>${a.url} — error: ${a.error}</li>`;
    });
    html += '</ul>';
    content.innerHTML = html;
  }
}

function displayEventStats(data) {
  // ensure we have the target containers in scope
  const wrapper = document.getElementById('per-event-results');
  const content = document.getElementById('event-stats-content');
  if (!wrapper || !content) return;
  wrapper.classList.remove('hidden');
  // clear previous content
  content.innerHTML = '';

  // If backend returned the list shape { events: [...] }, prefer renderEventStats()
  if (data && data.events && Array.isArray(data.events)) {
    if (typeof renderEventStats === 'function') {
      renderEventStats(data);
      return;
    }

    // Inline fallback rendering for events list (if renderEventStats not available)
    let cards = '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">';
    let totalAll = 0;
    data.events.forEach(item => {
      const id = item.event?.id ?? '';
      const name = item.event?.event_name ?? 'Event';
      const total = Number(item.total_interactions) || 0;
      const optionEl = document.querySelector(`#event-select option[value="${id}"]`);
      const optionPay = optionEl ? optionEl.dataset.pay : null;
      const publicVal = item.pay ?? item.public_access ?? item.public_access_count ?? item.public_accesses ?? optionPay ?? '';
      totalAll += total;
      cards += `
        <div class="p-4 bg-white rounded-lg shadow-sm">
          <div class="text-xs text-gray-500">Event</div>
          <div class="text-lg font-semibold text-gray-800 mt-1">${name}</div>
          <div class="text-sm text-gray-400 mt-1">ID: ${id}</div>
          <div class="text-sm text-gray-500 mt-1">Akses Publik: ${publicVal}</div>
          <div class="text-2xl font-bold text-gray-900 mt-3">${total}</div>
          <div class="mt-3">
            <button class="px-3 py-1 bg-blue-600 text-white rounded text-sm" onclick="document.getElementById('event-select').value='${id}'; loadEventStats();">Lihat</button>
          </div>
        </div>
      `;
    });
    cards += '</div>';

    content.innerHTML = `
      <div class="mb-4">
        <div class="p-4 bg-white rounded-lg shadow-sm">
          <div class="text-sm text-gray-500">Total Interaksi (semua event)</div>
          <div class="text-2xl font-bold text-gray-900 mt-2">${totalAll}</div>
        </div>
      </div>
    `;
    content.innerHTML += cards;
    return;
  }

  // Determine event name from select or response
  const select = document.getElementById('event-select');
  const eventName = (select?.selectedOptions && select.selectedOptions[0]?.text) || data.event_name || '';

  // Header
  content.innerHTML += `
    <div class="mb-4">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-lg font-semibold text-gray-800">${eventName ? `Statistik: ${eventName}` : 'Statistik Event'}</h3>
          <p class="text-sm text-gray-500">Ringkasan dan detail untuk event yang dipilih</p>
        </div>
      </div>
    </div>
  `;

  // If backend returned detailed event object with daily_interactions -> render chart + summary
  if (Array.isArray(data.daily_interactions)) {
    // summary cards
    const total = data.total_interactions ?? 0;
    const uniq = data.unique_users ?? 0;
    content.innerHTML += `
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
        <div class="p-4 bg-white rounded-lg shadow-sm">
          <div class="text-xs text-gray-500">Total Interaksi</div>
          <div class="text-2xl font-bold text-gray-900 mt-2">${total}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-sm">
          <div class="text-xs text-gray-500">User Unik</div>
          <div class="text-2xl font-bold text-gray-900 mt-2">${uniq}</div>
        </div>
      </div>
    `;

    // prepare chart data
    const labels = data.daily_interactions.map(r => r.date);
    const values = data.daily_interactions.map(r => Number(r.total));

    content.innerHTML += `<div class="p-4 bg-white rounded-lg shadow-sm"><canvas id="chart-per-event" style="max-height:320px;"></canvas></div>`;
    if (window.perEventChart) try { window.perEventChart.destroy(); } catch(e){}
    const ctx = document.getElementById('chart-per-event').getContext('2d');
    window.perEventChart = new Chart(ctx, {
      type: 'bar',
      data: { labels, datasets: [{ label: eventName || 'Interaksi harian', data: values, backgroundColor: 'rgba(59,130,246,0.6)' }] },
      options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero:true } } }
    });

    return;
  }

  // If response contains a summary object, render as cards
  const summary = data.summary || data.stats || null;
  if (summary && typeof summary === 'object' && !Array.isArray(summary)) {
    let cards = '<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">';
    Object.entries(summary).forEach(([k, v]) => {
      const title = k.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
      cards += `
        <div class="p-4 bg-white rounded-lg shadow-sm">
          <div class="text-xs text-gray-500">${title}</div>
          <div class="text-2xl font-bold text-gray-900 mt-2">${typeof v === 'number' ? v : (v ?? '')}</div>
        </div>
      `;
    });
    cards += '</div>';
    content.innerHTML += cards;
  }

  // If data is chart-friendly (labels & values)
  if (data && Array.isArray(data.labels) && Array.isArray(data.values)) {
    content.innerHTML += `<div class="p-4 bg-white rounded-lg shadow-sm"><canvas id="chart-per-event" style="max-height:320px;"></canvas></div>`;
    if (window.perEventChart) try { window.perEventChart.destroy(); } catch (e) {}
    const ctx = document.getElementById('chart-per-event').getContext('2d');
    window.perEventChart = new Chart(ctx, {
      type: data.type || 'line',
      data: {
        labels: data.labels,
        datasets: [{
          label: data.label || eventName || 'Data',
          data: data.values,
          borderColor: 'rgba(59,130,246,1)',
          backgroundColor: 'rgba(59,130,246,0.12)',
          fill: true,
          pointRadius: 3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: true } },
        scales: { y: { beginAtZero: true } }
      }
    });
    return;
  }

  // If data is an array -> render table
  if (Array.isArray(data)) {
    if (data.length === 0) {
      content.innerHTML = `<p class="text-gray-500">Tidak ada data untuk event ini.</p>`;
      return;
    }

    // Recognize the 'events summary' shape returned when no event_id filter provided
    // expected item: { event: { id, event_name }, total_interactions: N }
    const first = data[0];
    if (first && first.event && (first.total_interactions !== undefined)) {
      // render as cards grid
      let cards = '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">';
      let totalAll = 0;
      data.forEach(item => {
        const id = item.event?.id ?? '';
        const name = item.event?.event_name ?? 'Event';
        const total = Number(item.total_interactions) || 0;
        totalAll += total;
        cards += `
          <div class="p-4 bg-white rounded-lg shadow-sm">
            <div class="text-xs text-gray-500">Event</div>
            <div class="text-lg font-semibold text-gray-800 mt-1">${name}</div>
            <div class="text-sm text-gray-400 mt-1">ID: ${id}</div>
            <div class="text-sm text-gray-500 mt-1">Akses Publik: ${publicVal}</div>
            <div class="text-2xl font-bold text-gray-900 mt-3">${total}</div>
            <div class="mt-3">
              <button class="px-3 py-1 bg-blue-600 text-white rounded text-sm" onclick="document.getElementById('event-select').value='${id}'; loadEventStats();">Lihat</button>
            </div>
          </div>
        `;
      });
      cards += '</div>';

      // show aggregate summary on top
      content.innerHTML = `
        <div class="mb-4">
          <div class="p-4 bg-white rounded-lg shadow-sm">
            <div class="text-sm text-gray-500">Total Interaksi (semua event)</div>
            <div class="text-2xl font-bold text-gray-900 mt-2">${totalAll}</div>
          </div>
        </div>
      `;
      content.innerHTML += cards;
      return;
    }

    // Fallback to table for generic arrays
    const keys = Object.keys(data[0]);
    let table = `<div class="overflow-x-auto bg-white rounded-lg shadow-sm"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr>`;
    keys.forEach(k => table += `<th class="px-4 py-2 text-left text-xs font-medium text-gray-500">${k}</th>`);
    table += `</tr></thead><tbody class="bg-white divide-y divide-gray-100">`;

    data.forEach(row => {
      table += '<tr>';
      keys.forEach(k => table += `<td class="px-4 py-3 text-sm text-gray-700">${typeof row[k] === 'object' ? JSON.stringify(row[k]) : (row[k] ?? '')}</td>`);
      table += '</tr>';
    });

    table += '</tbody></table></div>';
    content.innerHTML = table;
    return;
  }
  
  // Fallback: raw JSON
  content.innerHTML += `<pre class="text-sm text-gray-700 whitespace-pre-wrap">${JSON.stringify(data, null, 2)}</pre>`;
}

window.onclick = function (e) {
  if (e.target === document.getElementById('menu-modal')) closeMenuModal();
  if (e.target === document.getElementById('category-modal')) closeCategoryModal();
  if (e.target === document.getElementById('mood-modal')) closeMoodModal();
};

document.addEventListener('DOMContentLoaded', () => {
  try {
    showPage(currentPage || 1);
  } catch {}
});
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
