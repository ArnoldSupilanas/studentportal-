<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/index.php/dashboard">
                <i class="bi bi-speedometer2"></i> Student Portal
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/index.php/dashboard">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <a class="nav-link" href="/index.php/teacher/students">
                    <i class="bi bi-people"></i> Students
                </a>
                <a class="nav-link" href="/index.php/logout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">
                            <i class="bi bi-envelope"></i> <?= esc($page_title) ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <p class="text-muted"><?= esc($description) ?></p>
                        
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> 
                                    Sending email to: <strong><?= esc($student['name']) ?></strong> (<?= esc($student['email']) ?>)
                                    <br>Course: <strong><?= esc($student['course']) ?></strong>
                                </div>
                            </div>
                        </div>

                        <form action="/index.php/teacher/sendEmail/<?= esc($student['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Subject *</label>
                                        <input type="text" class="form-control" id="subject" name="subject" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="message" class="form-label">Message *</label>
                                        <textarea class="form-control" id="message" name="message" rows="8" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="bi bi-lightbulb"></i> Quick Templates</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="useTemplate('grade')">
                                                    Grade Update
                                                </button>
                                            </div>
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="useTemplate('assignment')">
                                                    Assignment Reminder
                                                </button>
                                            </div>
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="useTemplate('meeting')">
                                                    Meeting Request
                                                </button>
                                            </div>
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="useTemplate('concern')">
                                                    Academic Concern
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="bi bi-info-circle"></i> Email Tips</h6>
                                        </div>
                                        <div class="card-body">
                                            <small class="text-muted">
                                                • Be clear and concise<br>
                                                • Include specific details<br>
                                                • Provide action items if needed<br>
                                                • Use professional tone
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="cc_teacher" name="cc_teacher">
                                        <label class="form-check-label" for="cc_teacher">
                                            Send a copy to my email
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="/index.php/teacher/students" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Back to Students
                                    </a>
                                    <a href="/index.php/teacher/viewStudent/<?= esc($student['id']) ?>" class="btn btn-info">
                                        <i class="bi bi-person"></i> View Student
                                    </a>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Send Email
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function useTemplate(type) {
            const subjectField = document.getElementById('subject');
            const messageField = document.getElementById('message');
            
            const templates = {
                'grade': {
                    subject: 'Grade Update - ' + '<?= esc($student['course']) ?>',
                    message: `Dear <?= esc($student['name']) ?>,

I wanted to update you on your recent performance in <?= esc($student['course']) ?>.

[Add specific grade information and feedback here]

Please let me know if you have any questions or would like to discuss your progress further.

Best regards,
[Your Name]`
                },
                'assignment': {
                    subject: 'Assignment Reminder - ' + '<?= esc($student['course']) ?>',
                    message: `Dear <?= esc($student['name']) ?>,

This is a reminder about the upcoming assignment in <?= esc($student['course']) ?>.

Assignment: [Assignment Title]
Due Date: [Date]
Details: [Assignment details]

Please ensure you submit this on time. If you have any questions or need clarification, don't hesitate to ask.

Best regards,
[Your Name]`
                },
                'meeting': {
                    subject: 'Meeting Request - ' + '<?= esc($student['course']) ?>',
                    message: `Dear <?= esc($student['name']) ?>,

I would like to schedule a meeting with you to discuss your progress in <?= esc($student['course']) ?>.

Please let me know what times work best for you this week. I'm available during:
- [Time slots]

Looking forward to speaking with you.

Best regards,
[Your Name]`
                },
                'concern': {
                    subject: 'Academic Concern - ' + '<?= esc($student['course']) ?>',
                    message: `Dear <?= esc($student['name']) ?>,

I wanted to reach out regarding your performance in <?= esc($student['course']) ?>.

[Describe the specific concern here]

I believe you have the potential to improve, and I'd like to work together to help you succeed. Please let me know when we can meet to discuss this further.

Best regards,
[Your Name]`
                }
            };
            
            if (templates[type]) {
                subjectField.value = templates[type].subject;
                messageField.value = templates[type].message;
            }
        }
    </script>
</body>
</html>
