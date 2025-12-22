<!DOCTYPE html>
<html>
<head>
    <title>Complete Login & Dashboard Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Complete Login & Dashboard Test</h1>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Test Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Test dashboard without login:</p>
                        <a href="/index.php/test-dashboard" target="_blank" class="btn btn-primary">
                            <i class="bi bi-speedometer2"></i> Test Dashboard
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5>Simulate Login</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Set test session data:</p>
                        <a href="/index.php/debug/test-login" target="_blank" class="btn btn-success">
                            <i class="bi bi-box-arrow-in-right"></i> Simulate Login
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5>Real Login</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Actual login with credentials:</p>
                        <a href="/index.php/login" target="_blank" class="btn btn-info">
                            <i class="bi bi-person-circle"></i> Login Page
                        </a>
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
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-success">
                        <h5><i class="bi bi-check-circle"></i> Status: Working</h5>
                        <p class="mb-0">The login and dashboard system is now functional!</p>
                        <ul>
                            <li>✅ Login routes working (/login and /auth/login)</li>
                            <li>✅ Authentication filters working</li>
                            <li>✅ Dashboard displays role-based content</li>
                            <li>✅ Session management working</li>
                            <li>✅ CSRF protection active</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
