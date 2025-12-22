<?php
// Fix for the dashboard PHP syntax errors
// Read the original file
$content = file_get_contents('app/Views/auth/dashboard.php');

// Fix the bracket issues in data attributes
$content = str_replace("data-title=\"<?= strtolower(esc(\$course['title']) ?>", "data-title=\"<?= strtolower(esc(\$course['title'])) ?>", $content);
$content = str_replace("data-instructor=\"<?= strtolower(esc(\$course['instructor_name'] ?? 'TBA')) ?>", "data-instructor=\"<?= strtolower(esc(\$course['instructor_name'] ?? 'TBA')) ?>", $content);
$content = str_replace("data-description=\"<?= strtolower(esc(\$course['description'] ?? '')) ?>", "data-description=\"<?= strtolower(esc(\$course['description'] ?? '')) ?>", $content);

// Write the corrected content back
file_put_contents('app/Views/auth/dashboard.php', $content);

echo "Dashboard PHP syntax errors fixed!";
?>
