<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes" />
    <title>MoodFood – Rekomendasi Senang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
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
        .menu-card {
            transition: all 0.3s ease;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .btn-pesan {
            transition: all 0.3s ease;
        }
        .btn-pesan:hover {
            transform: scale(1.05);
            background-color: #333;
        }
        .btn-pesan:active {
            transform: scale(0.95);
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
    </style>
</head>
<body data-page="2" class="bg-gradient-to-br from-white via-yellow-50 to-orange-50 min-h-screen font-sans">

    <!-- Top Navigation -->
    <div class="max-w-2xl mx-auto mt-4 sm:mt-6 px-4">
        <div class="flex items-center gap-3 sm:gap-4 bg-gradient-to-r from-yellow-300 via-yellow-400 to-orange-300 p-4 sm:p-5 rounded-2xl shadow-lg animate-slideUp">
            <a href="{{ route('home') }}" class="text-black text-xl sm:text-2xl hover:scale-110 transition-transform">&#8592;</a>
            <div class="flex-1">
                <p class="font-semibold text-base sm:text-lg md:text-xl">Mood: Senang 😄</p>
                <p class="text-xs sm:text-sm text-black/70 -mt-1">Rekomendasi makanan yang membuatmu lebih bahagia!</p>
            </div>
        </div>
    </div>

    <!-- Menu List -->
    <div class="max-w-2xl mx-auto mt-4 sm:mt-6 px-4 space-y-3 sm:space-y-4 pb-20">

        <!-- Item Card -->
        <div class="menu-card flex items-center bg-white rounded-2xl p-3 sm:p-4 shadow-md animate-fadeIn" style="animation-delay: 0.1s;">
            <img src="https://via.placeholder.com/70" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover flex-shrink-0" />
            <div class="flex-1 ml-3 sm:ml-4 min-w-0">
                <p class="font-semibold text-sm sm:text-base">Rainbow Sushi Roll</p>
                <p class="text-xs sm:text-sm text-gray-500 -mt-1">Sushi Tei</p>
                <div class="flex items-center text-xs text-gray-600 mt-1">
                    ⭐ 4.8 &nbsp; ⏱ 15 min
                </div>
            </div>
            <div class="flex flex-col items-end gap-2 ml-2">
                <p class="font-semibold text-xs sm:text-sm text-gray-800">Rp 85.000</p>
                <button class="btn-pesan bg-black text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-sm font-medium">Pesan</button>
            </div>
        </div>

        <!-- Item Card -->
        <div class="menu-card flex items-center bg-white rounded-2xl p-3 sm:p-4 shadow-md animate-fadeIn" style="animation-delay: 0.2s;">
            <img src="https://via.placeholder.com/70" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover flex-shrink-0" />
            <div class="flex-1 ml-3 sm:ml-4 min-w-0">
                <p class="font-semibold text-sm sm:text-base">Tropical Smoothie Bowl</p>
                <p class="text-xs sm:text-sm text-gray-500 -mt-1">Healthy Corner</p>
                <div class="flex items-center text-xs text-gray-600 mt-1">
                    ⭐ 4.7 &nbsp; ⏱ 10 min
                </div>
            </div>
            <div class="flex flex-col items-end gap-2 ml-2">
                <p class="font-semibold text-xs sm:text-sm text-gray-800">Rp 55.000</p>
                <button class="btn-pesan bg-black text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-sm font-medium">Pesan</button>
            </div>
        </div>

        <!-- Item Card -->
        <div class="menu-card flex items-center bg-white rounded-2xl p-3 sm:p-4 shadow-md animate-fadeIn" style="animation-delay: 0.3s;">
            <img src="https://via.placeholder.com/70" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover flex-shrink-0" />
            <div class="flex-1 ml-3 sm:ml-4 min-w-0">
                <p class="font-semibold text-sm sm:text-base">Mango Sticky Rice</p>
                <p class="text-xs sm:text-sm text-gray-500 -mt-1">Thai Express</p>
                <div class="flex items-center text-xs text-gray-600 mt-1">
                    ⭐ 4.6 &nbsp; ⏱ 12 min
                </div>
            </div>
            <div class="flex flex-col items-end gap-2 ml-2">
                <p class="font-semibold text-xs sm:text-sm text-gray-800">Rp 45.000</p>
                <button class="btn-pesan bg-black text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-sm font-medium">Pesan</button>
            </div>
        </div>

        <!-- Item Card -->
        <div class="menu-card flex items-center bg-white rounded-2xl p-3 sm:p-4 shadow-md animate-fadeIn" style="animation-delay: 0.4s;">
            <img src="https://via.placeholder.com/70" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover flex-shrink-0" />
            <div class="flex-1 ml-3 sm:ml-4 min-w-0">
                <p class="font-semibold text-sm sm:text-base">Strawberry Cake</p>
                <p class="text-xs sm:text-sm text-gray-500 -mt-1">Sweet Corner</p>
                <div class="flex items-center text-xs text-gray-600 mt-1">
                    ⭐ 4.9 &nbsp; ⏱ 8 min
                </div>
            </div>
            <div class="flex flex-col items-end gap-2 ml-2">
                <p class="font-semibold text-xs sm:text-sm text-gray-800">Rp 48.000</p>
                <button class="btn-pesan bg-black text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-xs sm:text-sm font-medium">Pesan</button>
            </div>
        </div>
    </div>

    <!-- Location -->
    <div class="max-w-2xl mx-auto mt-6 sm:mt-8 px-4">
        <div class="bg-white p-4 sm:p-5 rounded-2xl text-center shadow-md animate-fadeIn" style="animation-delay: 0.5s;">
            <p class="font-semibold text-sm sm:text-base">📍 Lokasi Food Court</p>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Lantai 3, Mall Citra Land Jakarta Barat</p>
        </div>
    </div>

    <!-- Page Indicator -->
    <div class="max-w-2xl mx-auto mt-4 sm:mt-6 px-4 text-center pb-6">
        <p class="text-xs sm:text-sm font-semibold mb-2 text-gray-600">Recommendation List</p>
        <div class="flex justify-center items-center gap-2">
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
        })();
    </script>

</body>
</html>