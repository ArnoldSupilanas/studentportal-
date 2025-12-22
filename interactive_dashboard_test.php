<!DOCTYPE html>
<html>
<head>
    <title>Interactive Dashboard Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">
            <i class="bi bi-mouse text-success"></i> Interactive Dashboard Buttons
        </h1>
        
        <div class="alert alert-success">
            <h4><i class="bi bi-check-circle"></i> Dashboard Buttons Now Interactive!</h4>
            <p>All dashboard buttons are now functional and lead to their respective pages.</p>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5>Teacher Interactive Buttons</h5>
                    </div>
                    <div class="card-body">
                        <p>Click these buttons to test teacher functionality:</p>
                        <div class="d-grid gap-2">
                            <a href="/index.php/teacher/courses" target="_blank" class="btn btn-success">
                                <i class="bi bi-book"></i> My Courses
                            </a>
                            <a href="/index.php/teacher/assignments" target="_blank" class="btn btn-info">
                                <i class="bi bi-clipboard-check"></i> Assignments
                            </a>
                            <a href="/index.php/teacher/grades" target="_blank" class="btn btn-warning">
                                <i class="bi bi-award"></i> Grade Students
                            </a>
                            <a href="/index.php/teacher/attendance" target="_blank" class="btn btn-secondary">
                                <i class="bi bi-calendar-check"></i> Attendance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5>Student Interactive Buttons</h5>
                    </div>
                    <div class="card-body">
                        <p>Click these buttons to test student functionality:</p>
                        <div class="d-grid gap-2">
                            <a href="/index.php/student/courses" target="_blank" class="btn btn-info">
                                <i class="bi bi-book"></i> My Courses
                            </a>
                            <a href="/index.php/student/assignments" target="_blank" class="btn btn-primary">
                                <i class="bi bi-journal-text"></i> Assignments
                            </a>
                            <a href="/index.php/student/grades" target="_blank" class="btn btn-warning">
                                <i class="bi bi-award"></i> My Grades
                            </a>
                            <a href="/index.php/student/calendar" target="_blank" class="btn btn-secondary">
                                <i class="bi bi-calendar3"></i> Academic Calendar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Test Complete Dashboard Flow</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center gap-3">
                            <a href="/index.php/quick-login" target="_blank" class="btn btn-success btn-lg">
                                <i class="bi bi-lightning"></i> Quick Login → Dashboard
                            </a>
                            <a href="/index.php/dashboard" target="_blank" class="btn btn-primary btn-lg">
                                <i class="bi bi-speedometer2"></i> Direct Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h5><i class="bi bi-info-circle"></i> Interactive Features Added</h5>
                    <ul>
                        <li><strong>Teacher Pages:</strong> Courses, Assignments, Grades, Attendance</li>
                        <li><strong>Student Pages:</strong> Courses, Assignments, Grades, Calendar</li>
                        <li><strong>Navigation:</strong> All buttons link to functional pages</li>
                        <li><strong>Data Display:</strong> Mock data shows realistic LMS content</li>
                        <li><strong>Role Protection:</strong> Pages protected by role-based filters</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
