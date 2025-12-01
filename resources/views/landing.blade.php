<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Splash Screen</title>
</head>

   <body data-page="0" class="bg-white h-screen flex flex-col">

 <!--top navigation-->
  <div class="absolute top-4 right-4 flex space-x-4">
  <div class="flex space-x-6">
    <button onclick="window.location.href='index.html'"
      class="text-gray-600 text-3xl font-bold">☜</button>
    <button onclick="window.location.href='home.html'"
      class="text-gray-600 text-3xl font-bold">☞</button>
  </div>
  </div>

  <!-- Konten Tengah -->
  <div class="flex flex-col justify-center items-center flex-grow">

    <!-- Gambar Makanan Bulat -->
    <div class="w-52 h-52 rounded-full overflow-hidden shadow-md mb-6">
      <img src="/Users/ratukemala/Library/CloudStorage/OneDrive-Bisnis/Hidup trs berjalan/SMS/Semester 5/E-Bisnis/mood recomendations/image 15.jpg"
        alt="MoodFood"
        class="w-full h-full object-cover">
    </div>

    <!-- MoodFood Pill -->
    <div class="px-6 py-2 bg-gray-200 rounded-full text-gray-700 font-semibold text-lg mb-2">
      MoodFood
    </div>

    <!-- Mall Name -->
    <p class="text-gray-600 text-base mb-2">
      Mall Citra Land
    </p>

    <!-- Splash Screen Label -->
    <p class="text-gray-500 text-xs italic mb-10">
      Splash Screen
    </p>
  </div>

  <div class="flex justify-center space-x-2 mb-6">
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
  </div>

  <style>
    .dot { width: 10px; height: 10px; border-radius: 9999px; background:#d1d5db; transition: all 0.35s ease; }
    .dot.active { background: #111; transform: scale(1.35); opacity: 1; animation: pop 0.35s ease; }
    @keyframes pop { 0% { transform: scale(1); } 50% { transform: scale(1.6); } 100% { transform: scale(1.35); }}
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