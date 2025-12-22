<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Display Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Dashboard Display Test</h1>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5>Quick Login Test</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Test login and dashboard redirect:</p>
                        <a href="/index.php/quick-login" target="_blank" class="btn btn-success">
                            <i class="bi bi-lightning"></i> Quick Login → Dashboard
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5>Direct Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Test dashboard directly:</p>
                        <a href="/index.php/dashboard" target="_blank" class="btn btn-info">
                            <i class="bi bi-speedometer2"></i> Open Dashboard
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Real Login</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Test actual login form:</p>
                        <a href="/index.php/login" target="_blank" class="btn btn-primary">
                            <i class="bi bi-person-circle"></i> Login Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h5><i class="bi bi-info-circle"></i> Dashboard Features</h5>
                    <ul>
                        <li><strong>Role-based content</strong> - Shows different content for admin, teacher, student</li>
                        <li><strong>User profile</strong> - Displays user information</li>
                        <li><strong>Navigation</strong> - Role-specific menu items</li>
                        <li><strong>Welcome message</strong> - Personalized greeting</li>
                        <li><strong>Action cards</strong> - Role-specific actions and statistics</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Test Credentials</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Admin</h6>
                                <code>admin@lms.com / admin123</code>
                            </div>
                            <div class="col-md-4">
                                <h6>Teacher</h6>
                                <code>instructor@lms.com / instructor123</code>
                            </div>
                            <div class="col-md-4">
                                <h6>Student</h6>
                                <code>student@lms.com / student123</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
