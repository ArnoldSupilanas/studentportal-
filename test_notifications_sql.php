<?php
// Direct SQL test script for creating sample notifications

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'lms_supilanas';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully!\n";
    
    // First, let's check if users exist
    $stmt = $pdo->query("SELECT id, first_name, last_name FROM users LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "No users found. Creating sample user first...\n";
        $pdo->exec("INSERT INTO users (first_name, last_name, email, password, role, status) VALUES ('Test', 'Student', 'student@test.com', 'password123', 'student', 'active')");
        $userId = $pdo->lastInsertId();
        echo "Created test user with ID: $userId\n";
        $users = [['id' => $userId, 'first_name' => 'Test', 'last_name' => 'Student']];
    }
    
    echo "Creating test notifications...\n";
    
    foreach ($users as $user) {
        // Create different types of test notifications
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
            ],
            [
                'user_id' => $user['id'],
                'title' => 'Assignment Due Soon',
                'message' => 'You have an assignment due in the next 48 hours.',
                'type' => 'warning',
                'is_read' => 0
            ]
        ];
        
        foreach ($notifications as $notification) {
            $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, is_read, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                $notification['user_id'],
                $notification['title'],
                $notification['message'],
                $notification['type'],
                $notification['is_read']
            ]);
            
            echo "Created notification for user {$user['id']} ({$user['first_name']} {$user['last_name']}): {$notification['title']}\n";
        }
    }
    
    echo "\nTest notifications created successfully!\n";
    echo "\nNow you can:\n";
    echo "1. Log in as any user to see the notification badge\n";
    echo "2. Click the notification bell to see the dropdown\n";
    echo "3. Test marking notifications as read\n";
    echo "4. Test real-time updates (60-second polling)\n";
    
    // Show current notification count
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM notifications WHERE is_read = 0");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nTotal unread notifications: " . $result['total'] . "\n";
    
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    echo "Please check your database credentials and make sure the database exists.\n";
}
?>
