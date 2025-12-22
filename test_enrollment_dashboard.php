<?php
session_start();

// Simple login check
if (!($_SESSION['is_logged_in'] ?? $_SESSION['logged_in'] ?? false)) {
    header("Location: quick_login.php");
    exit;
}

// Check if user has admin role
$role = $_SESSION['role'] ?? 'student';
if ($role !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Database connection for testing
try {
    $pdo = new PDO('mysql:host=localhost;dbname=lms_supilanas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test enrollment statistics
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM enrollments");
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as active FROM enrollments WHERE status = 'enrolled'");
    $active = $stmt->fetch(PDO::FETCH_ASSOC)['active'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as completed FROM enrollments WHERE status = 'completed'");
    $completed = $stmt->fetch(PDO::FETCH_ASSOC)['completed'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as dropped FROM enrollments WHERE status = 'dropped'");
    $dropped = $stmt->fetch(PDO::FETCH_ASSOC)['dropped'];
    
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
    $total = $active = $completed = $dropped = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Dashboard Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user-graduate me-2"></i>
                            Enrollment Dashboard Test
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h3><?= $total ?></h3>
                                        <p>Total Enrollments</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h3><?= $active ?></h3>
                                        <p>Active Enrollments</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h3><?= $completed ?></h3>
                                        <p>Completed</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h3><?= $dropped ?></h3>
                                        <p>Dropped</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="/ITE311-SUPILANAS/admin/enrollments" class="btn btn-primary">
                                <i class="fas fa-arrow-right me-1"></i>Go to Full Enrollment Dashboard
                            </a>
                            <a href="/ITE311-SUPILANAS/admin/dashboard" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Admin Dashboard
                            </a>
                            <a href="/ITE311-SUPILANAS/enrollment_dashboard_link.php" class="btn btn-info">
                                <i class="fas fa-link me-1"></i>Test Direct Link
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
