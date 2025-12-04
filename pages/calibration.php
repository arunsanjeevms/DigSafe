<?php
// Calibration page
?>
<section x-data="calibrationPage()" x-init="init()" class="space-y-6">
  <!-- Page Header -->
  <div class="flex items-center justify-between" data-aos="fade-down">
    <div>
      <h1 class="text-3xl font-bold text-white mb-1">Sensor Calibration</h1>
      <p class="text-slate-400 text-sm">Establish baseline readings and configure detection thresholds</p>
    </div>
    <div class="px-4 py-2 rounded-xl bg-slate-900/60 border border-slate-800">
      <div class="flex items-center gap-2">
        <div class="w-2 h-2 rounded-full" :class="calibrating ? 'bg-yellow-400 animate-pulse' : 'bg-emerald-400'"></div>
        <span class="text-sm text-slate-300" x-text="calibrating ? 'Calibrating...' : 'Ready'"></span>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Live Sensor Readings -->
    <div class="control-card" data-aos="fade-up">
      <div class="flex items-center gap-3 mb-4">
        <div class="h-10 w-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Live Readings</h3>
          <p class="text-xs text-slate-400">Current sensor values</p>
        </div>
      </div>
      <div class="space-y-3">
        <div class="p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="flex justify-between items-center mb-1">
            <span class="text-xs text-slate-400">MQ-135</span>
            <span class="text-xs px-2 py-0.5 rounded bg-blue-500/20 text-blue-400">PPM</span>
          </div>
          <div class="text-2xl font-bold text-white" x-text="liveData.mq135 ?? '-'"></div>
        </div>
        <div class="p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="flex justify-between items-center mb-1">
            <span class="text-xs text-slate-400">MQ-7</span>
            <span class="text-xs px-2 py-0.5 rounded bg-orange-500/20 text-orange-400">PPM</span>
          </div>
          <div class="text-2xl font-bold text-white" x-text="liveData.mq7 ?? '-'"></div>
        </div>
        <div class="p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="flex justify-between items-center mb-1">
            <span class="text-xs text-slate-400">Temperature</span>
            <span class="text-xs px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400">°C</span>
          </div>
          <div class="text-2xl font-bold text-white" x-text="liveData.temp ?? '-'"></div>
        </div>
      </div>
    </div>

    <!-- Calibration Control -->
    <div class="control-card" data-aos="fade-up" data-aos-delay="100">
      <div class="flex items-center gap-3 mb-4">
        <div class="h-10 w-10 rounded-xl bg-cyan-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Auto Calibration</h3>
          <p class="text-xs text-slate-400">6-second baseline scan</p>
        </div>
      </div>
      
      <button 
        class="w-full btn" 
        :class="calibrating ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-neon hover:scale-105'"
        @click="startCalibration" 
        :disabled="calibrating">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        <span x-text="calibrating ? 'Calibrating...' : 'Start Calibration'"></span>
      </button>

      <!-- Progress bar -->
      <div class="mt-4 relative">
        <div class="w-full h-4 rounded-lg bg-slate-900/60 border border-slate-800 overflow-hidden">
          <div 
            class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 transition-all duration-300 ease-out"
            :style="`width: ${progress}%`"
            :class="calibrating ? 'animate-pulse' : ''"></div>
        </div>
        <div class="mt-2 text-center">
          <span class="text-sm font-semibold text-slate-300" x-text="progress + '%'"></span>
        </div>
      </div>

      <div class="mt-4 p-3 rounded-lg bg-blue-500/10 border border-blue-500/30">
        <div class="flex items-start gap-2">
          <svg class="w-4 h-4 text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
          <p class="text-xs text-blue-300">Place sensors in clean air environment before calibrating. This establishes baseline readings for all sensors.</p>
        </div>
      </div>
    </div>

    <!-- Calibration History -->
    <div class="control-card" data-aos="fade-up" data-aos-delay="200">
      <div class="flex items-center gap-3 mb-4">
        <div class="h-10 w-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Recent Activity</h3>
          <p class="text-xs text-slate-400">Last 5 calibrations</p>
        </div>
      </div>
      
      <div class="space-y-2 max-h-64 overflow-y-auto custom-scrollbar">
        <template x-if="history.length === 0">
          <div class="p-4 text-center text-slate-500 text-sm">
            <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            No calibration history yet
          </div>
        </template>
        <template x-for="(item, idx) in history" :key="idx">
          <div class="p-3 rounded-lg bg-slate-900/40 border border-slate-800 hover:border-slate-700 transition">
            <div class="flex justify-between items-start mb-2">
              <span class="text-xs font-semibold text-slate-300" x-text="item.time"></span>
              <span class="text-xs px-2 py-0.5 rounded" :class="item.success ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400'" x-text="item.success ? 'Success' : 'Failed'"></span>
            </div>
            <div class="grid grid-cols-3 gap-2 text-xs">
              <div>
                <span class="text-slate-500">MQ135:</span>
                <span class="text-slate-300 font-semibold ml-1" x-text="item.mq135"></span>
              </div>
              <div>
                <span class="text-slate-500">MQ7:</span>
                <span class="text-slate-300 font-semibold ml-1" x-text="item.mq7"></span>
              </div>
              <div>
                <span class="text-slate-500">Temp:</span>
                <span class="text-slate-300 font-semibold ml-1" x-text="item.temp"></span>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>

  <!-- Threshold Configuration -->
  <div class="control-card" data-aos="fade-up">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Detection Thresholds</h3>
          <p class="text-xs text-slate-400">Configure alarm trigger levels</p>
        </div>
      </div>
      <div class="flex gap-2">
        <button class="px-3 py-1.5 text-sm rounded-lg bg-slate-900/60 border border-slate-800 text-slate-300 hover:bg-slate-800 transition" @click="resetThresholds">
          <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
          Reset
        </button>
        <button class="px-3 py-1.5 text-sm rounded-lg bg-cyan-500/20 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/30 transition" @click="saveThresholds">
          <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
          Save
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- MQ135 Threshold -->
      <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800">
        <div class="flex items-center gap-2 mb-3">
          <div class="h-8 w-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
            <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
          </div>
          <div>
            <h4 class="text-sm font-bold text-white">MQ-135</h4>
            <p class="text-xs text-slate-500">Air Quality</p>
          </div>
        </div>
        <div class="space-y-2">
          <div>
            <label class="text-xs text-slate-400 mb-1 block">Calibrated Value</label>
            <div class="text-xl font-bold text-blue-400" x-text="thresholds.mq135_ema || '-'"></div>
          </div>
          <div>
            <label class="text-xs text-slate-400 mb-1 block">Alarm Threshold (PPM)</label>
            <input 
              type="number" 
              x-model.number="thresholds.mq135_threshold"
              class="w-full px-3 py-2 rounded-lg bg-slate-900/60 border border-slate-800 text-white focus:border-blue-500 focus:outline-none transition"
              placeholder="Enter threshold">
          </div>
        </div>
      </div>

      <!-- MQ7 Threshold -->
      <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800">
        <div class="flex items-center gap-2 mb-3">
          <div class="h-8 w-8 rounded-lg bg-orange-500/20 flex items-center justify-center">
            <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
          </div>
          <div>
            <h4 class="text-sm font-bold text-white">MQ-7</h4>
            <p class="text-xs text-slate-500">Carbon Monoxide</p>
          </div>
        </div>
        <div class="space-y-2">
          <div>
            <label class="text-xs text-slate-400 mb-1 block">Calibrated Value</label>
            <div class="text-xl font-bold text-orange-400" x-text="thresholds.mq7_ema || '-'"></div>
          </div>
          <div>
            <label class="text-xs text-slate-400 mb-1 block">Alarm Threshold (PPM)</label>
            <input 
              type="number" 
              x-model.number="thresholds.mq7_threshold"
              class="w-full px-3 py-2 rounded-lg bg-slate-900/60 border border-slate-800 text-white focus:border-orange-500 focus:outline-none transition"
              placeholder="Enter threshold">
          </div>
        </div>
      </div>

      <!-- Temperature Threshold -->
      <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800">
        <div class="flex items-center gap-2 mb-3">
          <div class="h-8 w-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
            <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a3 3 0 00-3 3v6a4 4 0 108 0V5a3 3 0 00-3-3zm-1 11.7A3.5 3.5 0 019 11V5a1 1 0 112 0v6a3.5 3.5 0 01-.1 2.7A1.5 1.5 0 0110 15a1.5 1.5 0 01-1-2.3z" clip-rule="evenodd"></path></svg>
          </div>
          <div>
            <h4 class="text-sm font-bold text-white">LM35</h4>
            <p class="text-xs text-slate-500">Temperature</p>
          </div>
        </div>
        <div class="space-y-2">
          <div>
            <label class="text-xs text-slate-400 mb-1 block">Calibrated Value</label>
            <div class="text-xl font-bold text-emerald-400" x-text="thresholds.temp_ema ? thresholds.temp_ema + ' °C' : '-'"></div>
          </div>
          <div>
            <label class="text-xs text-slate-400 mb-1 block">Alarm Threshold (°C)</label>
            <input 
              type="number" 
              x-model.number="thresholds.temp_threshold"
              class="w-full px-3 py-2 rounded-lg bg-slate-900/60 border border-slate-800 text-white focus:border-emerald-500 focus:outline-none transition"
              placeholder="Enter threshold">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
