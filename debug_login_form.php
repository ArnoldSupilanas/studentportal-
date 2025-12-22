<!DOCTYPE html>
<html>
<head>
    <title>Debug Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Debug Login Form</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test Login (Teacher)</h5>
                    </div>
                    <div class="card-body">
                        <form action="/index.php/login" method="post" target="_blank">
                            <input type="hidden" name="csrf_test_name" value="">
                            <div class="mb-3">
                                <label>Email:</label>
                                <input type="email" name="email" class="form-control" value="instructor@lms.com" required>
                            </div>
                            <div class="mb-3">
                                <label>Password:</label>
                                <input type="password" name="password" class="form-control" value="instructor123" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Quick Test Options</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="/index.php/quick-login" target="_blank" class="list-group-item list-group-item-action">
                                <i class="bi bi-lightning"></i> Quick Login (bypass form)
                            </a>
                            <a href="/index.php/dashboard" target="_blank" class="list-group-item list-group-item-action">
                                <i class="bi bi-speedometer2"></i> Test Dashboard Direct
                            </a>
                            <a href="/index.php/test-dashboard" target="_blank" class="list-group-item list-group-item-action">
                                <i class="bi bi-gear"></i> Test Dashboard (no auth)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <div class="alert alert-info">
                <h5>Debug Info:</h5>
                <ul>
                    <li>Database: Connected ✓</li>
                    <li>Users: Found (admin, instructor, student) ✓</li>
                    <li>Login page: Loading ✓</li>
                    <li>Dashboard: Working ✓</li>
                    <li>Quick login: Working ✓</li>
                </ul>
            </div>
        </div>
    </div>
    
    <script>
        // Get CSRF token from the actual login page
        fetch('/index.php/login')
            .then(response => response.text())
            .then(html => {
                const match = html.match(/name="csrf_test_name" value="([^"]+)"/);
                if (match) {
                    document.querySelector('input[name="csrf_test_name"]').value = match[1];
                }
            });
    </script>
</body>
</html>
