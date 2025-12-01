<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Home - MoodFood</title>
</head>
<body data-page="1" class="bg-white h-screen flex flex-col items-center relative">

  <!-- Top Navigation Arrows -->
  <div class="absolute top-4 right-4 flex space-x-4">
    <button onclick="window.location.href='index.html'" class="text-gray-700 text-2xl font-bold">☜</button>
    <button onclick="window.location.href='home.html'" class="text-gray-700 text-2xl font-bold">☞</button>
  </div>

  <!-- Welcome Banner -->
  <div class="mt-20 flex items-center bg-lime-300 px-6 py-4 rounded-xl shadow-sm space-x-4">
    <div>
      <p class="text-sm font-semibold">Selamat datang di</p>
      <h1 class="font-extrabold text-lg">MOOD FOOD</h1>
      <p class="text-xs">Mall Citra Land, Jakarta Barat</p>
    </div>
    <div class="text-3xl">👤</div>
  </div>

  <!-- Question -->
  <div class="mt-6 text-center">
    <p class="font-semibold text-gray-700 text-sm">Apa mood kamu hari ini?</p>
    <p class="text-gray-500 text-xs">Pilih mood dan kami akan merekomendasikan makanan terbaik!</p>
  </div>

  <!-- Mood Options -->
  <div class="grid grid-cols-2 gap-4 mt-6">
    <div class="flex flex-col items-center bg-gray-200 rounded-xl px-6 py-3 shadow-sm">
      <p class="text-3xl">😊</p><span class="font-semibold text-sm">Senang</span>
    </div>
    <div class="flex flex-col items-center bg-gray-200 rounded-xl px-6 py-3 shadow-sm">
      <p class="text-3xl">😔</p><span class="font-semibold text-sm">Sedih</span>
    </div>
    <div class="flex flex-col items-center bg-gray-200 rounded-xl px-6 py-3 shadow-sm">
      <p class="text-3xl">🤯</p><span class="font-semibold text-sm">Stress</span>
    </div>
    <div class="flex flex-col items-center bg-gray-200 rounded-xl px-6 py-3 shadow-sm">
      <p class="text-3xl">😴</p><span class="font-semibold text-sm">Lelah</span>
    </div>
    <div class="flex flex-col items-center bg-gray-200 rounded-xl px-6 py-3 shadow-sm">
      <p class="text-3xl">😐</p><span class="font-semibold text-sm">Biasa Aja</span>
    </div>
    <div class="flex flex-col items-center bg-gray-200 rounded-xl px-6 py-3 shadow-sm">
      <p class="text-3xl">🤩</p><span class="font-semibold text-sm">Excited</span>
    </div>
  </div>

  <!-- Statistik Card -->
  <div class="bg-gray-200 px-6 py-4 rounded-xl shadow-sm mt-8 text-center">
    <p class="font-semibold text-sm mb-2">📊 Statistik Hari Ini</p>
    <div class="flex justify-center space-x-6 text-xs font-semibold">
      <span class="text-red-500">324<br>Pengguna</span>
      <span class="text-blue-600">15<br>Tenant</span>
      <span class="text-green-600">4.8<br>Rating</span>
    </div>
  </div>

  <!-- Bottom Navigation -->
  <div class=" py-8 flex space-x-10 text-center">
    <div>
      <p class="text-2xl">🏛️</p><span class="text-xs">Home</span>
    </div>
    <div>
      <p class="text-2xl">🔍</p><span class="text-xs">Cari</span>
    </div>
    <div>
      <p class="text-2xl">🛍️</p><span class="text-xs">Order</span>
    </div>
    <div>
      <p class="text-2xl">👤</p><span class="text-xs">Profil</span>
    </div>
  </div>

  <!-- Page Indicator -->
  <div class="absolute bottom-6 w-full flex flex-col items-center">
  <p class="text-xs text-gray-500 mb-2"><b>Home Screen</b></p>
  <div class="flex justify-center space-x-2 mb-6">
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
  </div>
  </div>

  <style>
    .dot { width: 10px; height: 10px; border-radius: 9999px; background:#d1d5db; transition: all 0.35s ease; }

    .dot.active {
    background: #111;
    transform: scale(1.35);
    opacity: 1;
    animation: pop 0.35s ease; 
    }

    @keyframes pop {
    0% { transform: scale(1); }
    50% { transform: scale(1.6); }
    100% { transform: scale(1.35); }
    }
  </style>

  <script>
    (function () {
      const page = parseInt(document.body.dataset.page || '0', 10);
      const dots = document.querySelectorAll('.dot');
      dots.forEach((dot, idx) => {
        dot.classList.toggle('active', idx === page);
      });
    })();
  </script>

</body>
</html>