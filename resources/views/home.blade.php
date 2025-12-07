<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Home - MoodFood</title>
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
    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
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
    .mood-card:active {
      transform: translateY(-4px) scale(1.02);
    }
    .nav-btn {
      transition: all 0.3s ease;
    }
    .nav-btn:hover {
      transform: scale(1.2);
      opacity: 0.7;
    }
    .nav-btn:active {
      transform: scale(0.95);
    }
    .bottom-nav-item {
      transition: all 0.3s ease;
      cursor: pointer;
    }
    .bottom-nav-item:hover {
      transform: translateY(-5px);
    }
    .bottom-nav-item:active {
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
    @media (max-width: 640px) {
      .mood-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
      }
    }
  </style>
</head>
<body data-page="1" class="bg-gradient-to-br from-white via-gray-50 to-gray-100 min-h-screen flex flex-col items-center relative overflow-x-hidden">

  <!-- Top Navigation Arrows -->
  <div class="absolute top-4 right-4 z-10 flex space-x-4">
    <button onclick="window.location.href='{{ route('landing') }}'" 
      class="nav-btn text-gray-700 hover:text-gray-900 text-xl sm:text-2xl font-bold p-2 rounded-full hover:bg-gray-200">
      ☜
    </button>
    <button onclick="window.location.href='{{ route('home') }}'" 
      class="nav-btn text-gray-700 hover:text-gray-900 text-xl sm:text-2xl font-bold p-2 rounded-full hover:bg-gray-200">
      ☞
    </button>
  </div>

  <!-- Welcome Banner -->
  <div class="mt-16 sm:mt-20 md:mt-24 flex items-center bg-gradient-to-r from-lime-300 to-lime-400 px-4 sm:px-6 md:px-8 py-4 sm:py-5 rounded-xl shadow-lg space-x-3 sm:space-x-4 mx-4 sm:mx-0 w-[calc(100%-2rem)] sm:w-auto max-w-md animate-slideUp">
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
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-3 sm:gap-4 mt-6 sm:mt-8 px-4 w-full max-w-2xl">
    <a href="{{ route('mood.senang') }}" class="mood-card flex flex-col items-center bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl px-4 sm:px-6 py-4 sm:py-5 shadow-md hover:shadow-xl">
      <p class="text-2xl sm:text-3xl md:text-4xl mb-2">😊</p>
      <span class="font-semibold text-xs sm:text-sm md:text-base">Senang</span>
    </a>
    <a href="{{ route('mood.sedih') }}" class="mood-card flex flex-col items-center bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl px-4 sm:px-6 py-4 sm:py-5 shadow-md hover:shadow-xl">
      <p class="text-2xl sm:text-3xl md:text-4xl mb-2">😔</p>
      <span class="font-semibold text-xs sm:text-sm md:text-base">Sedih</span>
    </a>
    <a href="{{ route('mood.stress') }}" class="mood-card flex flex-col items-center bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl px-4 sm:px-6 py-4 sm:py-5 shadow-md hover:shadow-xl">
      <p class="text-2xl sm:text-3xl md:text-4xl mb-2">🤯</p>
      <span class="font-semibold text-xs sm:text-sm md:text-base">Stress</span>
    </a>
    <a href="{{ route('mood.lelah') }}" class="mood-card flex flex-col items-center bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl px-4 sm:px-6 py-4 sm:py-5 shadow-md hover:shadow-xl">
      <p class="text-2xl sm:text-3xl md:text-4xl mb-2">😴</p>
      <span class="font-semibold text-xs sm:text-sm md:text-base">Lelah</span>
    </a>
    <a href="{{ route('mood.biasa-aja') }}" class="mood-card flex flex-col items-center bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl px-4 sm:px-6 py-4 sm:py-5 shadow-md hover:shadow-xl">
      <p class="text-2xl sm:text-3xl md:text-4xl mb-2">😐</p>
      <span class="font-semibold text-xs sm:text-sm md:text-base">Biasa Aja</span>
    </a>
    <a href="{{ route('mood.excited') }}" class="mood-card flex flex-col items-center bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl px-4 sm:px-6 py-4 sm:py-5 shadow-md hover:shadow-xl">
      <p class="text-2xl sm:text-3xl md:text-4xl mb-2">🤩</p>
      <span class="font-semibold text-xs sm:text-sm md:text-base">Excited</span>
    </a>
  </div>

  <!-- Statistik Card -->
  <div class="stat-card bg-gradient-to-br from-gray-100 to-gray-200 px-6 sm:px-8 py-4 sm:py-5 rounded-xl shadow-md mt-6 sm:mt-8 text-center mx-4 sm:mx-0 w-[calc(100%-2rem)] sm:w-auto max-w-md animate-fadeIn" style="animation-delay: 0.4s;">
    <p class="font-semibold text-sm sm:text-base mb-3 sm:mb-4 text-gray-700">📊 Statistik Hari Ini</p>
    <div class="flex justify-center space-x-6 sm:space-x-8 text-xs sm:text-sm font-semibold">
      <span class="text-red-500">
        <span class="block text-lg sm:text-xl md:text-2xl font-bold">324</span>
        <span class="text-xs sm:text-sm">Pengguna</span>
      </span>
      <span class="text-blue-600">
        <span class="block text-lg sm:text-xl md:text-2xl font-bold">15</span>
        <span class="text-xs sm:text-sm">Tenant</span>
      </span>
      <span class="text-green-600">
        <span class="block text-lg sm:text-xl md:text-2xl font-bold">4.8</span>
        <span class="text-xs sm:text-sm">Rating</span>
      </span>
    </div>
  </div>

  <!-- Bottom Navigation -->
  <div class="mt-auto py-6 sm:py-8 flex justify-center space-x-8 sm:space-x-10 md:space-x-12 text-center">
    <div class="bottom-nav-item">
      <p class="text-xl sm:text-2xl md:text-3xl mb-1">🏛️</p>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Home</span>
    </div>
    <div class="bottom-nav-item">
      <p class="text-xl sm:text-2xl md:text-3xl mb-1">🔍</p>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Cari</span>
    </div>
    <div class="bottom-nav-item">
      <p class="text-xl sm:text-2xl md:text-3xl mb-1">🛍️</p>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Order</span>
    </div>
    <div class="bottom-nav-item">
      <p class="text-xl sm:text-2xl md:text-3xl mb-1">👤</p>
      <span class="text-xs sm:text-sm text-gray-600 font-medium">Profil</span>
    </div>
  </div>

  <!-- Page Indicator -->
  <div class="absolute bottom-4 sm:bottom-6 w-full flex flex-col items-center">
    <p class="text-xs sm:text-sm text-gray-500 mb-2"><b>Home Screen</b></p>
    <div class="flex justify-center space-x-2 mb-4 sm:mb-6">
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
    </div>
  </div>

  <script>
    (function () {
      const page = parseInt(document.body.dataset.page || '0', 10);
      const dots = document.querySelectorAll('.dot');
      dots.forEach((dot, idx) => {
        dot.classList.toggle('active', idx === page);
      });
      
      // Mood cards are now links, no need for click handlers
    })();
  </script>

</body>
</html>