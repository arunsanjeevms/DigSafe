<?php
// Dashboard page
?>
<section x-data="dashboard()" x-init="init()" class="space-y-6">
  <!-- Page Header -->
  <div class="flex items-center justify-between" data-aos="fade-down">
    <div>
      <h1 class="text-3xl font-bold text-white mb-1">Live Dashboard</h1>
      <p class="text-slate-400 text-sm">Real-time monitoring of gas sensors and temperature</p>
    </div>
    <div class="text-right">
      <div class="text-xs text-slate-500 uppercase tracking-wider">Last Updated</div>
      <div class="text-sm font-medium text-slate-300" x-text="lastUpdated"></div>
    </div>
  </div>

  <!-- Alarm Banner -->
  <div x-show="alarmActive" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
       class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-red-500/20 via-red-600/20 to-red-500/20 border border-red-500/50 p-6 shadow-[0_0_50px_rgba(239,68,68,0.4)]" data-aos="zoom-in">
    <div class="absolute inset-0 bg-red-500/10 animate-pulse"></div>
    <div class="relative flex items-center gap-4">
      <div class="relative">
        <div class="h-12 w-12 rounded-full bg-red-500/20 flex items-center justify-center">
          <div class="h-6 w-6 rounded-full bg-red-500 animate-ping absolute"></div>
          <svg class="w-6 h-6 text-red-500 relative z-10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        </div>
      </div>
      <div>
        <div class="text-red-400 font-bold text-lg uppercase tracking-wider">⚠ ALARM ACTIVE</div>
        <div class="text-slate-300 text-sm mt-1">Critical threshold exceeded - immediate attention required</div>
      </div>
    </div>
  </div>

  <!-- Control Panel -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Manual SOS -->
    <div class="relative overflow-hidden rounded-xl border transition-all duration-300" 
         :class="sosEnabled ? 'bg-gradient-to-br from-red-900/40 via-red-800/30 to-red-900/40 border-red-500/50 shadow-[0_0_20px_rgba(239,68,68,0.3)]' : 'bg-gradient-to-br from-slate-900/60 via-slate-800/40 to-slate-900/60 border-slate-800'"
         data-aos="fade-right">
      <div class="absolute inset-0 opacity-10" :class="sosEnabled ? 'bg-red-500 animate-pulse' : 'bg-slate-700'"></div>
      <div class="relative p-4">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <div class="h-9 w-9 rounded-lg flex items-center justify-center transition-all duration-300"
                 :class="sosEnabled ? 'bg-red-500/30 shadow-[0_0_15px_rgba(239,68,68,0.4)]' : 'bg-red-500/10'">
              <svg class="w-5 h-5 transition-all duration-300" :class="sosEnabled ? 'text-red-400 animate-pulse' : 'text-red-500'" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-base font-bold text-white">Emergency SOS</h3>
              <p class="text-xs text-slate-400">Manual alarm override</p>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between mb-3">
          <div>
            <div class="text-xs font-semibold uppercase tracking-wider mb-1" :class="sosEnabled ? 'text-red-400' : 'text-slate-400'">
              Status
            </div>
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 rounded-full transition-all duration-300" :class="sosEnabled ? 'bg-red-500 animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.8)]' : 'bg-slate-600'"></div>
              <span class="text-sm font-bold" :class="sosEnabled ? 'text-red-300' : 'text-slate-400'" x-text="sosEnabled ? 'ACTIVE' : 'Inactive'"></span>
            </div>
          </div>

          <button 
            type="button" 
            @click="toggleSOS(!sosEnabled)" 
            class="group relative px-5 py-2.5 rounded-lg font-bold text-xs uppercase tracking-wider transition-all duration-300 transform hover:scale-105 active:scale-95"
            :class="sosEnabled ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-[0_0_15px_rgba(16,185,129,0.4)] hover:shadow-[0_0_20px_rgba(16,185,129,0.6)]' : 'bg-gradient-to-r from-red-600 to-red-500 text-white shadow-[0_0_15px_rgba(239,68,68,0.4)] hover:shadow-[0_0_20px_rgba(239,68,68,0.6)]'">
            <span class="relative z-10 flex items-center gap-1.5">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path x-show="!sosEnabled" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                <path x-show="sosEnabled" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"></path>
              </svg>
              <span x-text="sosEnabled ? 'Deactivate' : 'Activate'"></span>
            </span>
            <div class="absolute inset-0 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                 :class="sosEnabled ? 'bg-gradient-to-r from-emerald-500 to-emerald-400' : 'bg-gradient-to-r from-red-500 to-red-400'"></div>
          </button>
        </div>

        <div class="p-2.5 rounded-lg transition-all duration-300 mb-3" 
             :class="sosEnabled ? 'bg-red-500/10 border border-red-500/30' : 'bg-slate-900/40 border border-slate-800'">
          <div class="flex items-start gap-2">
            <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" :class="sosEnabled ? 'text-red-400' : 'text-slate-400'" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <p class="text-xs leading-tight" :class="sosEnabled ? 'text-red-300' : 'text-slate-400'">
              <span x-show="!sosEnabled">Click Activate to trigger the alarm system manually.</span>
              <span x-show="sosEnabled">SOS active. Click Deactivate to return to automatic mode.</span>
            </p>
          </div>
        </div>

        <!-- SOS Activity Log -->
        <div class="space-y-2">
          <div class="flex items-center gap-2">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Recent Activity</span>
          </div>
          <div class="space-y-1.5 max-h-20 overflow-y-auto custom-scrollbar">
            <div class="flex items-center justify-between text-xs p-2 rounded bg-slate-900/50 border border-slate-800" x-show="sosEnabled">
              <span class="text-red-400">SOS Activated</span>
              <span class="text-slate-500" x-text="new Date().toLocaleTimeString()"></span>
            </div>
            <div class="flex items-center justify-between text-xs p-2 rounded bg-slate-900/50 border border-slate-800" x-show="!sosEnabled && lastUpdated !== '-'">
              <span class="text-emerald-400">Normal Mode</span>
              <span class="text-slate-500" x-text="lastUpdated"></span>
            </div>
            <div class="flex items-center justify-between text-xs p-2 rounded bg-slate-900/50 border border-slate-800" x-show="!sosEnabled && lastUpdated === '-'">
              <span class="text-slate-500">No recent activity</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- System Performance -->
    <div class="relative overflow-hidden rounded-xl border border-slate-800 bg-gradient-to-br from-slate-900/60 via-slate-800/40 to-slate-900/60" data-aos="fade-left">
      <div class="absolute inset-0 opacity-5 bg-gradient-to-br from-cyan-500 to-blue-600"></div>
      <div class="relative p-4">
        <div class="flex items-center gap-2 mb-4">
          <div class="h-9 w-9 rounded-lg bg-cyan-500/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
            </svg>
          </div>
          <div>
            <h3 class="text-base font-bold text-white">System Performance</h3>
            <p class="text-xs text-slate-400">Real-time monitoring status</p>
          </div>
        </div>

        <div class="space-y-3">
          <!-- Data Streaming -->
          <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-900/40 border border-slate-800">
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]"></div>
              <span class="text-xs font-medium text-slate-300">Data Streaming</span>
            </div>
            <span class="text-xs font-bold text-emerald-400">LIVE</span>
          </div>

          <!-- Connection Quality -->
          <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-900/40 border border-slate-800">
            <span class="text-xs font-medium text-slate-300">Connection Quality</span>
            <div class="flex items-center gap-1">
              <div class="w-1 h-3 rounded-full bg-emerald-500"></div>
              <div class="w-1 h-4 rounded-full bg-emerald-500"></div>
              <div class="w-1 h-5 rounded-full bg-emerald-500"></div>
              <div class="w-1 h-6 rounded-full bg-emerald-500"></div>
            </div>
          </div>

          <!-- Active Sensors -->
          <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-900/40 border border-slate-800">
            <span class="text-xs font-medium text-slate-300">Active Sensors</span>
            <div class="flex items-center gap-1.5">
              <span class="text-xs font-bold text-white">3</span>
              <span class="text-xs text-slate-500">/</span>
              <span class="text-xs text-slate-400">3</span>
            </div>
          </div>

          <!-- Device Status -->
          <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-900/40 border border-slate-800">
            <span class="text-xs font-medium text-slate-300">Device Status</span>
            <div class="flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </svg>
              <span class="text-xs font-bold text-emerald-400">Online</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Stats Overview -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
    <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800 backdrop-blur-sm">
      <div class="flex items-center justify-between mb-2">
        <span class="text-xs text-slate-400 uppercase tracking-wider">Readings/Min</span>
        <svg class="w-4 h-4 text-cyan-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
      </div>
      <div class="text-2xl font-bold text-white" x-text="stats.readingsPerMin"></div>
    </div>
    <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800 backdrop-blur-sm">
      <div class="flex items-center justify-between mb-2">
        <span class="text-xs text-slate-400 uppercase tracking-wider">Avg Response</span>
        <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
      </div>
      <div class="text-2xl font-bold text-white" x-text="stats.avgResponse + 'ms'"></div>
    </div>
    <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800 backdrop-blur-sm">
      <div class="flex items-center justify-between mb-2">
        <span class="text-xs text-slate-400 uppercase tracking-wider">Alarms Today</span>
        <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
      </div>
      <div class="text-2xl font-bold text-white" x-text="stats.alarmsToday"></div>
    </div>
    <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800 backdrop-blur-sm">
      <div class="flex items-center justify-between mb-2">
        <span class="text-xs text-slate-400 uppercase tracking-wider">Uptime</span>
        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
      </div>
      <div class="text-2xl font-bold text-white" x-text="stats.uptime"></div>
    </div>
  </div>

  <!-- Sensor Cards -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- MQ135 Air Quality -->
    <div class="metric-card" :class="mq135StateClass" data-aos="fade-up" data-aos-delay="0">
      <div class="flex items-start justify-between mb-6">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <div class="h-10 w-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Air Quality</h3>
              <p class="text-xs text-slate-500">MQ-135 Sensor</p>
            </div>
          </div>
        </div>
        <div class="status-indicator" :class="data.auto_air_quality ? 'alert' : 'normal'"></div>
      </div>
      <div class="space-y-4">
        <div>
          <div class="flex items-baseline justify-between mb-2">
            <span class="text-xs text-slate-500 uppercase tracking-wider">Current (EMA)</span>
            <span class="text-xs text-slate-400">PPM</span>
          </div>
          <div class="text-5xl font-bold text-white mb-1" x-text="formatVal(data.air_quality_ema)"></div>
          <div class="h-2 bg-slate-800/50 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full transition-all duration-500" :style="`width: ${Math.min(100, (data.air_quality_ema / 40))}%`"></div>
          </div>
        </div>
        <div class="pt-4 border-t border-slate-800/50 space-y-3">
          <div class="flex items-center justify-between">
            <div class="text-xs text-slate-500 uppercase tracking-wider">Raw Value</div>
            <div class="text-xl font-semibold text-slate-300" x-text="formatVal(data.air_quality_raw)"></div>
          </div>
          <div class="flex items-center justify-between">
            <div class="text-xs text-slate-500 uppercase tracking-wider">Trend (1m)</div>
            <div class="flex items-center gap-1">
              <svg x-show="trends.mq135 > 0" class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
              <svg x-show="trends.mq135 < 0" class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
              <svg x-show="trends.mq135 === 0" class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
              <span class="text-xs font-semibold" :class="trends.mq135 > 0 ? 'text-red-400' : trends.mq135 < 0 ? 'text-emerald-400' : 'text-slate-400'" x-text="Math.abs(trends.mq135).toFixed(1) + '%'"></span>
            </div>
          </div>
          <div class="flex items-center justify-between">
            <div class="text-xs text-slate-500 uppercase tracking-wider">Avg (5m)</div>
            <div class="text-sm font-semibold text-blue-300" x-text="formatVal(averages.mq135)"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- MQ7 Carbon Monoxide -->
    <div class="metric-card" :class="mq7StateClass" data-aos="fade-up" data-aos-delay="100">
      <div class="flex items-start justify-between mb-6">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <div class="h-10 w-10 rounded-xl bg-orange-500/20 flex items-center justify-center">
              <svg class="w-5 h-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Carbon Monoxide</h3>
              <p class="text-xs text-slate-500">MQ-7 Sensor</p>
            </div>
          </div>
        </div>
        <div class="status-indicator" :class="data.auto_carbon ? 'alert' : 'normal'"></div>
      </div>
      <div class="space-y-4">
        <div>
          <div class="flex items-baseline justify-between mb-2">
            <span class="text-xs text-slate-500 uppercase tracking-wider">Current (EMA)</span>
            <span class="text-xs text-slate-400">PPM</span>
          </div>
          <div class="text-5xl font-bold text-white mb-1" x-text="formatVal(data.carbon_ema)"></div>
          <div class="h-2 bg-slate-800/50 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-orange-500 to-red-400 rounded-full transition-all duration-500" :style="`width: ${Math.min(100, (data.carbon_ema / 40))}%`"></div>
          </div>
        </div>
        <div class="pt-4 border-t border-slate-800/50 space-y-3">
          <div class="flex items-center justify-between">
            <div class="text-xs text-slate-500 uppercase tracking-wider">Raw Value</div>
            <div class="text-xl font-semibold text-slate-300" x-text="formatVal(data.carbon_raw)"></div>
          </div>
          <div class="flex items-center justify-between">
            <div class="text-xs text-slate-500 uppercase tracking-wider">Trend (1m)</div>
            <div class="flex items-center gap-1">
              <svg x-show="trends.mq7 > 0" class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
              <svg x-show="trends.mq7 < 0" class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
              <svg x-show="trends.mq7 === 0" class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
              <span class="text-xs font-semibold" :class="trends.mq7 > 0 ? 'text-red-400' : trends.mq7 < 0 ? 'text-emerald-400' : 'text-slate-400'" x-text="Math.abs(trends.mq7).toFixed(1) + '%'"></span>
            </div>
          </div>
          <div class="flex items-center justify-between">
            <div class="text-xs text-slate-500 uppercase tracking-wider">Avg (5m)</div>
            <div class="text-sm font-semibold text-orange-300" x-text="formatVal(averages.mq7)"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Temperature -->
    <div class="metric-card" :class="tempValid ? 'border-emerald-500/30' : 'border-red-500/30'" data-aos="fade-up" data-aos-delay="200">
      <div class="flex items-start justify-between mb-6">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <div class="h-10 w-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
              <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a3 3 0 00-3 3v6a4 4 0 108 0V5a3 3 0 00-3-3zm-1 11.7A3.5 3.5 0 019 11V5a1 1 0 112 0v6a3.5 3.5 0 01-.1 2.7A1.5 1.5 0 0110 15a1.5 1.5 0 01-1-2.3z" clip-rule="evenodd"></path></svg>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Temperature</h3>
              <p class="text-xs text-slate-500">LM35 Sensor</p>
            </div>
          </div>
        </div>
        <div class="status-indicator" :class="data.auto_temperature ? 'alert' : (tempValid ? 'normal' : 'inactive')"></div>
      </div>
      <div class="space-y-4">
        <div>
          <div class="flex items-baseline justify-between mb-2">
            <span class="text-xs text-slate-500 uppercase tracking-wider">Current (EMA)</span>
            <span class="text-xs text-slate-400">°C</span>
          </div>
          <div class="text-5xl font-bold text-white mb-1" x-text="formatVal(data.temperature_ema)"></div>
          <div class="h-2 bg-slate-800/50 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500" :style="`width: ${Math.min(100, (data.temperature_ema / 1))}%`"></div>
          </div>
        </div>
        <div class="pt-4 border-t border-slate-800/50 space-y-3">
          <div class="flex items-center justify-between">
            <div class="text-xs text-slate-500 uppercase tracking-wider">Raw Value</div>
            <div class="text-xl font-semibold text-slate-300" x-text="formatVal(data.temperature_c)"></div>
          </div>
          <div class="flex items-center justify-between">
            <div class="text-xs text-slate-500 uppercase tracking-wider">Trend (1m)</div>
            <div class="flex items-center gap-1">
              <svg x-show="trends.temp > 0" class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
              <svg x-show="trends.temp < 0" class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
              <svg x-show="trends.temp === 0" class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
              <span class="text-xs font-semibold" :class="trends.temp > 0 ? 'text-red-400' : trends.temp < 0 ? 'text-emerald-400' : 'text-slate-400'" x-text="Math.abs(trends.temp).toFixed(1) + '%'"></span>
            </div>
          </div>
          <div class="flex items-center justify-between">
            <div class="text-xs text-slate-500 uppercase tracking-wider">Avg (5m)</div>
            <div class="text-sm font-semibold text-emerald-300" x-text="formatVal(averages.temp) + ' °C'"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Details -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Peak Values Today -->
    <div class="control-card" data-aos="fade-up">
      <div class="flex items-center gap-3 mb-6">
        <div class="h-10 w-10 rounded-xl bg-red-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Peak Values Today</h3>
          <p class="text-xs text-slate-400">Maximum readings recorded</p>
        </div>
      </div>
      <div class="grid grid-cols-3 gap-4">
        <div class="p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="text-xs text-slate-400 mb-1">MQ-135 Max</div>
          <div class="text-xl font-bold text-blue-400" x-text="formatVal(peaks.mq135)"></div>
          <div class="text-xs text-slate-500 mt-1" x-text="peaks.mq135Time"></div>
        </div>
        <div class="p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="text-xs text-slate-400 mb-1">MQ-7 Max</div>
          <div class="text-xl font-bold text-orange-400" x-text="formatVal(peaks.mq7)"></div>
          <div class="text-xs text-slate-500 mt-1" x-text="peaks.mq7Time"></div>
        </div>
        <div class="p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="text-xs text-slate-400 mb-1">Temp Max</div>
          <div class="text-xl font-bold text-emerald-400" x-text="formatVal(peaks.temp) + '°C'"></div>
          <div class="text-xs text-slate-500 mt-1" x-text="peaks.tempTime"></div>
        </div>
      </div>
    </div>

    <!-- Environmental Assessment -->
    <div class="control-card" data-aos="fade-up" data-aos-delay="100">
      <div class="flex items-center gap-3 mb-6">
        <div class="h-10 w-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Air Quality Index</h3>
          <p class="text-xs text-slate-400">Overall environmental assessment</p>
        </div>
      </div>
      <div class="space-y-4">
        <div class="text-center">
          <div class="text-6xl font-bold mb-2" :class="airQualityColor" x-text="airQualityIndex"></div>
          <div class="text-sm font-semibold uppercase tracking-wider" :class="airQualityColor" x-text="airQualityLevel"></div>
          <div class="text-xs text-slate-400 mt-2" x-text="airQualityDescription"></div>
        </div>
        <div class="relative h-3 bg-slate-800 rounded-full overflow-hidden">
          <div class="absolute inset-0 flex">
            <div class="flex-1 bg-emerald-500"></div>
            <div class="flex-1 bg-yellow-500"></div>
            <div class="flex-1 bg-orange-500"></div>
            <div class="flex-1 bg-red-500"></div>
            <div class="flex-1 bg-purple-500"></div>
          </div>
          <div class="absolute top-0 h-full w-1 bg-white shadow-lg transition-all duration-500" :style="`left: ${airQualityIndex}%`"></div>
        </div>
        <div class="flex justify-between text-xs text-slate-500">
          <span>Good</span>
          <span>Moderate</span>
          <span>Poor</span>
          <span>Unhealthy</span>
          <span>Hazardous</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Mini Charts -->
  <div class="control-card" data-aos="fade-up">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-cyan-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Real-Time Trends (Last 60s)</h3>
          <p class="text-xs text-slate-400">Live sensor data visualization</p>
        </div>
      </div>
      <div class="flex items-center gap-2 text-xs">
        <div class="flex items-center gap-1">
          <div class="w-3 h-3 rounded-sm bg-blue-500"></div>
          <span class="text-slate-400">MQ135</span>
        </div>
        <div class="flex items-center gap-1">
          <div class="w-3 h-3 rounded-sm bg-orange-500"></div>
          <span class="text-slate-400">MQ7</span>
        </div>
        <div class="flex items-center gap-1">
          <div class="w-3 h-3 rounded-sm bg-emerald-500"></div>
          <span class="text-slate-400">Temp</span>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-4 rounded-lg bg-slate-900/40 border border-slate-800">
        <div class="text-xs text-slate-400 mb-2 uppercase tracking-wider">MQ-135 Sparkline</div>
        <div style="height: 80px; position: relative;">
          <canvas id="mini-chart-mq135"></canvas>
        </div>
      </div>
      <div class="p-4 rounded-lg bg-slate-900/40 border border-slate-800">
        <div class="text-xs text-slate-400 mb-2 uppercase tracking-wider">MQ-7 Sparkline</div>
        <div style="height: 80px; position: relative;">
          <canvas id="mini-chart-mq7"></canvas>
        </div>
      </div>
      <div class="p-4 rounded-lg bg-slate-900/40 border border-slate-800">
        <div class="text-xs text-slate-400 mb-2 uppercase tracking-wider">Temperature Sparkline</div>
        <div style="height: 80px; position: relative;">
          <canvas id="mini-chart-temp"></canvas>
        </div>
      </div>
    </div>
  </div>
</section>
