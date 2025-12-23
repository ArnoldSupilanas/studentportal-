<?php
// Setup notifications table and sample data
require_once 'app/Config/Database.php';

$db = \Config\Database::connect();

// Create notifications table
$sql = "
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `type` varchar(50) NOT NULL DEFAULT 'info',
    `title` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `data` json DEFAULT NULL,
    `is_read` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_is_read` (`is_read`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

try {
    $db->query($sql);
    echo "Notifications table created successfully.\n";
} catch (Exception $e) {
    echo "Error creating notifications table: " . $e->getMessage() . "\n";
}

// Insert sample notifications
$sampleNotifications = [
    [
        'user_id' => 1,
        'type' => 'success',
        'title' => 'Welcome Admin',
        'message' => 'You have successfully logged in to the admin dashboard.',
        'data' => json_encode(['action' => 'login', 'timestamp' => date('Y-m-d H:i:s')])
    ],
    [
        'user_id' => 1,
        'type' => 'info',
        'title' => 'System Update',
        'message' => 'A new system update is available. Please review the changes.',
        'data' => json_encode(['version' => '1.2.0', 'update_type' => 'security'])
    ],
    [
        'user_id' => 1,
        'type' => 'warning',
        'title' => 'User Activity',
        'message' => 'Multiple users have registered in the last hour.',
        'data' => json_encode(['count' => 5, 'timeframe' => '1 hour'])
    ],
    [
        'user_id' => 1,
        'type' => 'error',
        'title' => 'Failed Login Attempt',
        'message' => 'There was a failed login attempt from an unknown IP.',
        'data' => json_encode(['ip' => '192.168.1.100', 'attempts' => 3])
    ]
];

foreach ($sampleNotifications as $notification) {
    try {
        $db->table('notifications')->insert($notification);
        echo "Sample notification '{$notification['title']}' added.\n";
    } catch (Exception $e) {
        echo "Error inserting sample notification: " . $e->getMessage() . "\n";
    }
}

echo "\nNotification system setup complete!\n";
echo "You can now test the real-time notifications in the admin dashboard.\n";
?>
