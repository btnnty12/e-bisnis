<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Explore Mood & Menu - MoodFood</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
  <div class="max-w-4xl mx-auto px-4 py-8">

    <!-- HEADER -->
    <div class="flex items-start justify-between mb-4">
      <h1 class="text-3xl font-bold">Jelajahi Semua Mood & Menu</h1>
      <a href="{{ route('home') }}" class="px-3 py-2 bg-gray-100 rounded">Kembali</a>
    </div>

    @foreach($moods as $mood)
      <div class="mb-10">
        <h2 class="text-xl font-semibold text-lime-700 mb-3">{{ $mood->mood_name }}</h2>

        @php $categories = $mood->categories; @endphp

        <!-- CATEGORY FILTER -->
        @if($categories->count())
          <div class="mb-4 flex flex-wrap gap-2">
            <button data-category="all" data-mood="{{ $mood->id }}"
                    class="category-chip px-3 py-1 rounded text-sm bg-gray-100 text-gray-700 hover:bg-lime-100 hover:text-lime-700 transition active">
              Semua
            </button>

            @foreach($categories as $cat)
              <button data-category="{{ $cat->id }}" data-mood="{{ $mood->id }}"
                      class="category-chip px-3 py-1 rounded text-sm bg-gray-50 text-gray-700 border hover:bg-lime-100 hover:text-lime-700 transition">
                {{ $cat->category_name }}
              </button>
            @endforeach
          </div>
        @endif

        <!-- MENUS GRID -->
        <div id="menus-grid-{{ $mood->id }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          @forelse($mood->categories->flatMap(fn($c) => $c->menus) as $menu)
            <div class="menu-card bg-white rounded-lg shadow p-4 flex flex-col"
                 data-category-id="{{ $menu->category_id }}"
                 data-search-text="{{ strtolower($menu->menu_name.' '.($menu->tenant->tenant_name ?? '').' '.($menu->category->category_name ?? '')) }}">
              <div class="flex items-start space-x-4">
                <img src="{{ $menu->image ?? asset('img/placeholder.png') }}"
                     class="w-20 h-20 object-cover rounded"/>
                <div class="flex-1">
                  <h3 class="font-semibold">{{ $menu->menu_name }}</h3>
                  <p class="text-sm text-gray-500">{{ $menu->tenant->tenant_name ?? 'Tenant' }} • {{ $menu->category->category_name ?? '' }}</p>
                  <p class="text-sm text-gray-700 mt-2">Rp {{ number_format($menu->price ?? 0,0,',','.') }}</p>
                </div>
              </div>
              <div class="mt-4 flex justify-between items-center">
                <button data-menu-id="{{ $menu->id }}" data-mood-id="{{ $mood->id }}"
                        class="select-btn px-3 py-2 bg-lime-500 text-white rounded">
                  Pilih
                </button>
                <button class="detail-btn text-sm text-gray-500"
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
              Belum ada menu untuk kategori ini.
            </div>
          @endforelse
        </div>

        <div id="no-results-{{ $mood->id }}" class="hidden text-center text-gray-500 py-6">
          Tidak ditemukan menu yang sesuai.
        </div>
      </div>
    @endforeach
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

  <script>
const token = document.querySelector('meta[name="csrf-token"]').content;
const modal = document.getElementById('detail-modal');

// DETAIL MODAL
function openDetail(btn){
  document.getElementById('d-name').innerText = btn.dataset.name;
  document.getElementById('d-desc').innerText = btn.dataset.desc;
  document.getElementById('d-price').innerText = btn.dataset.price;
  document.getElementById('d-tenant').innerText = btn.dataset.tenant;
  document.getElementById('d-location').innerText = btn.dataset.location;
  modal.classList.remove('hidden');
}
function closeDetail(){ modal.classList.add('hidden'); }

// PILIH MENU
function attachSelectBtns(){
  document.querySelectorAll('.select-btn').forEach(btn=>{
    btn.onclick = function(){
      const menuId = this.dataset.menuId;
      const moodId = this.dataset.moodId;
      this.disabled=true; this.innerText='Memproses...';
      fetch('/interactions/public', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token},
        body:JSON.stringify({menu_id:menuId, mood_id:moodId})
      })
      .then(r=>r.json())
      .then(()=>{
        this.innerText='Dipilih ✓';
        showToast('Interaksi tersimpan. Terima kasih!');
        setTimeout(()=>{this.innerText='Pilih'; this.disabled=false;},1500);
      })
      .catch(()=>{
        this.innerText='Pilih'; this.disabled=false;
        showToast('Gagal menyimpan interaksi', true);
      });
    }
  });
}

// TOAST
function showToast(message,isError=false){
  const el = document.createElement('div');
  el.className='fixed bottom-6 right-6 bg-white px-4 py-2 rounded shadow-lg text-sm z-50';
  el.innerText = message;
  if(isError) el.classList.add('text-red-600'); else el.classList.add('text-gray-800');
  document.body.appendChild(el);
  setTimeout(()=>el.remove(),2500);
}

// AJAX LOAD MENU
function loadMenus(categoryId, moodId){
  if(categoryId === 'all') categoryId = '';
  fetch(`/explore/ajax/${moodId}?category=${categoryId}`)
  .then(r=>r.json())
  .then(data=>{
    const grid = document.getElementById('menus-grid-'+moodId);
    grid.innerHTML='';
    const noResults = document.getElementById('no-results-'+moodId);
    if(data.length===0){ noResults.classList.remove('hidden'); return; }
    noResults.classList.add('hidden');
    data.forEach(menu=>{
      const card = document.createElement('div');
      card.className='menu-card bg-white rounded-lg shadow p-4 flex flex-col';
      card.dataset.categoryId = menu.category_id;
      card.dataset.searchText = (menu.menu_name+' '+menu.tenant_name+' '+menu.category_name).toLowerCase();
      card.innerHTML = `
        <div class="flex items-start space-x-4">
          <img src="${menu.image||'/img/placeholder.png'}" class="w-20 h-20 object-cover rounded"/>
          <div class="flex-1">
            <h3 class="font-semibold">${menu.menu_name}</h3>
            <p class="text-sm text-gray-500">${menu.tenant_name} • ${menu.category_name}</p>
            <p class="text-sm text-gray-700 mt-2">Rp ${menu.price_formatted}</p>
          </div>
        </div>
        <div class="mt-4 flex justify-between items-center">
          <button data-menu-id="${menu.id}" data-mood-id="${moodId}" class="select-btn px-3 py-2 bg-lime-500 text-white rounded">Pilih</button>
          <button class="detail-btn text-sm text-gray-500"
                  data-name="${menu.menu_name}"
                  data-desc="${menu.description||'Tidak ada deskripsi menu'}"
                  data-price="${menu.price_formatted}"
                  data-tenant="${menu.tenant_name}"
                  data-location="${menu.location||'-'}">
            Lihat detail
          </button>
        </div>
      `;
      grid.appendChild(card);
    });
    attachSelectBtns();
    document.querySelectorAll('.detail-btn').forEach(btn=>{ btn.onclick=()=>openDetail(btn); });
  });
}

// EVENT FILTER
document.querySelectorAll('.category-chip').forEach(btn=>{
  btn.onclick = ()=>{
    const activeCategory = btn.dataset.category;
    const moodId = btn.dataset.mood;
    btn.parentNode.querySelectorAll('.category-chip').forEach(b=>{
      b.classList.remove('bg-lime-200','text-lime-800'); b.classList.add('bg-gray-50','text-gray-700');
    });
    btn.classList.add('bg-lime-200','text-lime-800');
    loadMenus(activeCategory, moodId);
  }
});

// SEARCH GLOBAL
document.getElementById('menu-search')?.addEventListener('input', function(){
  const val=this.value.toLowerCase();
  document.querySelectorAll('.menu-card').forEach(card=>{
    card.style.display = card.dataset.searchText.includes(val)?'':'none';
  });
});

// INIT
attachSelectBtns();
document.querySelectorAll('.detail-btn').forEach(btn=>{ btn.onclick=()=>openDetail(btn); });
</script>
</body>
</html>