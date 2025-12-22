<?php
// Fix course status to 'active' for all courses
echo "Updating course status to 'active'...\n";

try {
    // Connect to database
    $pdo = new PDO('mysql:host=localhost;dbname=ite311_supilanas', 'root', '');
    
    // Update all courses to active status
    $update = $pdo->prepare("UPDATE courses SET status = 'active'");
    $update->execute();
    
    echo "Updated all courses to 'active' status.\n";
    
    // Verify the update
    $stmt = $pdo->query("SELECT COUNT(*) FROM courses WHERE status = 'active'");
    $activeCount = $stmt->fetchColumn();
    
    echo "Active courses count: $activeCount\n";
    
    if ($activeCount > 0) {
        echo "Courses should now be visible in dashboard.\n";
    } else {
        echo "No active courses found.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
