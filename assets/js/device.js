// Device API helpers to communicate with ESP32
const DeviceAPI = (() => {
  // Configure the base URL of the ESP32 (adjust if needed)
  const BASE = window.ESP32_BASE_URL || location.origin; // default to same origin

  async function getData() {
    const res = await fetch(`${BASE}/data`, { cache: 'no-store' });
    if (!res.ok) throw new Error('Failed to fetch /data');
    return await res.json();
  }

  async function sosEnable() {
    const res = await fetch(`${BASE}/sos`, { method: 'POST' });
    if (!res.ok) throw new Error('Failed to POST /sos');
    return await res.json().catch(() => ({}));
  }

  async function sosClear() {
    const res = await fetch(`${BASE}/clear-sos`, { method: 'POST' });
    if (!res.ok) throw new Error('Failed to POST /clear-sos');
    return await res.json().catch(() => ({}));
  }

  async function calibrate() {
    const res = await fetch(`${BASE}/calibrate`, { method: 'POST' });
    if (!res.ok) throw new Error('Failed to POST /calibrate');
    return await res.json();
  }

  return { getData, sosEnable, sosClear, calibrate };
})();
