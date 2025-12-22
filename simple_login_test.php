<?php
// Get CSRF token
$csrf = [
    'name' => csrf_token(),
    'hash' => csrf_hash()
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Login Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Simple Login Test</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Login Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="/index.php/login" method="post" target="_blank">
                            <input type="hidden" name="<?= $csrf['name'] ?>" value="<?= $csrf['hash'] ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <select name="email" class="form-control">
                                    <option value="admin@lms.com">admin@lms.com (Admin)</option>
                                    <option value="instructor@lms.com">instructor@lms.com (Teacher)</option>
                                    <option value="student@lms.com">student@lms.com (Student)</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password:</label>
                                <select name="password" class="form-control">
                                    <option value="admin123">admin123</option>
                                    <option value="instructor123">instructor123</option>
                                    <option value="student123">student123</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test Results</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Steps:</strong></p>
                        <ol>
                            <li>Choose credentials from the dropdown</li>
                            <li>Click Login</li>
                            <li>Should redirect to dashboard</li>
                            <li>Dashboard shows role-specific content</li>
                        </ol>
                        
                        <div class="mt-3">
                            <a href="/index.php/dashboard" target="_blank" class="btn btn-outline-primary">Test Dashboard Direct</a>
                            <a href="/index.php/login" target="_blank" class="btn btn-outline-secondary">Fresh Login Page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
