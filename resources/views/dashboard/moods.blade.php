<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan Mood - MoodFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen text-gray-800">

<!-- Navbar -->
<nav class="bg-white border-b sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center h-16">
            <h1 class="text-xl font-bold text-blue-600">MoodFood Admin</h1>

            <div class="hidden sm:flex space-x-6 text-sm font-medium">
                <a href="{{ route('dashboard.index') }}" class="text-gray-600 hover:text-blue-600">Dashboard</a>
                <a href="{{ route('dashboard.menus') }}" class="text-gray-600 hover:text-blue-600">Menu</a>
                <a href="{{ route('dashboard.categories') }}" class="text-gray-600 hover:text-blue-600">Kategori Mood</a>
                <a href="{{ route('dashboard.moods') }}" class="text-blue-600 border-b-2 border-blue-600 pb-1">Mood</a>
                <a href="{{ route('statistics.index') }}" class="text-gray-600 hover:text-blue-600">Statistik</a>
            </div>
        </div>
    </div>
</nav>

<!-- Content -->
<main class="max-w-7xl mx-auto px-6 py-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-bold">Pengelolaan Mood</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola data mood dan keterkaitannya dengan kategori</p>
        </div>

        <button onclick="openModal()"
            class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition shadow-sm">
            + Tambah Mood
        </button>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b">
                <tr class="text-xs uppercase tracking-wide text-gray-500">
                    <th class="px-6 py-4 text-left">Mood</th>
                    <th class="px-6 py-4 text-left">Deskripsi</th>
                    <th class="px-6 py-4 text-left">Kategori</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($moods as $mood)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium">
                        {{ $mood->mood_name }}
                    </td>

                    <td class="px-6 py-4 text-gray-500 text-sm">
                        {{ $mood->description ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            {{ $mood->categories_count }} kategori
                        </span>
                    </td>

                    <td class="px-6 py-4 text-right space-x-3 text-sm">
                        <button
                            onclick="editMood({{ $mood->id }}, '{{ $mood->mood_name }}', '{{ $mood->description ?? '' }}')"
                            class="text-blue-600 hover:text-blue-800 font-medium">
                            Edit
                        </button>

                        <form action="{{ route('dashboard.moods.delete', $mood->id) }}"
                              method="POST" class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus mood ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:text-red-800 font-medium">
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

<!-- Modal -->
<div id="modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6 shadow-lg relative">
        <h3 id="modal-title" class="text-xl font-semibold mb-5">Tambah Mood</h3>

        <form id="mood-form" method="POST">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Mood</label>
                <input type="text" name="mood_name" id="mood_name" required
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal-title').innerText = 'Tambah Mood';
    document.getElementById('mood-form').action = '{{ route("dashboard.moods.store") }}';
    document.getElementById('form-method').value = 'POST';
    document.getElementById('mood-form').reset();
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

function editMood(id, name, description) {
    openModal();
    document.getElementById('modal-title').innerText = 'Edit Mood';
    document.getElementById('mood-form').action =
        '{{ route("dashboard.moods.update", ":id") }}'.replace(':id', id);
    document.getElementById('form-method').value = 'PUT';
    document.getElementById('mood_name').value = name;
    document.getElementById('description').value = description || '';
}
</script>

</body>
</html>