<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Statistik MoodFood</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">

<!-- NAVBAR -->
<nav class="bg-white shadow-sm border-b">
  <div class="max-w-7xl mx-auto px-6">
    <div class="flex justify-between items-center h-16">
      <h1 class="text-xl font-bold text-blue-600">MoodFood Dashboard</h1>
      <div class="hidden sm:flex space-x-6 text-sm font-medium">
        <a href="{{ route('dashboard.index') }}" class="text-gray-500 hover:text-blue-600">Dashboard</a>
        <a href="{{ route('dashboard.menus') }}" class="text-gray-500 hover:text-blue-600">Menu</a>
        <a href="{{ route('dashboard.categories') }}" class="text-gray-500 hover:text-blue-600">Kategori</a>
        <a href="{{ route('dashboard.moods') }}" class="text-gray-500 hover:text-blue-600">Mood</a>
        <a href="{{ route('statistics.index') }}" class="text-blue-600 border-b-2 border-blue-600 pb-1">Statistik</a>
      </div>
    </div>
  </div>
</nav>

<main class="max-w-7xl mx-auto px-6 py-8 space-y-8">

  <div>
    <h2 class="text-2xl font-bold">Statistik MoodFood</h2>
    <p class="text-gray-500 text-sm">Analisis interaksi pengguna berdasarkan mood & event</p>
  </div>

  <div class="flex gap-3">
    <button id="btn-before-after" onclick="showBeforeAfter()"
      class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold">
      Sebelum & Sesudah
    </button>
    <button id="btn-per-event" onclick="showPerEvent()"
      class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-semibold">
      Per Event
    </button>
  </div>

  <!-- BEFORE & AFTER -->
  <section id="section-before-after" class="space-y-6">
    <div class="bg-white rounded-xl shadow p-6">
      <h3 class="text-lg font-semibold mb-4">Statistik Sebelum & Sesudah</h3>
      <form id="form-before-after" class="flex flex-col sm:flex-row gap-4">
        <input type="date" id="date-input" required
          class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
          Tampilkan
        </button>
      </form>
    </div>
    <div id="before-after-results" class="hidden space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-blue-50 rounded-xl p-6">
          <h4 class="font-semibold text-blue-700 mb-2">Sebelum</h4>
          <div id="before-stats" class="text-sm space-y-1"></div>
        </div>
        <div class="bg-green-50 rounded-xl p-6">
          <h4 class="font-semibold text-green-700 mb-2">Sesudah</h4>
          <div id="after-stats" class="text-sm space-y-1"></div>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
          <h4 class="font-semibold mb-4">Mood Sebelum</h4>
          <canvas id="chart-mood-before"></canvas>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
          <h4 class="font-semibold mb-4">Mood Sesudah</h4>
          <canvas id="chart-mood-after"></canvas>
        </div>
      </div>
    </div>
  </section>

  <!-- PER EVENT -->
  <section id="section-per-event" class="hidden space-y-6">
    <div class="bg-white rounded-xl shadow p-6">
      <h3 class="text-lg font-semibold mb-4">Statistik Per Event</h3>
      <div class="flex flex-col sm:flex-row gap-4 mb-4">
        <select id="event-select" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Event</option>
          @foreach($events as $event)
            <option value="{{ $event->id }}">{{ $event->event_name }}</option>
          @endforeach
        </select>
        <button id="btn-show-event" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
          Tampilkan
        </button>
      </div>
    </div>
    <div id="per-event-result-container"></div>
  </section>

</main>

<script>
let moodBeforeChart=null;
let moodAfterChart=null;
let eventChart=null;

const dateInput=document.getElementById('date-input');
const beforeStats=document.getElementById('before-stats');
const afterStats=document.getElementById('after-stats');
const chartMoodBefore=document.getElementById('chart-mood-before');
const chartMoodAfter=document.getElementById('chart-mood-after');
const eventSelect=document.getElementById('event-select');
const sectionPerEvent=document.getElementById('section-per-event');
const btnShowEvent=document.getElementById('btn-show-event');

/* TAB */
function showBeforeAfter(){section('before-after');}
function showPerEvent(){section('per-event');}
function section(type){
  document.getElementById('section-before-after').classList.toggle('hidden',type!=='before-after');
  document.getElementById('section-per-event').classList.toggle('hidden',type!=='per-event');
  document.getElementById('btn-before-after').classList.toggle('bg-blue-600',type==='before-after');
  document.getElementById('btn-before-after').classList.toggle('bg-gray-200',type!=='before-after');
  document.getElementById('btn-before-after').classList.toggle('text-white',type==='before-after');
  document.getElementById('btn-before-after').classList.toggle('text-gray-700',type!=='before-after');
  document.getElementById('btn-per-event').classList.toggle('bg-blue-600',type==='per-event');
  document.getElementById('btn-per-event').classList.toggle('bg-gray-200',type!=='per-event');
  document.getElementById('btn-per-event').classList.toggle('text-white',type==='per-event');
  document.getElementById('btn-per-event').classList.toggle('text-gray-700',type!=='per-event');
}

/* BEFORE & AFTER */
document.getElementById('form-before-after').addEventListener('submit',async e=>{
  e.preventDefault();
  try{
    const res=await fetch(`/statistics/before-after?date=${dateInput.value}`);
    if(!res.ok)throw new Error('Gagal fetch');
    const data=await res.json();
    displayBeforeAfter(data);
  }catch(err){console.error(err);alert('Terjadi kesalahan saat mengambil data');}
});

function displayBeforeAfter(data){
  document.getElementById('before-after-results').classList.remove('hidden');
  beforeStats.innerHTML=`<p>Total Interaksi: <b>${data.before.total_interactions??0}</b></p>
                         <p>Pengguna Unik: <b>${data.before.unique_users??0}</b></p>`;
  afterStats.innerHTML=`<p>Total Interaksi: <b>${data.after.total_interactions??0}</b></p>
                        <p>Pengguna Unik: <b>${data.after.unique_users??0}</b></p>`;
  moodBeforeChart?.destroy();
  moodAfterChart?.destroy();
  moodBeforeChart=new Chart(chartMoodBefore,{type:'doughnut',data:{labels:data.before.by_mood?.map(m=>m.mood_name)||[],datasets:[{data:data.before.by_mood?.map(m=>m.total_interactions)||[],backgroundColor:['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF']}]}}); 
  moodAfterChart=new Chart(chartMoodAfter,{type:'doughnut',data:{labels:data.after.by_mood?.map(m=>m.mood_name)||[],datasets:[{data:data.after.by_mood?.map(m=>m.total_interactions)||[],backgroundColor:['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF']}]}}); 
}

/* PER EVENT */
btnShowEvent.addEventListener('click',async ()=>{
  let url='/statistics/per-event';
  if(eventSelect.value) url+=`?event_id=${eventSelect.value}`;
  try{
    const res=await fetch(url);
    if(!res.ok)throw new Error('Gagal fetch');
    const data=await res.json();
    renderPerEvent(data);
  }catch(err){console.error(err);alert('Terjadi kesalahan saat mengambil data');}
});

function renderPerEvent(data){
  const container = document.getElementById('per-event-result-container');
  container.innerHTML = ''; // Clear previous results

  let resultBox=document.createElement('div');
  resultBox.className='bg-white rounded-xl shadow p-6 space-y-4';

  if(Array.isArray(data.events)){
    // LIST ALL EVENTS
    if(data.events.length===0){
      resultBox.innerHTML=`<p class="text-gray-500">Belum ada interaksi pada event</p>`;
    }else{
      resultBox.innerHTML=`
        <h4 class="font-semibold text-lg mb-2">Ringkasan Semua Event</h4>
        <ul class="space-y-2 mb-6">
          ${data.events.map(e=>`<li class="flex justify-between border-b pb-1">
            <span>${e.event.event_name??e.event_name}</span>
            <b>${e.total_interactions??0} interaksi</b>
          </li>`).join('')}
        </ul>
        <div class="h-64">
             <canvas id="chart-event-mood"></canvas>
        </div>
      `;
      container.appendChild(resultBox);
      
      // Render Bar Chart for All Events (Interactions count)
      const ctx = document.getElementById('chart-event-mood').getContext('2d');
      eventChart?.destroy();
      eventChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.events.map(e => e.event.event_name),
            datasets: [{
                label: 'Total Interaksi',
                data: data.events.map(e => e.total_interactions),
                backgroundColor: '#3b82f6'
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
      });
    }
  }else if(data.event){
    // SINGLE EVENT DETAIL
    const e=data.event;
    resultBox.innerHTML=`
      <h4 class="font-semibold text-lg">${e.event_name??'Event'}</h4>
      <p class="text-sm text-gray-500 mb-2">${e.description??''}</p>
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Total Interaksi</p>
            <p class="text-xl font-bold text-blue-700">${data.total_interactions??0}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Pengguna Unik</p>
            <p class="text-xl font-bold text-green-700">${data.unique_users??0}</p>
        </div>
      </div>
      <h5 class="font-semibold mt-4">Distribusi Mood</h5>
      <div class="h-64">
           <canvas id="chart-event-mood"></canvas>
      </div>
    `;
    container.appendChild(resultBox);

    // Render Bar Chart for Mood Distribution in this Event
    const ctx = document.getElementById('chart-event-mood').getContext('2d');
    eventChart?.destroy();
    
    const moodLabels = data.by_mood?.map(m => m.mood_name) || [];
    const moodData = data.by_mood?.map(m => m.total_interactions) || [];

    if(moodLabels.length > 0) {
        eventChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: moodLabels,
                datasets: [{
                    label: 'Jumlah Interaksi Mood',
                    data: moodData,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ]
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    } else {
        document.getElementById('chart-event-mood').parentNode.innerHTML = '<p class="text-gray-500 italic">Belum ada data mood untuk ditampilkan di chart.</p>';
    }
  }else{
    resultBox.innerHTML=`<p class="text-red-600">Format data tidak dikenali</p>`;
    container.appendChild(resultBox);
  }
}
</script>

</body>
</html>