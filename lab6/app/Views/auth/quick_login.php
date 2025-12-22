<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Login - Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .quick-btn {
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }
        .quick-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-graduation-cap fa-3x mb-3"></i>
            <h3>Student Portal</h3>
            <p class="mb-0">Quick Login for Testing</p>
        </div>
        
        <div class="card-body p-4">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?= base_url('/quick-login') ?>">
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-envelope me-2"></i>Email Address
                    </label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-lock me-2"></i>Password
                    </label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 quick-btn">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
            
            <hr class="my-4">
            
            <h6 class="text-center mb-3">Quick Access:</h6>
            
            <div class="d-grid gap-2">
                <button onclick="quickLogin('student@lms.com', 'password')" class="btn btn-outline-primary quick-btn">
                    <i class="fas fa-user-graduate me-2"></i>Student Login
                </button>
                
                <button onclick="quickLogin('admin@lms.com', 'password')" class="btn btn-outline-danger quick-btn">
                    <i class="fas fa-user-shield me-2"></i>Admin Login
                </button>
            </div>
            
            <div class="mt-4 text-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    This is a simplified login for testing purposes
                </small>
            </div>
        </div>
    </div>
    
    <script>
    function quickLogin(email, password) {
        document.querySelector('input[name="email"]').value = email;
        document.querySelector('input[name="password"]').value = password;
        document.querySelector('form').submit();
    }
    </script>
</body>
</html>
