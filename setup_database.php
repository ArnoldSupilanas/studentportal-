<?php
// Database setup script for course management
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'lms_supilanas';

try {
    // Connect to MySQL
    $conn = new mysqli($host, $username, $password);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Create database if not exists
    $conn->query("CREATE DATABASE IF NOT EXISTS `$database`");
    $conn->select_db($database);
    
    echo "Database '$database' connected successfully.\n";
    
    // Create courses table
    $sql = "CREATE TABLE IF NOT EXISTS `courses` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) DEFAULT NULL,
        `title` varchar(255) DEFAULT NULL,
        `code` varchar(50) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `instructor_id` int(11) DEFAULT NULL,
        `status` enum('active','inactive') DEFAULT 'active',
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo "Courses table created successfully.\n";
    }
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `first_name` varchar(100) DEFAULT NULL,
        `last_name` varchar(100) DEFAULT NULL,
        `email` varchar(255) DEFAULT NULL,
        `password` varchar(255) DEFAULT NULL,
        `role` enum('admin','teacher','student') DEFAULT 'student',
        `status` enum('active','inactive') DEFAULT 'active',
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo "Users table created successfully.\n";
    }
    
    // Create enrollments table
    $sql = "CREATE TABLE IF NOT EXISTS `enrollments` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `course_id` int(11) DEFAULT NULL,
        `student_id` int(11) DEFAULT NULL,
        `status` enum('active','inactive','completed','dropped') DEFAULT 'active',
        `enrollment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `course_id` (`course_id`),
        KEY `student_id` (`student_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo "Enrollments table created successfully.\n";
    }
    
    // Insert sample data if tables are empty
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        // Insert sample users
        $sql = "INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `status`) VALUES
        (1, 'Admin', 'User', 'admin@example.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active'),
        (2, 'John', 'Smith', 'john@example.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
        (3, 'Jane', 'Doe', 'jane@example.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
        (4, 'Bob', 'Johnson', 'bob@example.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
        (5, 'Alice', 'Brown', 'alice@example.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active')";
        
        if ($conn->query($sql)) {
            echo "Sample users inserted successfully.\n";
        }
    }
    
    $result = $conn->query("SELECT COUNT(*) as count FROM courses");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        // Insert sample courses
        $sql = "INSERT INTO `courses` (`id`, `name`, `title`, `code`, `description`, `instructor_id`, `status`) VALUES
        (1, 'Introduction to Computer Science', 'Introduction to Computer Science', 'CS101', 'Learn programming fundamentals', 2, 'active'),
        (2, 'Web Development Fundamentals', 'Web Development Fundamentals', 'WEB201', 'Learn web development basics', 2, 'active'),
        (3, 'Database Management Systems', 'Database Management Systems', 'DB301', 'Learn database design and management', 2, 'active'),
        (4, 'Advanced Programming', 'Advanced Programming', 'CS401', 'Advanced programming concepts', 2, 'active'),
        (5, 'Network Security', 'Network Security', 'SEC501', 'Learn network security principles', 2, 'inactive')";
        
        if ($conn->query($sql)) {
            echo "Sample courses inserted successfully.\n";
        }
    }
    
    $result = $conn->query("SELECT COUNT(*) as count FROM enrollments");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        // Insert sample enrollments
        $sql = "INSERT INTO `enrollments` (`course_id`, `student_id`, `status`, `enrollment_date`) VALUES
        (1, 3, 'active', '2025-12-01 10:00:00'),
        (1, 4, 'active', '2025-12-01 10:05:00'),
        (1, 5, 'completed', '2025-12-01 10:10:00'),
        (2, 3, 'active', '2025-12-02 11:00:00'),
        (2, 4, 'active', '2025-12-02 11:05:00'),
        (3, 3, 'active', '2025-12-03 12:00:00'),
        (3, 5, 'active', '2025-12-03 12:05:00'),
        (4, 4, 'active', '2025-12-04 13:00:00'),
        (4, 5, 'dropped', '2025-12-04 13:05:00'),
        (5, 3, 'active', '2025-12-05 14:00:00')";
        
        if ($conn->query($sql)) {
            echo "Sample enrollments inserted successfully.\n";
        }
    }
    
    echo "\nDatabase setup completed successfully!\n";
    echo "You can now access the course management system with real data.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
