<?php
// Connect to database
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'lms_supilanas';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== Users Table Structure ===\n";
$result = $conn->query("DESCRIBE users");
while($row = $result->fetch_assoc()) {
    echo "{$row['Field']} - {$row['Type']} - {$row['Null']} - {$row['Key']}\n";
}

echo "\n=== Existing Users ===\n";
$result = $conn->query("SELECT id, email, role FROM users LIMIT 10");
while($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']}, Email: {$row['email']}, Role: {$row['role']}\n";
}

echo "\n=== Courses Table Structure ===\n";
$result = $conn->query("DESCRIBE courses");
while($row = $result->fetch_assoc()) {
    echo "{$row['Field']} - {$row['Type']} - {$row['Null']} - {$row['Key']}\n";
}

echo "\n=== Existing Courses ===\n";
$result = $conn->query("SELECT id, title FROM courses LIMIT 5");
while($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']}, Title: {$row['title']}\n";
}

echo "\n=== Materials Table Check ===\n";
$result = $conn->query("SELECT COUNT(*) as count FROM materials");
$row = $result->fetch_assoc();
echo "Total materials in database: {$row['count']}\n";

$conn->close();
?>
