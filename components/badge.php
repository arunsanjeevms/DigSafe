<?php
// Reusable badge component
function render_badge($text, $active = false) {
  $classes = $active ? 'text-neon-400 border-neon-600' : 'text-slate-400 border-slate-700';
  echo '<span class="px-2 py-0.5 rounded bg-slate-800 text-xs border ' . $classes . '">' . htmlspecialchars($text) . '</span>';
}
?>
