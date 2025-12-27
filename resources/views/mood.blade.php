<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $mood->mood_name }} - MoodFood</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-start justify-between mb-4">
      <div>
        <div class="flex items-center space-x-4">
          <div class="text-4xl">@php
            $emojiMap = [
              'senang' => '😊',
              'sedih' => '😥',
              'stress' => '😖',
              'lelah' => '😴',
              'biasa aja' => '😐',
              'excited' => '🤩',
            ];
            $key = strtolower($mood->mood_name);
          @endphp
          {{ $emojiMap[$key] ?? '🙂' }}</div>
          <div>
            <h1 class="text-3xl font-bold">{{ $mood->mood_name }}</h1>
            @if($mood->description)
              <p class="text-sm text-gray-600 mt-1">{{ $mood->description }}</p>
            @endif
            <div class="text-xs text-gray-500 mt-1">Kategori: {{ $categories->count() ?? 0 }} • Menu: {{ $menus->count() ?? 0 }}</div>
          </div>
        </div>
      </div>
      <div class="flex items-center space-x-2">
        <a href="{{ route('home') }}" class="px-3 py-2 bg-gray-100 rounded">Kembali</a>
      </div>
    </div>

    <!-- Categories Filter -->
    @if(isset($categories) && $categories->count())
      <div class="mb-4 flex flex-wrap gap-2">
        <button data-category="all" class="category-chip px-3 py-1 bg-gray-100 rounded text-sm text-gray-700 active">Semua</button>
        @foreach($categories as $cat)
          <button data-category="{{ $cat->id }}" class="category-chip px-3 py-1 bg-gray-50 border rounded text-sm text-gray-700">{{ $cat->category_name }}</button>
        @endforeach
      </div>
    @endif

    <!-- Search -->
    <div class="mb-6">
      <input id="menu-search" type="search" placeholder="Cari menu atau tenant..." class="w-full border rounded px-3 py-2" />
    </div>

    <!-- Popular (small showcase) -->
    @if(isset($popular) && $popular->count())
      <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Popular untuk "{{ $mood->mood_name }}"</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
          @foreach($popular as $p)
            <div class="bg-white rounded-lg shadow p-3 text-center">
              <div class="text-sm font-semibold">{{ Str::limit($p->menu_name, 26) }}</div>
              <div class="text-xs text-gray-500 mt-1">Rp {{ number_format($p->price ?? 0, 0, ',', '.') }}</div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- Menus Grid -->
    <div id="menus-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      @forelse($menus as $menu)
        <div class="bg-white rounded-lg shadow p-4 flex flex-col menu-card" data-category-id="{{ $menu->category_id }}" data-search-text="{{ strtolower($menu->menu_name . ' ' . ($menu->tenant->tenant_name ?? '') . ' ' . ($menu->category->category_name ?? '')) }}">
          <div class="flex items-start space-x-4">
            <img src="{{ $menu->image ?? asset('img/placeholder.png') }}" alt="{{ $menu->menu_name }}" class="w-20 h-20 object-cover rounded" />
            <div class="flex-1">
              <h3 class="font-semibold">{{ $menu->menu_name }}</h3>
              <p class="text-sm text-gray-500">{{ $menu->tenant->tenant_name ?? 'Tenant' }} • {{ $menu->category->category_name ?? '' }}</p>
              <p class="text-sm text-gray-700 mt-2">Rp {{ number_format($menu->price ?? 0, 0, ',', '.') }}</p>
            </div>
          </div>
          <div class="mt-4 flex justify-between items-center">
            <button data-menu-id="{{ $menu->id }}" data-mood-id="{{ $mood->id }}" class="select-btn px-3 py-2 bg-lime-500 text-white rounded">Pilih</button>
            <a href="#" class="text-sm text-gray-500">Lihat detail</a>
          </div>
        </div>
      @empty
        <div class="col-span-full text-center text-gray-500 py-8">Belum ada menu untuk mood ini.</div>
      @endforelse
    </div>

    <div id="no-results" class="hidden text-center text-gray-500 py-6">Tidak ditemukan menu yang sesuai.</div>
  </div>

  <script>
    document.getElementById('menu-search').addEventListener('input', function(e){
      const q = e.target.value.toLowerCase();
      document.querySelectorAll('#menus-grid > div').forEach(card => {
        const text = card.innerText.toLowerCase();
        card.style.display = text.includes(q) ? '' : 'none';
      });
    });

    (function(){
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      // Category filter
      let activeCategory = 'all';
      document.querySelectorAll('.category-chip').forEach(btn => {
        btn.addEventListener('click', function(){
          document.querySelectorAll('.category-chip').forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          activeCategory = this.dataset.category;
          applyFilters();
        });
      });

      // Search
      const searchInput = document.getElementById('menu-search');
      searchInput.addEventListener('input', applyFilters);

      function applyFilters(){
        const q = (searchInput.value || '').toLowerCase().trim();
        let shown = 0;
        document.querySelectorAll('.menu-card').forEach(card => {
          const text = card.dataset.searchText || '';
          const cat = String(card.dataset.categoryId || '');
          const catMatch = (activeCategory === 'all') || (cat === activeCategory);
          const textMatch = q === '' || text.indexOf(q) !== -1;
          if (catMatch && textMatch) {
            card.style.display = '';
            shown++;
          } else {
            card.style.display = 'none';
          }
        });
        document.getElementById('no-results').classList.toggle('hidden', shown > 0);
      }

      // Select buttons
      document.querySelectorAll('.select-btn').forEach(btn => {
        btn.addEventListener('click', function(){
          const menuId = this.dataset.menuId;
          const moodId = this.dataset.moodId;
          this.disabled = true;
          this.innerText = 'Memproses...';
          fetch('/interactions/public', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ menu_id: menuId, mood_id: moodId })
          }).then(r => r.json()).then(data => {
            this.innerText = 'Dipilih ✓';
            setTimeout(() => { this.innerText = 'Pilih'; this.disabled = false; }, 1500);
            // small toast
            showToast('Interaksi tersimpan. Terima kasih!');
          }).catch(err => {
            console.error(err);
            this.innerText = 'Pilih';
            this.disabled = false;
            showToast('Gagal menyimpan interaksi', true);
          });
        });
      });

      function showToast(message, isError = false){
        const el = document.createElement('div');
        el.className = 'fixed bottom-6 right-6 bg-white px-4 py-2 rounded shadow-lg';
        el.style.zIndex = 9999;
        el.innerText = message;
        if (isError) el.classList.add('text-red-600'); else el.classList.add('text-gray-800');
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2500);
      }
    })();
  </script>
</body>
</html>
