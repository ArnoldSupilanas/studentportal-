<?php
$conn = new mysqli('localhost', 'root', '', 'lms_supilanas');
if ($conn->connect_error) {
    echo 'Connection failed: ' . $conn->connect_error;
} else {
    echo 'Database connection successful<br>';
    $result = $conn->query('SELECT email, role, status FROM users WHERE email LIKE "%@lms.com" LIMIT 5');
    if ($result) {
        echo 'Users found:<br>';
        while ($row = $result->fetch_assoc()) {
            echo '- ' . $row['email'] . ' (' . $row['role'] . ', ' . $row['status'] . ')<br>';
        }
    }
}
?>
