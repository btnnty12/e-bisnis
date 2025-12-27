<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik MoodFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Statistik MoodFood</h1>
            <p class="text-gray-600">Laporan statistik interaksi pengguna</p>
        </div>

        <!-- Navigation -->
        <div class="mb-6 flex gap-4">
            <button onclick="showBeforeAfter()" id="btn-before-after" 
                class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                Statistik Sebelum & Sesudah
            </button>
            <button onclick="showPerEvent()" id="btn-per-event" 
                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition">
                Statistik Per Event
            </button>
        </div>

        <!-- Before After Section -->
        <div id="section-before-after" class="space-y-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Statistik Sebelum & Sesudah</h2>
                <form id="form-before-after" class="mb-6">
                    <div class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                            <input type="date" id="date-input" name="date" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                            Tampilkan Statistik
                        </button>
                    </div>
                </form>
                <div id="before-after-results" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="card bg-blue-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-800 mb-4">Sebelum</h3>
                            <div id="before-stats" class="space-y-2"></div>
                        </div>
                        <div class="card bg-green-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">Sesudah</h3>
                            <div id="after-stats" class="space-y-2"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="card bg-white rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Mood (Sebelum)</h3>
                            <canvas id="chart-mood-before"></canvas>
                        </div>
                        <div class="card bg-white rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Mood (Sesudah)</h3>
                            <canvas id="chart-mood-after"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Per Event Section -->
        <div id="section-per-event" class="hidden space-y-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Statistik Per Event</h2>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Event</label>
                    <select id="event-select" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button onclick="loadEventStats()" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition mb-6">
                    Tampilkan Statistik
                </button>
                <div id="per-event-results" class="hidden">
                    <div id="event-stats-content"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let moodBeforeChart = null;
        let moodAfterChart = null;

        function showBeforeAfter() {
            document.getElementById('section-before-after').classList.remove('hidden');
            document.getElementById('section-per-event').classList.add('hidden');
            document.getElementById('btn-before-after').classList.remove('bg-gray-300', 'text-gray-700');
            document.getElementById('btn-before-after').classList.add('bg-blue-600', 'text-white');
            document.getElementById('btn-per-event').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('btn-per-event').classList.add('bg-gray-300', 'text-gray-700');
        }

        function showPerEvent() {
            document.getElementById('section-before-after').classList.add('hidden');
            document.getElementById('section-per-event').classList.remove('hidden');
            document.getElementById('btn-per-event').classList.remove('bg-gray-300', 'text-gray-700');
            document.getElementById('btn-per-event').classList.add('bg-blue-600', 'text-white');
            document.getElementById('btn-before-after').classList.remove('bg-blue-600', 'text-white');
            document.getElementById('btn-before-after').classList.add('bg-gray-300', 'text-gray-700');
        }

        document.getElementById('form-before-after').addEventListener('submit', async function(e) {
            e.preventDefault();
            const date = document.getElementById('date-input').value;
            
            try {
                const response = await fetch(`/statistics/before-after?date=${date}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                displayBeforeAfter(data);
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data');
            }
        });

        function displayBeforeAfter(data) {
            document.getElementById('before-after-results').classList.remove('hidden');
            
            // Display stats
            const beforeStats = document.getElementById('before-stats');
            beforeStats.innerHTML = `
                <p class="text-gray-700"><span class="font-semibold">Total Interaksi:</span> ${data.before.total_interactions}</p>
                <p class="text-gray-700"><span class="font-semibold">Pengguna Unik:</span> ${data.before.unique_users}</p>
            `;

            const afterStats = document.getElementById('after-stats');
            afterStats.innerHTML = `
                <p class="text-gray-700"><span class="font-semibold">Total Interaksi:</span> ${data.after.total_interactions}</p>
                <p class="text-gray-700"><span class="font-semibold">Pengguna Unik:</span> ${data.after.unique_users}</p>
            `;

            // Charts
            if (moodBeforeChart) moodBeforeChart.destroy();
            if (moodAfterChart) moodAfterChart.destroy();

            moodBeforeChart = new Chart(document.getElementById('chart-mood-before'), {
                type: 'doughnut',
                data: {
                    labels: data.before.by_mood.map(m => m.mood_name),
                    datasets: [{
                        data: data.before.by_mood.map(m => m.total),
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899']
                    }]
                }
            });

            moodAfterChart = new Chart(document.getElementById('chart-mood-after'), {
                type: 'doughnut',
                data: {
                    labels: data.after.by_mood.map(m => m.mood_name),
                    datasets: [{
                        data: data.after.by_mood.map(m => m.total),
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899']
                    }]
                }
            });
        }

        async function loadEventStats() {
            const eventId = document.getElementById('event-select').value;
            const url = eventId 
                ? `/statistics/per-event?event_id=${eventId}`
                : '/statistics/per-event';
            
            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                displayEventStats(data, eventId);
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data');
            }
        }

        function displayEventStats(data, eventId) {
            document.getElementById('per-event-results').classList.remove('hidden');
            const content = document.getElementById('event-stats-content');

            if (eventId) {
                // Single event stats
                content.innerHTML = `
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">${data.event.event_name}</h3>
                        <p class="text-gray-600 mb-4">${data.event.description || ''}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Total Interaksi</p>
                            <p class="text-2xl font-bold text-blue-600">${data.total_interactions}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Pengguna Unik</p>
                            <p class="text-2xl font-bold text-green-600">${data.unique_users}</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600">Mood Terpopuler</p>
                            <p class="text-2xl font-bold text-purple-600">${data.by_mood.length > 0 ? data.by_mood[0].mood_name : 'N/A'}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg p-6">
                            <h4 class="font-semibold text-gray-800 mb-4">Statistik Mood</h4>
                            <canvas id="chart-event-mood"></canvas>
                        </div>
                        <div class="bg-white rounded-lg p-6">
                            <h4 class="font-semibold text-gray-800 mb-4">Top 10 Menu</h4>
                            <div class="space-y-2">
                                ${data.by_menu.map((menu, idx) => `
                                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                        <span class="text-gray-700">${idx + 1}. ${menu.menu_name}</span>
                                        <span class="font-semibold text-blue-600">${menu.total}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;

                // Create mood chart
                setTimeout(() => {
                    new Chart(document.getElementById('chart-event-mood'), {
                        type: 'bar',
                        data: {
                            labels: data.by_mood.map(m => m.mood_name),
                            datasets: [{
                                label: 'Interaksi',
                                data: data.by_mood.map(m => m.total),
                                backgroundColor: '#3B82F6'
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                }, 100);
            } else {
                // All events stats
                content.innerHTML = `
                    <div class="space-y-4">
                        ${data.events.map(event => `
                            <div class="card bg-white rounded-lg p-6 border border-gray-200">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">${event.event.event_name}</h3>
                                    </div>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold">
                                        ${event.total_interactions} interaksi
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    ${event.by_mood.map(mood => `
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600">${mood.mood_name}</p>
                                            <p class="text-lg font-bold text-gray-800">${mood.total}</p>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
            }
        }
    </script>
</body>
</html>
