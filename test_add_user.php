<?php
// Simple test for Add User functionality
session_start();

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Error: Admin access required";
    exit;
}

// Test database connection
try {
    $db = \Config\Database::connect();
    echo "Database connection: OK<br>";
    
    // Test UserModel
    $userModel = new \App\Models\UserModel();
    echo "UserModel: OK<br>";
    
    // Test creating a user
    $testData = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test' . time() . '@test.com',
        'role' => 'student',
        'status' => 'active',
        'password' => password_hash('test123', PASSWORD_DEFAULT)
    ];
    
    echo "Attempting to create user...<br>";
    $result = $userModel->insert($testData);
    
    if ($result) {
        echo "SUCCESS: User created with ID: " . $result;
    } else {
        echo "ERROR: Failed to create user<br>";
        $errors = $userModel->errors();
        if (!empty($errors)) {
            echo "Validation errors: " . implode(', ', $errors);
        }
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>
