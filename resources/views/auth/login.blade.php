<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Login - MoodFood</title>
</head>
<body class="bg-gradient-to-br from-lime-50 to-lime-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
    <div class="text-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800 mb-2">MOOD FOOD</h1>
      <p class="text-gray-600">Silakan login untuk melanjutkan</p>
    </div>

    @if(session('error'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
      </div>
    @endif

    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input type="email" 
               id="email" 
               name="email" 
               value="{{ old('email') }}"
               required 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lime-500 focus:border-transparent"
               placeholder="nama@email.com">
      </div>

      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input type="password" 
               id="password" 
               name="password" 
               required 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lime-500 focus:border-transparent"
               placeholder="••••••••">
      </div>

      <div class="mb-6 flex items-center">
        <input type="checkbox" 
               id="remember" 
               name="remember" 
               class="h-4 w-4 text-lime-600 focus:ring-lime-500 border-gray-300 rounded">
        <label for="remember" class="ml-2 block text-sm text-gray-700">
          Ingat saya
        </label>
      </div>

      <button type="submit" 
              class="w-full bg-gradient-to-r from-lime-400 to-lime-500 text-white font-semibold py-3 rounded-lg hover:from-lime-500 hover:to-lime-600 transition shadow-lg">
        Login
      </button>
    </form>

    <div class="mt-6 text-center">
      <a href="{{ route('landing') }}" class="text-sm text-gray-600 hover:text-gray-800">
        ← Kembali ke halaman utama
      </a>
    </div>

    <div class="mt-4 text-center text-xs text-gray-500">
      <p>Belum punya akun? Hubungi administrator untuk registrasi.</p>
    </div>
  </div>
</body>
</html>
