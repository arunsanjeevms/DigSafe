<?php
// Reusable sensor card component
function render_card($title, $rawLabel, $rawValue, $emaLabel, $emaValue, $extraClass = '') {
  echo '<div class="sensor-card ' . htmlspecialchars($extraClass) . '">';
  echo '<div class="card-title">' . htmlspecialchars($title) . '</div>';
  echo '<div class="card-values">';
  echo '<div><div class="value-label">' . htmlspecialchars($rawLabel) . '</div><div class="value">' . htmlspecialchars($rawValue) . '</div></div>';
  echo '<div><div class="value-label">' . htmlspecialchars($emaLabel) . '</div><div class="value">' . htmlspecialchars($emaValue) . '</div></div>';
  echo '</div></div>';
}
?>
