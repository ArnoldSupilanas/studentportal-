<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'About LMS' ?></title>
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('about') ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('contact') ?>">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="fas fa-info-circle me-3"></i>
                        <?= $page_title ?? 'About Our Learning Management System' ?>
                    </h1>
                    <p class="lead"><?= $description ?? 'Learn more about our comprehensive learning platform designed for students and instructors.' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Mission Section -->
                <div class="row mb-5">
                    <div class="col-md-6">
                        <h3 class="text-primary mb-3">
                            <i class="fas fa-bullseye me-2"></i>Our Mission
                        </h3>
                        <p>To provide accessible, high-quality education through innovative technology and expert instruction. We believe that learning should be engaging, interactive, and tailored to individual needs.</p>
                    </div>
                    <div class="col-md-6">
                        <h3 class="text-primary mb-3">
                            <i class="fas fa-eye me-2"></i>Our Vision
                        </h3>
                        <p>To become the leading platform for online education, empowering learners worldwide to achieve their educational and career goals through cutting-edge technology and personalized learning experiences.</p>
                    </div>
                </div>

                <!-- Features Section -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h3 class="text-primary mb-4 text-center">
                            <i class="fas fa-star me-2"></i>What Makes Us Different
                        </h3>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <i class="fas fa-code fa-3x text-primary mb-3"></i>
                            <h5>Hands-on Learning</h5>
                            <p>Practical projects and real-world applications in every course.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <i class="fas fa-users fa-3x text-success mb-3"></i>
                            <h5>Expert Instructors</h5>
                            <p>Learn from industry professionals with years of experience.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <i class="fas fa-mobile-alt fa-3x text-warning mb-3"></i>
                            <h5>Mobile Learning</h5>
                            <p>Access your courses anywhere, anytime on any device.</p>
                        </div>
                    </div>
                </div>

                <!-- Team Section -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h3 class="text-primary mb-4 text-center">
                            <i class="fas fa-users me-2"></i>Our Team
                        </h3>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-user-tie fa-3x text-primary mb-3"></i>
                                <h5>John Admin</h5>
                                <p class="text-muted">System Administrator</p>
                                <p>Oversees the platform operations and ensures smooth user experience.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-chalkboard-teacher fa-3x text-success mb-3"></i>
                                <h5>Jane Instructor</h5>
                                <p class="text-muted">Lead Instructor</p>
                                <p>Expert in web development and database design with 10+ years experience.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-graduation-cap fa-3x text-warning mb-3"></i>
                                <h5>Student Support</h5>
                                <p class="text-muted">Learning Assistants</p>
                                <p>Dedicated team to help students succeed in their learning journey.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="text-center">
                    <a href="<?= base_url() ?>" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                    <a href="<?= base_url('contact') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-envelope me-2"></i>Contact Us
                    </a>
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
