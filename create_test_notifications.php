<?php
// Test script for creating sample notifications
require_once 'app/Config/Database.php';

$db = \Config\Database::connect();

// Get all users to create test notifications for
$users = $db->table('users')->get()->getResultArray();

echo "Creating test notifications...\n";

foreach ($users as $user) {
    // Create different types of test notifications
    $notifications = [
        [
            'user_id' => $user['id'],
            'title' => 'Welcome Back!',
            'message' => 'Welcome to the student portal. You have new courses available.',
            'type' => 'success',
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'user_id' => $user['id'],
            'title' => 'Course Update',
            'message' => 'New materials have been added to your enrolled courses.',
            'type' => 'info',
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
        ],
        [
            'user_id' => $user['id'],
            'title' => 'Assignment Due Soon',
            'message' => 'You have an assignment due in the next 48 hours.',
            'type' => 'warning',
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
        ]
    ];

    foreach ($notifications as $notification) {
        try {
            $db->table('notifications')->insert($notification);
            echo "Created notification for user {$user['id']}: {$notification['title']}\n";
        } catch (Exception $e) {
            echo "Error creating notification for user {$user['id']}: " . $e->getMessage() . "\n";
        }
    }
}

echo "\nTest notifications created successfully!\n";
echo "Now you can:\n";
echo "1. Log in as any user to see the notification badge\n";
echo "2. Click the notification bell to see the dropdown\n";
echo "3. Test marking notifications as read\n";
echo "4. Test real-time updates (60-second polling)\n";
?>
