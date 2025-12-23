<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Notifications Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">
            <i class="bi bi-bell-fill text-primary"></i> Real-Time Notifications Demo
        </h1>
        
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            This demonstrates the real-time notification system. Test notifications will appear in real-time.
        </div>

        <div class="text-center">
            <button id="create-test-notification" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-plus-circle me-2"></i>Create Test Notification
            </button>
            <button id="mark-all-read" class="btn btn-warning btn-lg">
                <i class="bi bi-check-all me-2"></i>Mark All Read
            </button>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/assets/js/notifications.js"></script>
</body>
</html>
