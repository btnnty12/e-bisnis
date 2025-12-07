<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>MoodFood - Splash Screen</title>
  <style>
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    @keyframes scaleIn {
      from {
        opacity: 0;
        transform: scale(0.8);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }
    @keyframes pop {
      0% { transform: scale(1); }
      50% { transform: scale(1.6); }
      100% { transform: scale(1.35); }
    }
    .animate-fadeInUp {
      animation: fadeInUp 0.6s ease-out;
    }
    .animate-scaleIn {
      animation: scaleIn 0.8s ease-out;
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
    .food-image {
      transition: transform 0.5s ease;
    }
    .food-image:hover {
      transform: scale(1.05) rotate(2deg);
    }
    @media (max-width: 640px) {
      .food-image {
        width: 180px;
        height: 180px;
      }
    }
  </style>
</head>

<body data-page="0" class="bg-gradient-to-br from-white via-gray-50 to-gray-100 min-h-screen flex flex-col overflow-x-hidden">

  <!-- Top Navigation -->
  <div class="absolute top-4 right-4 z-10 flex space-x-4">
    <div class="flex space-x-4 sm:space-x-6">
      <button onclick="window.location.href='{{ route('landing') }}'"
        class="nav-btn text-gray-600 hover:text-gray-800 text-2xl sm:text-3xl font-bold p-2 rounded-full hover:bg-gray-200">
        ☜
      </button>
      <button onclick="window.location.href='{{ route('home') }}'"
        class="nav-btn text-gray-600 hover:text-gray-800 text-2xl sm:text-3xl font-bold p-2 rounded-full hover:bg-gray-200">
        ☞
      </button>
    </div>
  </div>

  <!-- Konten Tengah -->
  <div class="flex flex-col justify-center items-center flex-grow px-4 py-8 sm:py-12">
    <!-- Gambar Makanan Bulat -->
    <div class="food-image w-40 h-40 sm:w-52 sm:h-52 md:w-64 md:h-64 rounded-full overflow-hidden shadow-lg mb-6 sm:mb-8 animate-scaleIn">
      <img src="{{ asset('img/image 15.jpg') }}"
        alt="MoodFood"
        class="w-full h-full object-cover">
    </div>

    <!-- MoodFood Pill -->
    <div class="px-6 py-2 sm:px-8 sm:py-3 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full text-gray-700 font-semibold text-base sm:text-lg md:text-xl mb-3 shadow-md hover:shadow-lg transition-shadow duration-300 animate-fadeInUp">
      MoodFood
    </div>

    <!-- Mall Name -->
    <p class="text-gray-600 text-sm sm:text-base md:text-lg mb-2 font-medium animate-fadeInUp" style="animation-delay: 0.2s;">
      Mall Citra Land
    </p>

    <!-- Splash Screen Label -->
    <p class="text-gray-500 text-xs sm:text-sm italic mb-8 sm:mb-10 animate-fadeInUp" style="animation-delay: 0.4s;">
      Splash Screen
    </p>
  </div>

  <!-- Page Indicator -->
  <div class="flex justify-center space-x-2 mb-6 sm:mb-8 pb-4">
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
  </div>

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