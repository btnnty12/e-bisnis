<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan Menu - MoodFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @php
        use Illuminate\Support\Str;
    @endphp
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-2xl font-bold text-gray-800">MoodFood Dashboard</h1>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('dashboard.menus') }}" class="border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Menu
                        </a>
                        <a href="{{ route('dashboard.categories') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Kategori Mood
                        </a>
                        <a href="{{ route('dashboard.moods') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Mood
                        </a>
                        <a href="{{ route('statistics.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Statistik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-900">Pengelolaan Menu</h2>
                <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Tambah Menu
                </button>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Menu Table -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Menu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mood</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($menus as $menu)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $menu->menu_name }}</div>
                                @if($menu->description)
                                <div class="text-sm text-gray-500">{{ Str::limit($menu->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $menu->tenant->tenant_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $menu->category->category_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $menu->category->mood->mood_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="editMenu({{ $menu->id }}, '{{ $menu->menu_name }}', {{ $menu->price }}, '{{ $menu->description }}', {{ $menu->tenant_id }}, {{ $menu->category_id }})" 
                                    class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                <form action="{{ route('dashboard.menus.delete', $menu->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title">Tambah Menu Baru</h3>
                <form id="menu-form" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="menu_id" id="menu-id">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu</label>
                        <input type="text" name="menu_name" id="menu_name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                        <input type="number" name="price" id="price" step="0.01" min="0" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tenant</label>
                        <select name="tenant_id" id="tenant_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Tenant</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}">{{ $tenant->tenant_name }} - {{ $tenant->location }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" id="category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-mood="{{ $category->mood->mood_name }}">
                                    {{ $category->category_name }} ({{ $category->mood->mood_name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal-title').textContent = 'Tambah Menu Baru';
            document.getElementById('menu-form').action = '{{ route("dashboard.menus.store") }}';
            document.getElementById('form-method').value = 'POST';
            document.getElementById('menu-form').reset();
            document.getElementById('menu-id').value = '';
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function editMenu(id, name, price, description, tenantId, categoryId) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal-title').textContent = 'Edit Menu';
            document.getElementById('menu-form').action = '{{ route("dashboard.menus.update", ":id") }}'.replace(':id', id);
            document.getElementById('form-method').value = 'PUT';
            document.getElementById('menu-id').value = id;
            document.getElementById('menu_name').value = name;
            document.getElementById('price').value = price;
            document.getElementById('description').value = description || '';
            document.getElementById('tenant_id').value = tenantId;
            document.getElementById('category_id').value = categoryId;
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('modal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
