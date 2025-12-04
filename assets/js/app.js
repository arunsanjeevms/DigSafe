// Alpine.js stores and UI logic
function appShell() {
  return {
    theme: 'dark',
    showTeamModal: false,
    init() {
      this.theme = localStorage.getItem('theme') || 'dark';
    },
    toggleTheme() {
      this.theme = this.theme === 'dark' ? 'light' : 'dark';
      localStorage.setItem('theme', this.theme);
    }
  }
}

function dashboard() {
  return {
    data: {},
    alarmActive: false,
    sosEnabled: false,
    autoDetect: { mq135: true, mq7: true, temp: true },
    lastUpdated: '-',
    tempValid: true,
    stats: { readingsPerMin: 60, avgResponse: 0, alarmsToday: 0, uptime: '-' },
    trends: { mq135: 0, mq7: 0, temp: 0 },
    averages: { mq135: 0, mq7: 0, temp: 0 },
    peaks: { mq135: 0, mq7: 0, temp: 0, mq135Time: '-', mq7Time: '-', tempTime: '-' },
    airQualityIndex: 50,
    airQualityLevel: 'Good',
    airQualityColor: 'text-emerald-400',
    airQualityDescription: 'Air quality is satisfactory',
    miniCharts: { mq135: null, mq7: null, temp: null },
    readingCount: 0,
    startTime: Date.now(),
    get mq135StateClass() { return this.stateClass(this.data.air_quality_ema); },
    get mq7StateClass() { return this.stateClass(this.data.carbon_ema); },
    stateClass(val) {
      if (val == null) return 'border-slate-700';
      if (val > 80) return 'border-danger-500 shadow-neon';
      if (val > 50) return 'border-neon-600 shadow-neon';
      return 'border-slate-700';
    },
    formatVal(v) { return v == null ? '-' : Number(v).toFixed(2); },
    timer: null,
    async poll() {
      const startPoll = Date.now();
      try {
        const d = await DeviceAPI.getData();
        this.data = d;
        this.alarmActive = d.alarm === 1;
        this.tempValid = d.temperature_valid === 1;
        this.sosEnabled = d.manual_sos === 1;
        this.lastUpdated = new Date().toLocaleTimeString();
        this.readingCount++;

        // Update stats
        const responseTime = Date.now() - startPoll;
        this.stats.avgResponse = Math.round((this.stats.avgResponse * 0.8) + (responseTime * 0.2));
        if (d.alarm === 1 && !this.lastAlarmState) {
          this.stats.alarmsToday++;
        }
        this.lastAlarmState = d.alarm === 1;
        this.stats.uptime = this.formatUptime(Date.now() - this.startTime);

        // push into global history buffer for charts
        window.IOT_HISTORY = window.IOT_HISTORY || { mq135: [], mq7: [], temp: [] };
        const h = window.IOT_HISTORY;
        h.mq135.unshift(d.air_quality_ema ?? null);
        h.mq7.unshift(d.carbon_ema ?? null);
        h.temp.unshift(d.temperature_ema ?? null);
        const maxLen = 240; // store last 240 seconds
        if (h.mq135.length > maxLen) h.mq135.length = maxLen;
        if (h.mq7.length > maxLen) h.mq7.length = maxLen;
        if (h.temp.length > maxLen) h.temp.length = maxLen;

        // Calculate trends (1 minute comparison)
        if (h.mq135.length > 60) {
          this.trends.mq135 = ((d.air_quality_ema - h.mq135[60]) / h.mq135[60]) * 100 || 0;
          this.trends.mq7 = ((d.carbon_ema - h.mq7[60]) / h.mq7[60]) * 100 || 0;
          this.trends.temp = ((d.temperature_ema - h.temp[60]) / h.temp[60]) * 100 || 0;
        }

        // Calculate 5-minute averages
        const avg5m = Math.min(h.mq135.length, 300);
        if (avg5m > 0) {
          this.averages.mq135 = h.mq135.slice(0, avg5m).reduce((a, b) => a + (b || 0), 0) / avg5m;
          this.averages.mq7 = h.mq7.slice(0, avg5m).reduce((a, b) => a + (b || 0), 0) / avg5m;
          this.averages.temp = h.temp.slice(0, avg5m).reduce((a, b) => a + (b || 0), 0) / avg5m;
        }

        // Track peak values
        if (d.air_quality_ema > this.peaks.mq135) {
          this.peaks.mq135 = d.air_quality_ema;
          this.peaks.mq135Time = new Date().toLocaleTimeString();
        }
        if (d.carbon_ema > this.peaks.mq7) {
          this.peaks.mq7 = d.carbon_ema;
          this.peaks.mq7Time = new Date().toLocaleTimeString();
        }
        if (d.temperature_ema > this.peaks.temp) {
          this.peaks.temp = d.temperature_ema;
          this.peaks.tempTime = new Date().toLocaleTimeString();
        }

        // Calculate Air Quality Index
        this.calculateAQI(d);

        // Update mini charts
        this.updateMiniCharts();
      } catch (e) {
        console.warn(e);
      }
    },
    calculateAQI(d) {
      // Simplified AQI calculation based on sensor values
      const mq135Factor = Math.min(100, (d.air_quality_ema / 400) * 100);
      const mq7Factor = Math.min(100, (d.carbon_ema / 200) * 100);
      const tempFactor = d.temperature_ema > 35 ? Math.min(100, ((d.temperature_ema - 35) / 15) * 100) : 0;
      
      this.airQualityIndex = Math.round((mq135Factor * 0.5 + mq7Factor * 0.4 + tempFactor * 0.1));
      
      if (this.airQualityIndex <= 20) {
        this.airQualityLevel = 'Good';
        this.airQualityColor = 'text-emerald-400';
        this.airQualityDescription = 'Air quality is satisfactory, and air pollution poses little or no risk';
      } else if (this.airQualityIndex <= 40) {
        this.airQualityLevel = 'Moderate';
        this.airQualityColor = 'text-yellow-400';
        this.airQualityDescription = 'Air quality is acceptable for most people';
      } else if (this.airQualityIndex <= 60) {
        this.airQualityLevel = 'Poor';
        this.airQualityColor = 'text-orange-400';
        this.airQualityDescription = 'Members of sensitive groups may experience health effects';
      } else if (this.airQualityIndex <= 80) {
        this.airQualityLevel = 'Unhealthy';
        this.airQualityColor = 'text-red-400';
        this.airQualityDescription = 'Everyone may begin to experience health effects';
      } else {
        this.airQualityLevel = 'Hazardous';
        this.airQualityColor = 'text-purple-400';
        this.airQualityDescription = 'Health alert: everyone may experience serious health effects';
      }
    },
    updateMiniCharts() {
      const h = window.IOT_HISTORY;
      if (!h || h.mq135.length < 2) return;

      const data60 = Math.min(60, h.mq135.length);
      const labels = Array.from({length: data60}, (_, i) => '');

      // Update MQ135 chart
      if (this.miniCharts.mq135) {
        this.miniCharts.mq135.data.labels = labels;
        this.miniCharts.mq135.data.datasets[0].data = h.mq135.slice(0, data60).reverse();
        this.miniCharts.mq135.update('none');
      }

      // Update MQ7 chart
      if (this.miniCharts.mq7) {
        this.miniCharts.mq7.data.labels = labels;
        this.miniCharts.mq7.data.datasets[0].data = h.mq7.slice(0, data60).reverse();
        this.miniCharts.mq7.update('none');
      }

      // Update Temp chart
      if (this.miniCharts.temp) {
        this.miniCharts.temp.data.labels = labels;
        this.miniCharts.temp.data.datasets[0].data = h.temp.slice(0, data60).reverse();
        this.miniCharts.temp.update('none');
      }
    },
    createMiniChart(canvasId, color) {
      const ctx = document.getElementById(canvasId);
      if (!ctx) return null;
      
      return new Chart(ctx, {
        type: 'line',
        data: {
          labels: [],
          datasets: [{
            data: [],
            borderColor: color,
            backgroundColor: color + '20',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          aspectRatio: 3,
          plugins: { legend: { display: false }, tooltip: { enabled: false } },
          scales: {
            x: { display: false },
            y: { display: false, beginAtZero: false }
          },
          animation: false,
          elements: {
            line: {
              borderWidth: 2
            }
          }
        }
      });
    },
    formatUptime(ms) {
      const seconds = Math.floor(ms / 1000);
      const minutes = Math.floor(seconds / 60);
      const hours = Math.floor(minutes / 60);
      if (hours > 0) return `${hours}h ${minutes % 60}m`;
      if (minutes > 0) return `${minutes}m ${seconds % 60}s`;
      return `${seconds}s`;
    },
    async toggleSOS(on) {
      try {
        if (on) await DeviceAPI.sosEnable(); else await DeviceAPI.sosClear();
        this.sosEnabled = on;
      } catch (e) { console.warn(e); }
    },
    init() {
      this.poll();
      this.timer = setInterval(() => this.poll(), 1000);
      
      // Initialize mini charts
      setTimeout(() => {
        this.miniCharts.mq135 = this.createMiniChart('mini-chart-mq135', '#3b82f6');
        this.miniCharts.mq7 = this.createMiniChart('mini-chart-mq7', '#f97316');
        this.miniCharts.temp = this.createMiniChart('mini-chart-temp', '#10b981');
      }, 500);
    }
  }
}

function historyPage() {
  let chart135, chart7, chartTemp;
  const baseOpts = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: 'index', intersect: false },
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: 'rgba(15, 23, 42, 0.95)',
        titleColor: '#fff',
        bodyColor: '#94a3b8',
        borderColor: 'rgba(0, 209, 255, 0.3)',
        borderWidth: 1,
        padding: 12,
        displayColors: false,
        callbacks: {
          label: function(context) {
            return context.parsed.y ? context.parsed.y.toFixed(2) + ' ' + context.dataset.unit : 'No data';
          }
        }
      }
    },
    scales: {
      x: {
        grid: { color: 'rgba(148, 163, 184, 0.1)', drawBorder: false },
        ticks: { color: '#64748b', font: { size: 11 } }
      },
      y: {
        grid: { color: 'rgba(148, 163, 184, 0.1)', drawBorder: false },
        ticks: { color: '#64748b', font: { size: 11 } }
      }
    }
  };
  function createChart(ctx, label, color, unit) {
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, color + '40');
    gradient.addColorStop(1, color + '00');
    return new Chart(ctx, {
      type: 'line',
      data: {
        labels: [],
        datasets: [{
          label,
          data: [],
          borderColor: color,
          backgroundColor: gradient,
          borderWidth: 2.5,
          fill: true,
          tension: 0.4,
          pointRadius: 0,
          pointHoverRadius: 6,
          pointHoverBackgroundColor: color,
          pointHoverBorderColor: '#fff',
          pointHoverBorderWidth: 2,
          unit: unit
        }]
      },
      options: baseOpts
    });
  }
  async function loadRange(range) {
    // Simple client-side history buffer using recent /data polls
    const points = range === '24h' ? 240 : range === '30m' ? 120 : 60;
    const labels = [];
    const mq135 = [];
    const mq7 = [];
    const temp = [];
    for (let i = points - 1; i >= 0; i--) {
      labels.push(`-${i}s`);
      // fallback: reuse last known if not enough history yet
      mq135.push(window.IOT_HISTORY?.mq135[i] ?? null);
      mq7.push(window.IOT_HISTORY?.mq7[i] ?? null);
      temp.push(window.IOT_HISTORY?.temp[i] ?? null);
    }
    chart135.data.labels = labels;
    chart7.data.labels = labels;
    chartTemp.data.labels = labels;
    chart135.data.datasets[0].data = mq135;
    chart7.data.datasets[0].data = mq7;
    chartTemp.data.datasets[0].data = temp;
    chart135.update(); chart7.update(); chartTemp.update();
  }
  return {
    range: '5m',
    setRange(r) { this.range = r; loadRange(r); },
    init() {
      chart135 = createChart(document.getElementById('chart-mq135'), 'Air Quality', '#3b82f6', 'PPM');
      chart7 = createChart(document.getElementById('chart-mq7'), 'Carbon Monoxide', '#f97316', 'PPM');
      chartTemp = createChart(document.getElementById('chart-temp'), 'Temperature', '#10b981', '°C');
      loadRange(this.range);
    }
  }
}

function calibrationPage() {
  return {
    calibrating: false,
    progress: 0,
    thresholds: {
      mq135_ema: null,
      mq135_threshold: 400,
      mq7_ema: null,
      mq7_threshold: 200,
      temp_ema: null,
      temp_threshold: 45
    },
    liveData: { mq135: '-', mq7: '-', temp: '-' },
    history: [],
    pollInterval: null,
    async init() {
      // Load saved thresholds from localStorage
      const saved = localStorage.getItem('digsafe_thresholds');
      if (saved) {
        try {
          const parsed = JSON.parse(saved);
          this.thresholds = { ...this.thresholds, ...parsed };
        } catch (e) { console.warn(e); }
      }
      // Load calibration history
      const savedHistory = localStorage.getItem('digsafe_calibration_history');
      if (savedHistory) {
        try {
          this.history = JSON.parse(savedHistory);
        } catch (e) { console.warn(e); }
      }
      // Start polling live data
      this.pollLiveData();
      this.pollInterval = setInterval(() => this.pollLiveData(), 2000);
    },
    async pollLiveData() {
      try {
        const d = await DeviceAPI.getData();
        this.liveData = {
          mq135: d.air_quality_ema?.toFixed(2) ?? '-',
          mq7: d.carbon_ema?.toFixed(2) ?? '-',
          temp: d.temperature_ema?.toFixed(2) ?? '-'
        };
      } catch (e) { console.warn(e); }
    },
    async startCalibration() {
      if (this.calibrating) return;
      this.calibrating = true;
      this.progress = 0;
      const durationMs = 6000;
      const step = 100;
      const totalSteps = durationMs / step;
      let count = 0;
      const timer = setInterval(() => {
        count++;
        this.progress = Math.min(100, Math.round(count / totalSteps * 100));
        if (count >= totalSteps) {
          clearInterval(timer);
          this.calibrating = false;
        }
      }, step);
      try {
        const res = await DeviceAPI.calibrate();
        const newThresholds = res.thresholds || {};
        this.thresholds = { ...this.thresholds, ...newThresholds };
        // Add to history
        const historyItem = {
          time: new Date().toLocaleString(),
          success: true,
          mq135: newThresholds.mq135_ema?.toFixed(2) || '-',
          mq7: newThresholds.mq7_ema?.toFixed(2) || '-',
          temp: newThresholds.temp_ema?.toFixed(2) || '-'
        };
        this.history.unshift(historyItem);
        if (this.history.length > 5) this.history = this.history.slice(0, 5);
        localStorage.setItem('digsafe_calibration_history', JSON.stringify(this.history));
        // Auto-save thresholds
        this.saveThresholds();
      } catch (e) {
        console.warn(e);
        // Add failed entry to history
        this.history.unshift({
          time: new Date().toLocaleString(),
          success: false,
          mq135: '-',
          mq7: '-',
          temp: '-'
        });
        if (this.history.length > 5) this.history = this.history.slice(0, 5);
        localStorage.setItem('digsafe_calibration_history', JSON.stringify(this.history));
      }
    },
    saveThresholds() {
      localStorage.setItem('digsafe_thresholds', JSON.stringify(this.thresholds));
      // Show feedback (you can enhance this with a toast notification)
      console.log('Thresholds saved successfully');
    },
    resetThresholds() {
      this.thresholds = {
        mq135_ema: this.thresholds.mq135_ema,
        mq135_threshold: 400,
        mq7_ema: this.thresholds.mq7_ema,
        mq7_threshold: 200,
        temp_ema: this.thresholds.temp_ema,
        temp_threshold: 45
      };
      localStorage.removeItem('digsafe_thresholds');
    }
  }
}

function statusPage() {
  return {
    status: {},
    refreshing: false,
    connectionStatus: 'online',
    systemHealth: 0,
    responseTime: 0,
    dataPackets: 0,
    errorRate: 0,
    sensorStatus: { mq135: false, mq7: false, temp: false },
    sensorReadings: { mq135: '-', mq7: '-', temp: '-' },
    sensorCalibration: { mq135: false, mq7: false, temp: false },
    activityLog: [],
    pollInterval: null,
    async init() {
      await this.loadData();
      // Load activity log from localStorage
      const savedLog = localStorage.getItem('digsafe_activity_log');
      if (savedLog) {
        try {
          this.activityLog = JSON.parse(savedLog);
        } catch (e) { console.warn(e); }
      }
      // Check for calibration data
      const savedThresholds = localStorage.getItem('digsafe_thresholds');
      if (savedThresholds) {
        try {
          const thresholds = JSON.parse(savedThresholds);
          this.sensorCalibration = {
            mq135: !!thresholds.mq135_ema,
            mq7: !!thresholds.mq7_ema,
            temp: !!thresholds.temp_ema
          };
        } catch (e) { console.warn(e); }
      }
      // Start polling
      this.pollInterval = setInterval(() => this.loadData(), 3000);
    },
    async loadData() {
      const startTime = Date.now();
      try {
        const d = await DeviceAPI.getData();
        this.responseTime = Date.now() - startTime;
        this.connectionStatus = 'online';
        
        // Update status
        this.status = {
          wifi: d.wifi_status || 'connected',
          ip: d.ip || d.device_ip || d.client_ip || '-',
          uptime: d.uptime || this.formatUptime(d.uptime_ms) || '-',
          sensor_valid: d.temperature_valid === 1,
          last_alarm_ts: d.last_alarm_ts || (d.alarm === 1 ? new Date().toLocaleString() : '-'),
          firmware: d.firmware_version || 'v1.0.0',
          free_memory: d.free_heap ? this.formatMemory(d.free_heap) : '-',
          signal_strength: d.rssi ? d.rssi + ' dBm' : '-',
          mac_address: d.mac_address || '-'
        };

        // Update sensor status
        this.sensorStatus = {
          mq135: d.air_quality_ema !== null && d.air_quality_ema !== undefined,
          mq7: d.carbon_ema !== null && d.carbon_ema !== undefined,
          temp: d.temperature_valid === 1
        };

        // Update sensor readings
        this.sensorReadings = {
          mq135: d.air_quality_ema?.toFixed(2) + ' PPM' || '-',
          mq7: d.carbon_ema?.toFixed(2) + ' PPM' || '-',
          temp: d.temperature_ema?.toFixed(2) + ' °C' || '-'
        };

        // Calculate system health
        let healthScore = 0;
        if (this.sensorStatus.mq135) healthScore += 25;
        if (this.sensorStatus.mq7) healthScore += 25;
        if (this.sensorStatus.temp) healthScore += 25;
        if (this.responseTime < 500) healthScore += 15;
        if (this.status.wifi === 'connected') healthScore += 10;
        this.systemHealth = healthScore;

        // Update data packets
        this.dataPackets++;

        // Calculate error rate (simplified)
        this.errorRate = Math.max(0, 5 - healthScore / 20).toFixed(1);

        // Log alarm events
        if (d.alarm === 1 && !this.lastAlarmLogged) {
          this.addLog('alarm', 'Alarm Triggered!', 'One or more sensors detected hazardous levels');
          this.lastAlarmLogged = true;
        } else if (d.alarm === 0) {
          this.lastAlarmLogged = false;
        }

        // Log auto-detection events
        if (d.auto_air_quality === 1 && !this.lastAirQualityAlert) {
          this.addLog('warning', 'Air Quality Alert', 'MQ-135 detected poor air quality');
          this.lastAirQualityAlert = true;
        } else if (d.auto_air_quality === 0) {
          this.lastAirQualityAlert = false;
        }

        if (d.auto_carbon === 1 && !this.lastCarbonAlert) {
          this.addLog('warning', 'Carbon Monoxide Alert', 'MQ-7 detected high CO levels');
          this.lastCarbonAlert = true;
        } else if (d.auto_carbon === 0) {
          this.lastCarbonAlert = false;
        }

        if (d.auto_temperature === 1 && !this.lastTempAlert) {
          this.addLog('warning', 'Temperature Alert', 'LM35 detected high temperature');
          this.lastTempAlert = true;
        } else if (d.auto_temperature === 0) {
          this.lastTempAlert = false;
        }

      } catch (e) {
        console.warn(e);
        this.connectionStatus = 'offline';
        this.responseTime = Date.now() - startTime;
        this.errorRate = Math.min(100, this.errorRate + 5).toFixed(1);
        this.addLog('alarm', 'Connection Lost', 'Failed to communicate with ESP32 device');
      }
    },
    async refresh() {
      this.refreshing = true;
      await this.loadData();
      setTimeout(() => { this.refreshing = false; }, 500);
      this.addLog('info', 'Status Refreshed', 'Manual refresh completed successfully');
    },
    formatUptime(ms) {
      if (!ms) return null;
      const seconds = Math.floor(ms / 1000);
      const minutes = Math.floor(seconds / 60);
      const hours = Math.floor(minutes / 60);
      const days = Math.floor(hours / 24);
      if (days > 0) return `${days}d ${hours % 24}h`;
      if (hours > 0) return `${hours}h ${minutes % 60}m`;
      if (minutes > 0) return `${minutes}m ${seconds % 60}s`;
      return `${seconds}s`;
    },
    formatMemory(bytes) {
      if (bytes < 1024) return bytes + ' B';
      if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    },
    addLog(type, message, details = '') {
      const log = {
        type,
        message,
        details,
        time: new Date().toLocaleTimeString()
      };
      this.activityLog.unshift(log);
      if (this.activityLog.length > 50) this.activityLog = this.activityLog.slice(0, 50);
      localStorage.setItem('digsafe_activity_log', JSON.stringify(this.activityLog));
    },
    clearLogs() {
      this.activityLog = [];
      localStorage.removeItem('digsafe_activity_log');
      this.addLog('info', 'Logs Cleared', 'Activity log has been cleared');
    }
  }
}
