<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-graduation-cap me-2"></i>LMS
            </a>
        </div>
    </nav>

    <!-- Login Section -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-sign-in-alt fa-3x text-primary mb-3"></i>
                            <h2 class="text-primary"><?= $page_title ?? 'Login to LMS' ?></h2>
                            <p class="text-muted">Enter your credentials to access your account</p>
                        </div>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= base_url('auth/login') ?>">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email Address
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="<?= base_url() ?>" class="btn btn-outline-primary">
                                <i class="fas fa-home me-2"></i>Back to Home
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Demo Credentials -->
                <div class="card border-0 bg-light mt-4">
                    <div class="card-body">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>Demo Credentials
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Admin:</small><br>
                                <strong>admin@lms.com</strong><br>
                                <small>admin123</small>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Instructor:</small><br>
                                <strong>instructor@lms.com</strong><br>
                                <small>instructor123</small>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Student:</small><br>
                                <strong>student@lms.com</strong><br>
                                <small>student123</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-graduation-cap me-2"></i>Learning Management System</h5>
                    <p class="mb-0">Empowering education through technology.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2025 LMS. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>