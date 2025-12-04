# ESP32 IoT Gas + Temperature Dashboard

This is a production-ready dark neon dashboard for your ESP32 sketch.

## Structure
- `index.php` – simple router (`?page=dashboard|history|calibration|status`)
- `includes/header.php`, `includes/footer.php` – layout, Tailwind/Alpine/Chart.js/AOS/GSAP
- `pages/dashboard.php` – live cards with 1s polling from `/data`, manual SOS toggle
- `pages/history.php` – 3 Chart.js charts (MQ135 EMA, MQ7 EMA, Temp EMA)
- `pages/calibration.php` – start `/calibrate` and show suggested thresholds
- `pages/status.php` – WiFi/IP/uptime/sensor validity/last alarm view
- `assets/js/device.js` – calls ESP32 endpoints `/data`, `/sos`, `/clear-sos`, `/calibrate`
- `assets/js/app.js` – Alpine.js stores and UI logic
- `assets/js/ai.js` – placeholder AI helpers
- `assets/css/styles.css` – glassmorphism + neon accents

## Arduino JSON mapping
Your sketch exposes `/data` with keys:

- `air_quality_raw`, `air_quality_ema`
- `carbon_raw`, `carbon_ema`
- `temperature_c`, `temperature_ema`, `temperature_valid`
- `auto_air_quality`, `auto_carbon`, `auto_temperature`
- `manual_sos`, `alarm`

The dashboard matches these names directly.

## Running (XAMPP on Windows)

Place this folder as `c:/xampp/htdocs/XAI` and start Apache. Then open:

- `http://localhost/XAI/index.php?page=dashboard`
- `http://localhost/XAI/index.php?page=history`
- `http://localhost/XAI/index.php?page=calibration`
- `http://localhost/XAI/index.php?page=status`

If your ESP32 is on another host, set `window.ESP32_BASE_URL` in `includes/header.php` under the Tailwind script:

```html
<script>window.ESP32_BASE_URL = 'http://192.168.4.1';</script>
```

## AI-ready hooks

See `assets/js/ai.js` for placeholder functions:

- `ai_predict_anomaly(data)`
- `ai_explain_readings(data)`
- `ai_adaptive_thresholds(history)`

These are ready to be wired into polling and chart components later.
