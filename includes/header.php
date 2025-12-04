<?php
// Shared header include
?>
<!DOCTYPE html>
<html lang="en" x-data="appShell()" x-init="init()" :class="{ 'dark': theme === 'dark' }">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DIGSAFE</title>
  <link rel="icon" type="image/png" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%23FFD700'/><circle cx='50' cy='50' r='20' fill='%2300D4FF'/><circle cx='50' cy='50' r='15' fill='%23808080'/><circle cx='45' cy='45' r='3' fill='%23000'/><circle cx='55' cy='45' r='3' fill='%23000'/><rect x='40' y='20' width='4' height='15' rx='2' fill='%23FFD700' stroke='%23000' stroke-width='2'/><rect x='56' y='20' width='4' height='15' rx='2' fill='%23FFD700' stroke='%23000' stroke-width='2'/></svg>">
  <!-- Google Fonts - Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- TailwindCSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['Poppins', 'sans-serif'],
          },
          colors: {
            neon: {
              400: '#00f0ff',
              500: '#00d1ff',
              600: '#00b3ff',
            },
            glass: {
              800: 'rgba(20, 25, 35, 0.65)',
              700: 'rgba(25, 30, 45, 0.55)'
            },
            danger: {
              500: '#ff3b3b'
            }
          },
          boxShadow: {
            neon: '0 0 20px rgba(0, 209, 255, 0.6)',
          },
          backdropBlur: {
            xs: '2px'
          }
        }
      }
    }
  </script>
  <!-- AOS Animations -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <!-- GSAP (optional) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <!-- Custom Styles -->
  <link rel="stylesheet" href="/XAI/assets/css/styles.css" />
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen overflow-x-hidden">
  <!-- Animated background -->
  <div class="fixed inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950"></div>
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500/20 rounded-full blur-[128px] animate-pulse"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-[128px] animate-pulse" style="animation-delay: 1s;"></div>
  </div>

  <!-- Sidebar Navigation -->
  <aside class="fixed left-0 top-0 h-screen w-72 bg-slate-900/40 backdrop-blur-2xl border-r border-slate-800/50 z-50 flex flex-col">
    <div class="p-6 border-b border-slate-800/50">
      <div class="flex items-center gap-3">
        <div class="relative">
          <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 shadow-[0_0_30px_rgba(6,182,212,0.6)] flex items-center justify-center">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M13.5 2c-.178 0-.356.034-.523.103l-7 2.625A1.5 1.5 0 005 6.125v7.378a8.5 8.5 0 003.418 6.804l3.16 2.37a1.5 1.5 0 001.844 0l3.16-2.37A8.5 8.5 0 0020 13.503V6.125a1.5 1.5 0 00-.977-1.397l-7-2.625A1.498 1.498 0 0013.5 2z"/>
              <path fill="rgba(255,255,255,0.3)" d="M9 10l2 2 4-4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>
          </div>
          <div class="absolute -bottom-1 -right-1 h-4 w-4 bg-emerald-400 rounded-full border-2 border-slate-900 animate-pulse"></div>
        </div>
        <div>
          <div class="text-xs font-semibold tracking-wider text-cyan-400 uppercase">Batch 2</div>
          <div class="text-sm font-bold text-white">DIGSAFE</div>
        </div>
      </div>
    </div>
    <nav class="flex-1 p-4 space-y-2">
      <a href="/XAI/index.php?page=dashboard" class="nav-item group">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"></path></svg>
        <span>Dashboard</span>
        <div class="nav-indicator"></div>
      </a>
      <a href="/XAI/index.php?page=history" class="nav-item group">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        <span>History</span>
        <div class="nav-indicator"></div>
      </a>
      <a href="/XAI/index.php?page=calibration" class="nav-item group">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
        <span>Calibration</span>
        <div class="nav-indicator"></div>
      </a>
      <a href="/XAI/index.php?page=status" class="nav-item group">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>System Status</span>
        <div class="nav-indicator"></div>
      </a>
    </nav>
    <div class="p-4 border-t border-slate-800/50">
      <button @click="showTeamModal = true" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-600/20 to-blue-600/20 hover:from-cyan-600/30 hover:to-blue-600/30 border border-cyan-500/30 transition-all duration-300">
        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        <span class="text-xs font-medium text-cyan-400">Team Details</span>
      </button>
    </div>
  </aside>

  <!-- Team Details Modal -->
  <div x-show="showTeamModal" 
       x-transition:enter="transition ease-out duration-300" 
       x-transition:enter-start="opacity-0" 
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
       @click.self="showTeamModal = false"
       style="display: none;">
    <div x-show="showTeamModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 border border-slate-700 shadow-2xl">
      
      <!-- Modal Header -->
      <div class="sticky top-0 z-10 flex items-center justify-between p-6 border-b border-slate-700 bg-slate-900/80 backdrop-blur-sm">
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 shadow-[0_0_20px_rgba(6,182,212,0.4)] flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-white">Team Details</h2>
            <p class="text-xs text-slate-400">DIGSAFE - XAI Batch II</p>
          </div>
        </div>
        <button @click="showTeamModal = false" class="h-10 w-10 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-600 transition-all duration-200 flex items-center justify-center">
          <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

      <!-- Modal Content -->
      <div class="p-6 space-y-6">
       

        <!-- Team Members Grid -->
        <div>
          <h3 class="text-xl font-bold text-white mb-4">Team Members</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Member 1 -->
            <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:border-cyan-500/50 transition-all duration-300">
              <div class="flex items-center gap-3 mb-3">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                  AS
                </div>
                <div>
                  <div class="text-base font-bold text-white">Arun Sanjeev M S</div>
                  <div class="text-xs text-cyan-400">Software Developer</div>
                </div>
              </div>
              <div class="space-y-1 text-xs text-slate-400">
                <div>📧 927623bcs011@mkce.ac.in</div>
                <div>🎓 Roll No: 927623BCS011</div>
              </div>
            </div>

            <!-- Member 2 -->
            <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:border-cyan-500/50 transition-all duration-300">
              <div class="flex items-center gap-3 mb-3">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                  AK
                </div>
                <div>
                  <div class="text-base font-bold text-white">Aswanth K S</div>
                  <div class="text-xs text-blue-400">Hardware Developer</div>
                </div>
              </div>
              <div class="space-y-1 text-xs text-slate-400">
                <div>📧 927623bcs013@mkce.ac.in</div>
                <div>🎓 Roll No: 927623BCS013</div>
              </div>
            </div>

            <!-- Member 3 -->
            <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:border-cyan-500/50 transition-all duration-300">
              <div class="flex items-center gap-3 mb-3">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                  GR
                </div>
                <div>
                  <div class="text-base font-bold text-white">Ganesh R</div>
                  <div class="text-xs text-purple-400">Software Developer</div>
                </div>
              </div>
              <div class="space-y-1 text-xs text-slate-400">
                <div>📧 927623bcs030@mkce.ac.in</div>
                <div>🎓 Roll No: 927623BCS030</div>
              </div>
            </div>

            <!-- Member 4 -->
            <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:border-cyan-500/50 transition-all duration-300">
              <div class="flex items-center gap-3 mb-3">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                  KM
                </div>
                <div>
                  <div class="text-base font-bold text-white">Kumar Mohit</div>
                  <div class="text-xs text-emerald-400">Testing & Documentation</div>
                </div>
              </div>
              <div class="space-y-1 text-xs text-slate-400">
                <div>📧 927623bcs050@mkce.ac.in</div>
                <div>🎓 Roll No: 927623BCS050</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Project Mentor/Guide -->
        <div class="p-5 rounded-xl bg-gradient-to-r from-orange-500/10 to-red-500/10 border border-orange-500/30">
          <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-400" fill="currentColor" viewBox="0 0 24 24"><path d="M10.394 2.08a1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
            Project Guide
          </h3>
          <div class="flex items-center gap-3">
            <div class="h-14 w-14 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
              PG
            </div>
            <div>
              <div class="text-lg font-bold text-white">Dr.D Pradeep</div> 
              <div class="text-sm text-slate-400">Head of the Department</div>
              <div class="text-xs text-slate-500 mt-1">    Department of Computer Science & Engineering</div> 
 
             
            </div>
          </div>
        </div>

        <!-- Technologies Used -->
        <div>
          <h3 class="text-xl font-bold text-white mb-3">Technologies Used</h3>
          <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1.5 rounded-lg bg-orange-500/20 border border-orange-500/30 text-xs font-medium text-orange-400">ESP32</span>
            <span class="px-3 py-1.5 rounded-lg bg-blue-500/20 border border-blue-500/30 text-xs font-medium text-blue-400">MQ135 Sensor</span>
            <span class="px-3 py-1.5 rounded-lg bg-purple-500/20 border border-purple-500/30 text-xs font-medium text-purple-400">MQ7 Sensor</span>
            <span class="px-3 py-1.5 rounded-lg bg-green-500/20 border border-green-500/30 text-xs font-medium text-green-400">DHT22 Temperature</span>
            <span class="px-3 py-1.5 rounded-lg bg-cyan-500/20 border border-cyan-500/30 text-xs font-medium text-cyan-400">PHP Backend</span>
            <span class="px-3 py-1.5 rounded-lg bg-pink-500/20 border border-pink-500/30 text-xs font-medium text-pink-400">Alpine.js</span>
            <span class="px-3 py-1.5 rounded-lg bg-yellow-500/20 border border-yellow-500/30 text-xs font-medium text-yellow-400">Chart.js</span>
            <span class="px-3 py-1.5 rounded-lg bg-indigo-500/20 border border-indigo-500/30 text-xs font-medium text-indigo-400">TailwindCSS</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <main class="ml-72 min-h-screen p-8">
<?php /* page content starts */ ?>
