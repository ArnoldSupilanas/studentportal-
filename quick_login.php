<?php
// Quick login helper for debugging
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: quick_login.php");
    exit;
}

if ($_POST) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple authentication for testing
    if ($email === 'student@lms.com' && $password === 'password') {
        $_SESSION['is_logged_in'] = true;
        $_SESSION['userID'] = 3;
        $_SESSION['user_id'] = 3;
        $_SESSION['role'] = 'student';
        $_SESSION['first_name'] = 'Bob';
        $_SESSION['last_name'] = 'Student';
        header("Location: debug_enrollment.php");
        exit;
    } elseif ($email === 'admin@lms.com' && $password === 'password') {
        $_SESSION['is_logged_in'] = true;
        $_SESSION['userID'] = 1;
        $_SESSION['user_id'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['first_name'] = 'John';
        $_SESSION['last_name'] = 'Admin';
        header("Location: debug_enrollment.php");
        exit;
    }
}

echo '<!DOCTYPE html>
<html>
<head>
    <title>Quick Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Quick Login for Debugging</h4>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <hr>
                        <h6>Test Credentials:</h6>
                        <div class="row">
                            <div class="col-6">
                                <button onclick="login(\'student@lms.com\', \'password\')" class="btn btn-sm btn-outline-primary w-100">Student Login</button>
                            </div>
                            <div class="col-6">
                                <button onclick="login(\'admin@lms.com\', \'password\')" class="btn btn-sm btn-outline-danger w-100">Admin Login</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    function login(email, password) {
        document.querySelector("input[name=\'email\']").value = email;
        document.querySelector("input[name=\'password\']").value = password;
        document.querySelector("form").submit();
    }
    </script>
</body>
</html>';
?>
