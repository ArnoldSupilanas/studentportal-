<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Contact LMS' ?></title>
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
                        <a class="nav-link" href="<?= base_url('about') ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('contact') ?>">Contact</a>
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
                        <i class="fas fa-envelope me-3"></i>
                        <?= $page_title ?? 'Get in Touch' ?>
                    </h1>
                    <p class="lead"><?= $description ?? 'Have questions? We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="row">
                    <!-- Contact Form -->
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h3 class="text-primary mb-4">
                                    <i class="fas fa-paper-plane me-2"></i>Send us a Message
                                </h3>
                                <form>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="firstName" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="firstName" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="lastName" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="lastName" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Subject</label>
                                        <select class="form-select" id="subject" required>
                                            <option value="">Choose a subject</option>
                                            <option value="general">General Inquiry</option>
                                            <option value="technical">Technical Support</option>
                                            <option value="course">Course Information</option>
                                            <option value="billing">Billing Question</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control" id="message" rows="5" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Send Message
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h3 class="text-primary mb-4">
                                    <i class="fas fa-phone me-2"></i>Contact Information
                                </h3>
                                
                                <div class="mb-4">
                                    <h6 class="text-primary">
                                        <i class="fas fa-envelope me-2"></i>Email
                                    </h6>
                                    <p class="mb-0">support@lms.com</p>
                                    <p class="mb-0">info@lms.com</p>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-primary">
                                        <i class="fas fa-phone me-2"></i>Phone
                                    </h6>
                                    <p class="mb-0">+1 (555) 123-4567</p>
                                    <p class="mb-0">+1 (555) 987-6543</p>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-primary">
                                        <i class="fas fa-map-marker-alt me-2"></i>Address
                                    </h6>
                                    <p class="mb-0">123 Education Street</p>
                                    <p class="mb-0">Learning City, LC 12345</p>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-primary">
                                        <i class="fas fa-clock me-2"></i>Business Hours
                                    </h6>
                                    <p class="mb-0">Monday - Friday: 9:00 AM - 6:00 PM</p>
                                    <p class="mb-0">Saturday: 10:00 AM - 4:00 PM</p>
                                    <p class="mb-0">Sunday: Closed</p>
                                </div>

                                <div class="text-center">
                                    <a href="<?= base_url() ?>" class="btn btn-outline-primary btn-sm me-2">
                                        <i class="fas fa-home me-1"></i>Home
                                    </a>
                                    <a href="<?= base_url('about') ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-info-circle me-1"></i>About
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h3 class="text-primary mb-4 text-center">
                            <i class="fas fa-question-circle me-2"></i>Frequently Asked Questions
                        </h3>
                    </div>
                    <div class="col-md-6">
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                        How do I enroll in a course?
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Simply browse our course catalog, select the course you're interested in, and click the "Enroll" button. You'll be guided through the registration process.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                        What if I need technical support?
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Our technical support team is available Monday through Friday, 9 AM to 6 PM. You can reach us via email or phone, or use the contact form above.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="accordion" id="faqAccordion2">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                        Can I access courses on mobile devices?
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion2">
                                    <div class="accordion-body">
                                        Yes! Our platform is fully responsive and works on all devices including smartphones, tablets, and desktop computers.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                        Do you offer certificates?
                                    </button>
                                </h2>
                                <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion2">
                                    <div class="accordion-body">
                                        Yes, upon successful completion of a course, you'll receive a digital certificate that you can download and share on your professional profiles.
                                    </div>
                                </div>
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
