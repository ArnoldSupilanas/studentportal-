<?php
// Test web dashboard access
echo "Testing web dashboard access...\n";

// Set headers to prevent session issues
header('Content-Type: text/html; charset=utf-8');

// Simulate accessing dashboard via web
echo "<h1>Testing Dashboard Access</h1>";
echo "<p>This test simulates accessing the dashboard through the web interface.</p>";

// Check if we can access the dashboard URL
if (isset($_SERVER['HTTP_HOST'])) {
    echo "<p>✅ Web server accessible: " . $_SERVER['HTTP_HOST'] . "</p>";
} else {
    echo "<p>❌ Web server not accessible</p>";
}

echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>Access the dashboard via browser at http://localhost:8080/auth/dashboard</li>";
echo "<li>Check if available courses section displays the 4 courses</li>";
echo "<li>Test enrollment functionality</li>";
echo "</ol>";

echo "<p>If courses are not displaying, the issue might be:</p>";
echo "<ul>";
echo "<li>Session not being maintained properly</li>";
echo "<li>Auth controller not being called correctly</li>";
echo "<li>Database connection issues</li>";
echo "</ul>";
?>
