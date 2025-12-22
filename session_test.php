<?php
session_start();

// Test session functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['test_value'] = $_POST['test_value'] ?? 'default';
    $_SESSION['timestamp'] = time();
    header('Location: session_test.php');
    exit;
}

$test_value = $_SESSION['test_value'] ?? 'Not set';
$timestamp = $_SESSION['timestamp'] ?? 'Not set';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Session Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Session Test</h1>
        
        <div class="card">
            <div class="card-header">
                <h5>Current Session Data</h5>
            </div>
            <div class="card-body">
                <p><strong>Test Value:</strong> <?= htmlspecialchars($test_value) ?></p>
                <p><strong>Timestamp:</strong> <?= htmlspecialchars($timestamp) ?></p>
                <p><strong>Session ID:</strong> <?= session_id() ?></p>
                
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Set Test Value:</label>
                        <input type="text" name="test_value" class="form-control" value="test_data_<?= time() ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Set Session Value</button>
                </form>
                
                <div class="mt-3">
                    <a href="/index.php/login" class="btn btn-outline-primary">Go to Login</a>
                    <a href="/index.php/dashboard" class="btn btn-outline-secondary">Go to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
