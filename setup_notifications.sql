-- Create notifications table for real-time notification system
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

-- Insert sample notifications for testing
INSERT INTO `notifications` (`user_id`, `type`, `title`, `message`, `data`) VALUES
(1, 'success', 'Welcome Admin', 'You have successfully logged in to the admin dashboard.', '{"action": "login", "timestamp": "2025-12-22 18:00:00"}'),
(1, 'info', 'System Update', 'A new system update is available. Please review the changes.', '{"version": "1.2.0", "update_type": "security"}'),
(1, 'warning', 'User Activity', 'Multiple users have registered in the last hour.', '{"count": 5, "timeframe": "1 hour"}'),
(1, 'error', 'Failed Login Attempt', 'There was a failed login attempt from an unknown IP.', '{"ip": "192.168.1.100", "attempts": 3}');
