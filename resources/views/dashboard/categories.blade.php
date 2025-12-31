<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan Kategori Mood - MoodFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen text-gray-800">

<!-- ================= NAVBAR ================= -->
<nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16 items-center">
            <h1 class="text-xl font-bold text-blue-600">MoodFood Admin</h1>

            <div class="hidden sm:flex space-x-6 text-sm font-medium">
                <a href="{{ route('dashboard.index') }}" class="text-gray-500 hover:text-blue-600">Dashboard</a>
                <a href="{{ route('dashboard.menus') }}" class="text-gray-500 hover:text-blue-600">Menu</a>
                <a href="{{ route('dashboard.categories') }}" class="text-blue-600 border-b-2 border-blue-600 pb-1">Kategori</a>
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
        <h2 class="text-2xl font-bold">Pengelolaan Kategori Mood</h2>
        <button onclick="openModal()"
            class="mt-4 sm:mt-0 inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Tambah Kategori
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
                    <th class="px-6 py-4">Nama Kategori</th>
                    <th class="px-6 py-4">Mood</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($categories as $category)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium">
                        {{ $category->category_name }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">
                            {{ $category->mood->mood_name }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center space-x-3">
                        <button
                            onclick="editCategory({{ $category->id }}, '{{ $category->category_name }}', {{ $category->mood_id }})"
                            class="text-blue-600 hover:underline">
                            Edit
                        </button>

                        <form action="{{ route('dashboard.categories.delete', $category->id) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">
                                Hapus
                            </button>
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
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
        <h3 id="modal-title" class="text-lg font-bold mb-4">Tambah Kategori</h3>

        <form id="category-form" method="POST">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">

            <div class="space-y-4">
                <input
                    id="category_name"
                    name="category_name"
                    placeholder="Nama Kategori"
                    required
                    class="input"
                >

                <select
                    id="mood_id"
                    name="mood_id"
                    required
                    class="input"
                >
                    <option value="">Pilih Mood</option>
                    @foreach($moods as $mood)
                        <option value="{{ $mood->id }}">{{ $mood->mood_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-200 rounded-lg">
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
    const modal = document.getElementById('modal');
    const modalTitle = document.getElementById('modal-title');
    const form = document.getElementById('category-form');
    const method = document.getElementById('form-method');

    const categoryName = document.getElementById('category_name');
    const moodId = document.getElementById('mood_id');

    function openModal() {
        modal.classList.remove('hidden');
        modalTitle.textContent = 'Tambah Kategori';
        form.action = '{{ route("dashboard.categories.store") }}';
        method.value = 'POST';
        form.reset();
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    function editCategory(id, name, mood) {
        openModal();
        modalTitle.textContent = 'Edit Kategori';
        form.action = '{{ route("dashboard.categories.update", ":id") }}'.replace(':id', id);
        method.value = 'PUT';

        categoryName.value = name;
        moodId.value = mood;
    }

    window.onclick = e => {
        if (e.target === modal) closeModal();
    };
</script>

<style>
    .input {
        @apply w-full border border-gray-300 rounded-lg px-3 py-2
               focus:ring-2 focus:ring-blue-500 outline-none;
    }
</style>

</body>
</html>