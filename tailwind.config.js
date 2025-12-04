// Placeholder Tailwind config (CDN runtime config is set in header.php)
module.exports = {
  darkMode: 'class',
  content: [
    './index.php',
    './includes/**/*.php',
    './pages/**/*.php',
    './components/**/*.php',
    './assets/js/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        neon: { 400: '#00f0ff', 500: '#00d1ff', 600: '#00b3ff' },
        glass: { 800: 'rgba(20,25,35,0.65)', 700: 'rgba(25,30,45,0.55)' },
        danger: { 500: '#ff3b3b' }
      },
      boxShadow: { neon: '0 0 20px rgba(0,209,255,0.6)' },
      backdropBlur: { xs: '2px' }
    }
  }
};