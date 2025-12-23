<?php
// Create notifications table directly
$db = new mysqli('localhost', 'root', '', 'lms_supilanas');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS notifications (
    id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT(5) UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'info' COMMENT 'info, success, warning, danger',
    is_read BOOLEAN DEFAULT false,
    created_at DATETIME NOT NULL,
    read_at DATETIME NULL,
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY is_read (is_read),
    KEY created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($db->query($sql) === TRUE) {
    echo "Notifications table created successfully or already exists\n";
} else {
    echo "Error creating table: " . $db->error . "\n";
}

$db->close();
?>
