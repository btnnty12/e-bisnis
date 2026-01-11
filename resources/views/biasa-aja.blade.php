<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Biasa Aja - MoodFood</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
  @php
    // If view not rendered via route with variables, load mood data for 'Biasa Aja'
    if (! isset($mood)) {
        $mood = \App\Models\Mood::whereRaw('LOWER(mood_name) = ?', ['biasa aja'])->first();
        if ($mood) {
            $menus = $menus ?? \App\Models\Menu::whereHas('category', fn($q) => $q->where('mood_id', $mood->id))->with(['tenant','category'])->latest()->get();
            $categories = $categories ?? \App\Models\Category::where('mood_id', $mood->id)->orderBy('category_name')->get();
            $popular = $popular ?? $menus->take(6);
        } else {
            $menus = $menus ?? collect();
            $categories = $categories ?? collect();
            $popular = $popular ?? collect();
        }
    }
  @endphp

  <div class="max-w-4xl mx-auto px-4 py-8">

   <!-- HEADER -->
<div class="flex items-start justify-between mb-4">
  <div class="flex items-center space-x-4">
    <div class="text-4xl">😐</div>

    <div>
      <h1 class="text-3xl font-bold">Biasa Aja</h1>
      <p class="text-sm text-gray-600 mt-1">Pilihan makanan klasik yang selalu enak</p>
      <div class="text-xs text-gray-500 mt-1">
        Kategori: {{ $categories->count() ?? 0 }} • Menu: {{ $menus->count() ?? 0 }}
      </div>
    </div>
  </div>

  <a href="{{ route('home') }}" class="px-3 py-2 bg-gray-100 rounded">Kembali</a>
</div>

    <!-- CATEGORY FILTER -->
    @if($categories->count())
      <div class="mb-4 flex flex-wrap gap-2">
        <button
          data-category="all"
          class="category-chip px-3 py-1 rounded text-sm bg-gray-100 text-gray-700
                 hover:bg-lime-100 hover:text-lime-700 transition active">
          Semua
        </button>

        @foreach($categories as $cat)
          <button
            data-category="{{ $cat->id }}"
            class="category-chip px-3 py-1 rounded text-sm bg-gray-50 text-gray-700 border
                   hover:bg-lime-100 hover:text-lime-700 transition">
            {{ $cat->category_name }}
          </button>
        @endforeach
      </div>
    @endif

    <!-- SEARCH -->
    <div class="mb-6">
      <input
        id="menu-search"
        type="search"
        placeholder="Cari menu atau tenant..."
        class="w-full border rounded px-3 py-2"
      />
    </div>

    <!-- POPULAR -->
    @if($popular->count())
      <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">
          Popular untuk "Biasa Aja"
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
          @foreach($popular as $p)
            <div
              class="popular-btn bg-white rounded-lg shadow p-3 text-center cursor-pointer"
              data-name="{{ $p->menu_name }}"
              data-desc="{{ $p->description ?? 'Tidak ada deskripsi menu' }}"
              data-price="{{ number_format($p->price ?? 0,0,',','.') }}"
              data-tenant="{{ $p->tenant->tenant_name ?? '-' }}"
              data-location="{{ $p->tenant->location ?? '-' }}"
            >
              <div class="text-sm font-semibold">
                {{ Str::limit($p->menu_name, 26) }}
              </div>
              <div class="text-xs text-gray-500 mt-1">
                Rp {{ number_format($p->price ?? 0,0,',','.') }}
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- MENU GRID -->
    <div id="menus-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      @forelse($menus as $menu)
        <div
          class="menu-card bg-white rounded-lg shadow p-4 flex flex-col"
          data-category-id="{{ $menu->category_id }}"
          data-search-text="{{ strtolower($menu->menu_name.' '.($menu->tenant->tenant_name ?? '').' '.($menu->category->category_name ?? '')) }}"
        >
          <div class="flex items-start space-x-4">
            <img
              src="{{ $menu->image ?? asset('img/placeholder.png') }}"
              class="w-20 h-20 object-cover rounded"
            />

            <div class="flex-1">
              <h3 class="font-semibold">{{ $menu->menu_name }}</h3>
              <p class="text-sm text-gray-500">
                {{ $menu->tenant->tenant_name ?? 'Tenant' }} • {{ $menu->category->category_name ?? '' }}
              </p>
              <p class="text-sm text-gray-700 mt-2">
                Rp {{ number_format($menu->price ?? 0,0,',','.') }}
              </p>
            </div>
          </div>

          <div class="mt-4 flex justify-between items-center">
            <button
              data-menu-id="{{ $menu->id }}"
              data-mood-id="{{ $mood->id ?? 0 }}"
              class="select-btn px-3 py-2 bg-lime-500 text-white rounded">
              Pilih
            </button>

            <button
              class="detail-btn text-sm text-gray-500"
              data-name="{{ $menu->menu_name }}"
              data-desc="{{ $menu->description ?? 'Tidak ada deskripsi menu' }}"
              data-price="{{ number_format($menu->price ?? 0,0,',','.') }}"
              data-tenant="{{ $menu->tenant->tenant_name ?? '-' }}"
              data-location="{{ $menu->tenant->location ?? '-' }}">
              Lihat detail
            </button>
          </div>
        </div>
      @empty
        <div class="col-span-full text-center text-gray-500 py-8">
          Belum ada menu untuk mood ini.
        </div>
      @endforelse
    </div>

    <div id="no-results" class="hidden text-center text-gray-500 py-6">
      Tidak ditemukan menu yang sesuai.
    </div>
  </div>

  <!-- DETAIL MODAL -->
  <div id="detail-modal" class="fixed inset-0 hidden bg-black/40 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-5 relative">
      <button onclick="closeDetail()" class="absolute top-2 right-3 text-gray-400 text-xl">&times;</button>

      <h2 id="d-name" class="text-xl font-bold mb-2"></h2>
      <p id="d-desc" class="text-sm text-gray-600 mb-3"></p>

      <div class="text-sm space-y-1">
        <div><b>Harga:</b> Rp <span id="d-price"></span></div>
        <div><b>Tenant:</b> <span id="d-tenant"></span></div>
        <div><b>Lokasi:</b> <span id="d-location"></span></div>
      </div>
    </div>
  </div>

  <!-- SCRIPT -->
  <script>
  const token = document.querySelector('meta[name="csrf-token"]').content;
  const modal = document.getElementById('detail-modal');

  /* =====================
     DETAIL MODAL
  ====================== */
  function openDetail(btn) {
    document.getElementById('d-name').innerText = btn.dataset.name;
    document.getElementById('d-desc').innerText = btn.dataset.desc;
    document.getElementById('d-price').innerText = btn.dataset.price;
    document.getElementById('d-tenant').innerText = btn.dataset.tenant;
    document.getElementById('d-location').innerText = btn.dataset.location;

    modal.classList.remove('hidden');
  }

  function closeDetail() {
    modal.classList.add('hidden');
  }

  document.querySelectorAll('.detail-btn, .popular-btn').forEach(btn => {
    btn.addEventListener('click', () => openDetail(btn));
  });

  /* =====================
     CATEGORY FILTER
  ====================== */
  let activeCategory = 'all';

  document.querySelectorAll('.category-chip').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.category-chip').forEach(b => {
        b.classList.remove('bg-lime-200', 'text-lime-800');
        b.classList.add('bg-gray-50', 'text-gray-700');
      });

      btn.classList.add('bg-lime-200', 'text-lime-800');
      btn.classList.remove('bg-gray-50');

      activeCategory = btn.dataset.category;
      applyFilters();
    });
  });

  /* =====================
     SEARCH + FILTER
  ====================== */
  const searchInput = document.getElementById('menu-search');
  searchInput.addEventListener('input', applyFilters);

  function applyFilters() {
    const q = searchInput.value.toLowerCase().trim();
    let shown = 0;

    document.querySelectorAll('.menu-card').forEach(card => {
      const matchCat =
        activeCategory === 'all' ||
        card.dataset.categoryId === activeCategory;

      const matchText =
        q === '' || card.dataset.searchText.includes(q);

      card.style.display = (matchCat && matchText) ? '' : 'none';
      if (matchCat && matchText) shown++;
    });

    document.getElementById('no-results')
      .classList.toggle('hidden', shown > 0);
  }

  /* =====================
     PILIH MENU (FIXED)
  ====================== */
  document.querySelectorAll('.select-btn').forEach(btn => {
    btn.addEventListener('click', function () {
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
        body: JSON.stringify({
          menu_id: menuId,
          mood_id: moodId
        })
      })
      .then(res => res.json())
      .then(() => {
  this.innerText = 'Dipilih ✓';
  showToast('Interaksi tersimpan. Terima kasih!');

  setTimeout(() => {
    this.innerText = 'Pilih';
    this.disabled = false;
  }, 1500);
      })
      .catch(() => {
        this.innerText = 'Pilih';
        this.disabled = false;
        alert('Gagal menyimpan interaksi');
      });
    });
  });
  function showToast(message, isError = false) {
    const el = document.createElement('div');
    el.className =
      'fixed bottom-6 right-6 bg-white px-4 py-2 rounded shadow-lg text-sm z-50';
    el.innerText = message;

    if (isError) {
      el.classList.add('text-red-600');
    } else {
      el.classList.add('text-gray-800');
    }

    document.body.appendChild(el);
    setTimeout(() => el.remove(), 2500);
  }
</script>
</body>
</html>
