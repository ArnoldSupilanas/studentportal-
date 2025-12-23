<?php
// Simple script to check notifications table structure and create test data

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'lms_supilanas';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully!\n";
    
    // Check notifications table structure
    echo "\nNotifications table structure:\n";
    $stmt = $pdo->query("DESCRIBE notifications");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    
    // Check if users exist
    echo "\nChecking for users...\n";
    $stmt = $pdo->query("SELECT id, first_name, last_name FROM users LIMIT 3");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "No users found. Creating sample user...\n";
        $pdo->exec("INSERT INTO users (first_name, last_name, email, password, role, status) VALUES ('Test', 'Student', 'student@test.com', 'password123', 'student', 'active')");
        $userId = $pdo->lastInsertId();
        echo "Created test user with ID: $userId\n";
        $users = [['id' => $userId, 'first_name' => 'Test', 'last_name' => 'Student']];
    }
    
    echo "\nCreating test notifications...\n";
    
    foreach ($users as $user) {
        // Create simple test notifications
        $notifications = [
            [
                'user_id' => $user['id'],
                'title' => 'Welcome Back!',
                'message' => 'Welcome to the student portal. You have new courses available.',
                'type' => 'success',
                'is_read' => 0
            ],
            [
                'user_id' => $user['id'],
                'title' => 'Course Update',
                'message' => 'New materials have been added to your enrolled courses.',
                'type' => 'info',
                'is_read' => 0
            ]
        ];
        
        foreach ($notifications as $notification) {
            // Simple insert without updated_at
            $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, is_read, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $notification['user_id'],
                $notification['title'],
                $notification['message'],
                $notification['type'],
                $notification['is_read']
            ]);
            
            echo "Created notification for user {$user['id']}: {$notification['title']}\n";
        }
    }
    
    // Show current notification count
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM notifications WHERE is_read = 0");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nTotal unread notifications: " . $result['total'] . "\n";
    
    echo "\nTest notifications created successfully!\n";
    echo "Now you can test the notification system by:\n";
    echo "1. Logging in as a user\n";
    echo "2. Checking the notification badge\n";
    echo "3. Clicking the notification dropdown\n";
    echo "4. Testing mark as read functionality\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
