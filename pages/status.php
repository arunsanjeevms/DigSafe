<?php
// System Status page
?>
<section x-data="statusPage()" x-init="init()" class="space-y-6">
  <!-- Page Header -->
  <div class="flex items-center justify-between" data-aos="fade-down">
    <div>
      <h1 class="text-3xl font-bold text-white mb-1">System Status</h1>
      <p class="text-slate-400 text-sm">Device health, connectivity, and diagnostics</p>
    </div>
    <div class="flex items-center gap-3">
      <button @click="refresh()" class="px-4 py-2 rounded-lg bg-slate-900/60 border border-slate-800 text-slate-300 hover:bg-slate-800 transition">
        <svg class="w-4 h-4 inline mr-2" :class="refreshing ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        Refresh
      </button>
      <div class="px-4 py-2 rounded-xl bg-slate-900/60 border border-slate-800">
        <div class="flex items-center gap-2">
          <div class="w-2 h-2 rounded-full" :class="connectionStatus === 'online' ? 'bg-emerald-400 animate-pulse' : 'bg-red-400'"></div>
          <span class="text-sm text-slate-300" x-text="connectionStatus === 'online' ? 'Online' : 'Offline'"></span>
        </div>
      </div>
    </div>
  </div>

  <!-- System Health Overview -->
  <div class="control-card" data-aos="fade-up">
    <div class="flex items-center gap-3 mb-6">
      <div class="h-10 w-10 rounded-xl bg-cyan-500/20 flex items-center justify-center">
        <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
      </div>
      <div>
        <h2 class="text-xl font-bold text-white">System Health</h2>
        <p class="text-xs text-slate-400">Overall device status and performance</p>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs text-slate-400">System Health</span>
          <div class="w-2 h-2 rounded-full" :class="systemHealth >= 80 ? 'bg-emerald-400' : systemHealth >= 50 ? 'bg-yellow-400' : 'bg-red-400'"></div>
        </div>
        <div class="text-3xl font-bold text-white mb-1" x-text="systemHealth + '%'"></div>
        <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
          <div class="h-full transition-all duration-500" :class="systemHealth >= 80 ? 'bg-emerald-500' : systemHealth >= 50 ? 'bg-yellow-500' : 'bg-red-500'" :style="`width: ${systemHealth}%`"></div>
        </div>
      </div>
      <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs text-slate-400">Response Time</span>
          <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
        </div>
        <div class="text-3xl font-bold text-white" x-text="responseTime + 'ms'"></div>
        <div class="text-xs" :class="responseTime < 100 ? 'text-emerald-400' : responseTime < 500 ? 'text-yellow-400' : 'text-red-400'" x-text="responseTime < 100 ? 'Excellent' : responseTime < 500 ? 'Good' : 'Slow'"></div>
      </div>
      <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs text-slate-400">Data Packets</span>
          <svg class="w-4 h-4 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"></path><path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"></path><path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"></path></svg>
        </div>
        <div class="text-3xl font-bold text-white" x-text="dataPackets"></div>
        <div class="text-xs text-slate-400">Total received</div>
      </div>
      <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs text-slate-400">Error Rate</span>
          <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        </div>
        <div class="text-3xl font-bold text-white" x-text="errorRate + '%'"></div>
        <div class="text-xs" :class="errorRate < 5 ? 'text-emerald-400' : errorRate < 10 ? 'text-yellow-400' : 'text-red-400'" x-text="errorRate < 5 ? 'Stable' : errorRate < 10 ? 'Warning' : 'Critical'"></div>
      </div>
    </div>
  </div>

  <!-- Device Information & Connectivity -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="control-card" data-aos="fade-up">
      <div class="flex items-center gap-3 mb-4">
        <div class="h-10 w-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Device Information</h3>
          <p class="text-xs text-slate-400">ESP32 hardware details</p>
        </div>
      </div>
      <div class="space-y-3">
        <div class="flex items-center justify-between p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"></path></svg>
            <span class="text-sm text-slate-400">Device Name</span>
          </div>
          <span class="text-sm font-semibold text-white">DigSafe ESP32</span>
        </div>
        <div class="flex items-center justify-between p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 7H7v6h6V7z"></path><path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"></path></svg>
            <span class="text-sm text-slate-400">Firmware</span>
          </div>
          <span class="text-sm font-semibold text-white" x-text="status.firmware || 'v1.0.0'"></span>
        </div>
        <div class="flex items-center justify-between p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
            <span class="text-sm text-slate-400">Uptime</span>
          </div>
          <span class="text-sm font-semibold text-white" x-text="status.uptime || '-'"></span>
        </div>
        <div class="flex items-center justify-between p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            <span class="text-sm text-slate-400">Memory Free</span>
          </div>
          <span class="text-sm font-semibold text-white" x-text="status.free_memory || '-'"></span>
        </div>
      </div>
    </div>

    <div class="control-card" data-aos="fade-up" data-aos-delay="100">
      <div class="flex items-center gap-3 mb-4">
        <div class="h-10 w-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Network Connectivity</h3>
          <p class="text-xs text-slate-400">WiFi and communication status</p>
        </div>
      </div>
      <div class="space-y-3">
        <div class="flex items-center justify-between p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.778 8.222c-4.296-4.296-11.26-4.296-15.556 0A1 1 0 01.808 6.808c5.076-5.077 13.308-5.077 18.384 0a1 1 0 01-1.414 1.414zM14.95 11.05a7 7 0 00-9.9 0 1 1 0 01-1.414-1.414 9 9 0 0112.728 0 1 1 0 01-1.414 1.414zM12.12 13.88a3 3 0 00-4.242 0 1 1 0 01-1.415-1.415 5 5 0 017.072 0 1 1 0 01-1.415 1.415zM9 16a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
            <span class="text-sm text-slate-400">WiFi Status</span>
          </div>
          <span class="text-sm font-semibold" :class="status.wifi === 'connected' ? 'text-emerald-400' : 'text-red-400'" x-text="status.wifi || 'Unknown'"></span>
        </div>
        <div class="flex items-center justify-between p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 3.636a1 1 0 010 1.414 7 7 0 000 9.9 1 1 0 11-1.414 1.414 9 9 0 010-12.728 1 1 0 011.414 0zm9.9 0a1 1 0 011.414 0 9 9 0 010 12.728 1 1 0 11-1.414-1.414 7 7 0 000-9.9 1 1 0 010-1.414zM7.879 6.464a1 1 0 010 1.414 3 3 0 000 4.243 1 1 0 11-1.415 1.414 5 5 0 010-7.07 1 1 0 011.415 0zm4.242 0a1 1 0 011.415 0 5 5 0 010 7.072 1 1 0 01-1.415-1.415 3 3 0 000-4.242 1 1 0 010-1.415zM10 9a1 1 0 011 1v.01a1 1 0 11-2 0V10a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
            <span class="text-sm text-slate-400">Signal Strength</span>
          </div>
          <span class="text-sm font-semibold text-white" x-text="status.signal_strength || '-'"></span>
        </div>
        <div class="flex items-center justify-between p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="text-sm text-slate-400">IP Address</span>
          </div>
          <span class="text-sm font-semibold text-white" x-text="status.ip || '-'"></span>
        </div>
        <div class="flex items-center justify-between p-3 rounded-lg bg-slate-900/40 border border-slate-800">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
            <span class="text-sm text-slate-400">MAC Address</span>
          </div>
          <span class="text-xs font-mono text-white" x-text="status.mac_address || '-'"></span>
        </div>
      </div>
    </div>
  </div>

  <!-- Sensor Diagnostics -->
  <div class="control-card" data-aos="fade-up">
    <div class="flex items-center gap-3 mb-6">
      <div class="h-10 w-10 rounded-xl bg-orange-500/20 flex items-center justify-center">
        <svg class="w-5 h-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
      </div>
      <div>
        <h2 class="text-xl font-bold text-white">Sensor Diagnostics</h2>
        <p class="text-xs text-slate-400">Real-time sensor health monitoring</p>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <div class="h-8 w-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
              <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
            </div>
            <span class="text-sm font-bold text-white">MQ-135</span>
          </div>
          <div class="status-indicator" :class="sensorStatus.mq135 ? 'active' : ''"></div>
        </div>
        <div class="space-y-1 text-xs">
          <div class="flex justify-between">
            <span class="text-slate-400">Status:</span>
            <span :class="sensorStatus.mq135 ? 'text-emerald-400' : 'text-red-400'" x-text="sensorStatus.mq135 ? 'Online' : 'Offline'"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Last Reading:</span>
            <span class="text-slate-300" x-text="sensorReadings.mq135 || '-'"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Calibrated:</span>
            <span class="text-slate-300" x-text="sensorCalibration.mq135 ? 'Yes' : 'No'"></span>
          </div>
        </div>
      </div>
      <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <div class="h-8 w-8 rounded-lg bg-orange-500/20 flex items-center justify-center">
              <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
            </div>
            <span class="text-sm font-bold text-white">MQ-7</span>
          </div>
          <div class="status-indicator" :class="sensorStatus.mq7 ? 'active' : ''"></div>
        </div>
        <div class="space-y-1 text-xs">
          <div class="flex justify-between">
            <span class="text-slate-400">Status:</span>
            <span :class="sensorStatus.mq7 ? 'text-emerald-400' : 'text-red-400'" x-text="sensorStatus.mq7 ? 'Online' : 'Offline'"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Last Reading:</span>
            <span class="text-slate-300" x-text="sensorReadings.mq7 || '-'"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Calibrated:</span>
            <span class="text-slate-300" x-text="sensorCalibration.mq7 ? 'Yes' : 'No'"></span>
          </div>
        </div>
      </div>
      <div class="p-4 rounded-xl bg-slate-900/40 border border-slate-800">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <div class="h-8 w-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
              <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a3 3 0 00-3 3v6a4 4 0 108 0V5a3 3 0 00-3-3zm-1 11.7A3.5 3.5 0 019 11V5a1 1 0 112 0v6a3.5 3.5 0 01-.1 2.7A1.5 1.5 0 0110 15a1.5 1.5 0 01-1-2.3z" clip-rule="evenodd"></path></svg>
            </div>
            <span class="text-sm font-bold text-white">LM35</span>
          </div>
          <div class="status-indicator" :class="sensorStatus.temp ? 'active' : ''"></div>
        </div>
        <div class="space-y-1 text-xs">
          <div class="flex justify-between">
            <span class="text-slate-400">Status:</span>
            <span :class="sensorStatus.temp ? 'text-emerald-400' : 'text-red-400'" x-text="sensorStatus.temp ? 'Online' : 'Offline'"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Last Reading:</span>
            <span class="text-slate-300" x-text="sensorReadings.temp || '-'"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Valid:</span>
            <span class="text-slate-300" x-text="status.sensor_valid ? 'Yes' : 'No'"></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Activity Log -->
  <div class="control-card" data-aos="fade-up">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-white">Activity Log</h3>
          <p class="text-xs text-slate-400">Recent system events and alarms</p>
        </div>
      </div>
      <button @click="clearLogs()" class="px-3 py-1.5 text-xs rounded-lg bg-slate-900/60 border border-slate-800 text-slate-400 hover:text-slate-300 transition">
        Clear Logs
      </button>
    </div>
    <div class="space-y-2 max-h-80 overflow-y-auto custom-scrollbar">
      <template x-if="activityLog.length === 0">
        <div class="p-6 text-center text-slate-500 text-sm">
          <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          No activity logged yet
        </div>
      </template>
      <template x-for="(log, idx) in activityLog" :key="idx">
        <div class="flex items-start gap-3 p-3 rounded-lg bg-slate-900/40 border border-slate-800 hover:border-slate-700 transition">
          <div class="mt-1" :class="log.type === 'alarm' ? 'text-red-400' : log.type === 'warning' ? 'text-yellow-400' : log.type === 'success' ? 'text-emerald-400' : 'text-blue-400'">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <template x-if="log.type === 'alarm'">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
              </template>
              <template x-if="log.type === 'success'">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </template>
              <template x-if="log.type === 'info'">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
              </template>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
              <p class="text-sm text-white font-medium" x-text="log.message"></p>
              <span class="text-xs text-slate-500 whitespace-nowrap" x-text="log.time"></span>
            </div>
            <p class="text-xs text-slate-400 mt-1" x-text="log.details" x-show="log.details"></p>
          </div>
        </div>
      </template>
    </div>
  </div>
</section>
