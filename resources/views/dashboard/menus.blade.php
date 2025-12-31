<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan Menu - MoodFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @php use Illuminate\Support\Str; @endphp
</head>

<body class="bg-gray-100 min-h-screen text-gray-800">

<!-- ================= NAVBAR ================= -->
<nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16 items-center">
            <h1 class="text-xl font-bold text-blue-600">MoodFood Admin</h1>

            <div class="hidden sm:flex space-x-6 text-sm font-medium">
                <a href="{{ route('dashboard.index') }}" class="text-gray-500 hover:text-blue-600">Dashboard</a>
                <a href="{{ route('dashboard.menus') }}" class="text-blue-600 border-b-2 border-blue-600 pb-1">Menu</a>
                <a href="{{ route('dashboard.categories') }}" class="text-gray-500 hover:text-blue-600">Kategori</a>
                <a href="{{ route('dashboard.moods') }}" class="text-gray-500 hover:text-blue-600">Mood</a>
                <a href="{{ route('statistics.index') }}" class="text-gray-500 hover:text-blue-600">Statistik</a>
            </div>
        </div>
    </div>
</nav>

<!-- ================= CONTENT ================= -->
<main class="max-w-7xl mx-auto px-6 py-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h2 class="text-2xl font-bold">Pengelolaan Menu</h2>
        <button onclick="openModal()"
            class="mt-4 sm:mt-0 inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Tambah Menu
        </button>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- ================= TABLE ================= -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr class="text-left text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Menu</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Tenant</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Mood</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($menus as $menu)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-semibold">{{ $menu->menu_name }}</div>
                        @if($menu->description)
                            <div class="text-gray-500 text-xs mt-1">
                                {{ Str::limit($menu->description, 60) }}
                            </div>
                        @endif
                    </td>

                    <td class="px-6 py-4 font-medium">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $menu->tenant->tenant_name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $menu->category->category_name }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                            {{ $menu->category->mood->mood_name }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center space-x-3">
                        <button
                            onclick="editMenu({{ $menu->id }}, '{{ $menu->menu_name }}', {{ $menu->price }}, '{{ $menu->description }}', {{ $menu->tenant_id }}, {{ $menu->category_id }})"
                            class="text-blue-600 hover:underline">
                            Edit
                        </button>

                        <form action="{{ route('dashboard.menus.delete', $menu->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>

<!-- ================= MODAL ================= -->
<div id="modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <h3 id="modal-title" class="text-lg font-bold mb-4">Tambah Menu</h3>

        <form id="menu-form" method="POST">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">

            <div class="space-y-4">
                <input id="menu_name" name="menu_name" placeholder="Nama Menu" required class="w-full input" />
                <input id="price" name="price" type="number" placeholder="Harga" required class="w-full input" />
                <textarea id="description" name="description" rows="3" placeholder="Deskripsi" class="w-full input"></textarea>

                <select id="tenant_id" name="tenant_id" required class="w-full input">
                    <option value="">Pilih Tenant</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}">{{ $tenant->tenant_name }}</option>
                    @endforeach
                </select>

                <select id="category_id" name="category_id" required class="w-full input">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->category_name }} ({{ $category->mood->mood_name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-lg">
                    Batal
                </button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
    function openModal() {
        modal.classList.remove('hidden');
        modalTitle.textContent = 'Tambah Menu';
        form.action = '{{ route("dashboard.menus.store") }}';
        method.value = 'POST';
        form.reset();
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    function editMenu(id, name, price, desc, tenant, category) {
        openModal();
        modalTitle.textContent = 'Edit Menu';
        form.action = '{{ route("dashboard.menus.update", ":id") }}'.replace(':id', id);
        method.value = 'PUT';

        menu_name.value = name;
        priceInput.value = price;
        description.value = desc ?? '';
        tenant_id.value = tenant;
        category_id.value = category;
    }

    const modal = document.getElementById('modal');
    const modalTitle = document.getElementById('modal-title');
    const form = document.getElementById('menu-form');
    const method = document.getElementById('form-method');

    const menu_name = document.getElementById('menu_name');
    const priceInput = document.getElementById('price');
    const description = document.getElementById('description');
    const tenant_id = document.getElementById('tenant_id');
    const category_id = document.getElementById('category_id');

    window.onclick = e => { if (e.target === modal) closeModal(); };
</script>

<style>
    .input {
        @apply border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none;
    }
</style>

</body>
</html>