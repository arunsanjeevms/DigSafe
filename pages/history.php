<?php
// History page with charts and range selector
?>
<section x-data="historyPage()" x-init="init()" class="space-y-6">
  <!-- Page Header -->
  <div class="flex items-center justify-between" data-aos="fade-down">
    <div>
      <h1 class="text-3xl font-bold text-white mb-1">Historical Data</h1>
      <p class="text-slate-400 text-sm">Time-series analysis of sensor readings</p>
    </div>
    <div class="flex gap-3">
      <button class="btn" :class="range==='5m' ? 'btn-active' : ''" @click="setRange('5m')">
        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        5 min
      </button>
      <button class="btn" :class="range==='30m' ? 'btn-active' : ''" @click="setRange('30m')">
        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        30 min
      </button>
      <button class="btn" :class="range==='24h' ? 'btn-active' : ''" @click="setRange('24h')">
        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        24 hr
      </button>
    </div>
  </div>

  <!-- MQ135 Chart -->
  <div class="chart-card" data-aos="fade-up">
    <div class="flex items-start justify-between mb-4">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Air Quality Trends</h3>
          <p class="text-xs text-slate-400">MQ-135 Exponential Moving Average</p>
        </div>
      </div>
      <div class="px-3 py-1 rounded-lg bg-blue-500/10 border border-blue-500/30">
        <span class="text-xs font-semibold text-blue-400">PPM</span>
      </div>
    </div>
    <div class="h-64">
      <canvas id="chart-mq135"></canvas>
    </div>
  </div>

  <!-- MQ7 Chart -->
  <div class="chart-card" data-aos="fade-up" data-aos-delay="100">
    <div class="flex items-start justify-between mb-4">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-orange-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Carbon Monoxide Levels</h3>
          <p class="text-xs text-slate-400">MQ-7 Exponential Moving Average</p>
        </div>
      </div>
      <div class="px-3 py-1 rounded-lg bg-orange-500/10 border border-orange-500/30">
        <span class="text-xs font-semibold text-orange-400">PPM</span>
      </div>
    </div>
    <div class="h-64">
      <canvas id="chart-mq7"></canvas>
    </div>
  </div>

  <!-- Temperature Chart -->
  <div class="chart-card" data-aos="fade-up" data-aos-delay="200">
    <div class="flex items-start justify-between mb-4">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a3 3 0 00-3 3v6a4 4 0 108 0V5a3 3 0 00-3-3zm-1 11.7A3.5 3.5 0 019 11V5a1 1 0 112 0v6a3.5 3.5 0 01-.1 2.7A1.5 1.5 0 0110 15a1.5 1.5 0 01-1-2.3z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Temperature History</h3>
          <p class="text-xs text-slate-400">LM35 Exponential Moving Average</p>
        </div>
      </div>
      <div class="px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/30">
        <span class="text-xs font-semibold text-emerald-400">°C</span>
      </div>
    </div>
    <div class="h-64">
      <canvas id="chart-temp"></canvas>
    </div>
  </div>
</section>
