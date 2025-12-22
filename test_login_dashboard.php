<!DOCTYPE html>
<html>
<head>
    <title>Login to Dashboard Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Login to Dashboard Test</h1>
        
        <div class="alert alert-info">
            <h5>Test Credentials:</h5>
            <ul>
                <li><strong>Admin:</strong> admin@lms.com / admin123</li>
                <li><strong>Teacher:</strong> instructor@lms.com / instructor123</li>
                <li><strong>Student:</strong> student@lms.com / student123</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Quick Login Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('login') ?>" method="post" target="_blank">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" name="email" class="form-control" value="instructor@lms.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password:</label>
                                <input type="password" name="password" class="form-control" value="instructor123" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login & Go to Dashboard</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Direct Links</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="<?= base_url('login') ?>" target="_blank">Login Page</a>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= base_url('auth/login') ?>" target="_blank">Auth Login (Alternative)</a>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= base_url('dashboard') ?>" target="_blank">Dashboard (requires login)</a>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= base_url('debug/test-login') ?>" target="_blank">Simulate Login</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <div class="card">
                <div class="card-header">
                    <h5>Test Instructions</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li>Use the login form above or visit the login page directly</li>
                        <li>Enter any of the test credentials</li>
                        <li>After successful login, you should be redirected to the dashboard</li>
                        <li>The dashboard will show role-specific content based on your user type</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
