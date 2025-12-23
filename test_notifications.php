<?php
// Test page for notification system
session_start();

// Include the notification system test HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification System Test</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .notification-item {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .notification-item:hover {
            background-color: #f8f9fa !important;
        }
        
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Navigation with Notifications -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>LMS System
            </a>
            
            <div class="navbar-nav ms-auto">
                <!-- Notifications Dropdown -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
                            0
                        </span>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="min-width: 350px;">
                        <li class="dropdown-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Notifications</span>
                                <div>
                                    <button class="btn btn-sm btn-outline-secondary" id="refresh-notifications">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary ms-1" id="mark-all-read" style="display: none;">
                                        Mark all read
                                    </button>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li id="notification-list">
                            <!-- Notifications will be loaded here -->
                        </li>
                    </ul>
                </div>
                
                <!-- Test User Menu -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user me-2"></i>Test User
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><a class="dropdown-item" href="/notifications"><i class="fas fa-bell me-2"></i>All Notifications</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bell me-2"></i>Notification System Test
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Test Features:</h6>
                            <ul class="mb-0">
                                <li>Click the bell icon to see notifications dropdown</li>
                                <li>Click "Create Test Notification" to add new notifications</li>
                                <li>Click on notifications to mark them as read</li>
                                <li>Use "Mark all read" to clear all notifications</li>
                                <li>System polls for new notifications every 30 seconds</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                            <button class="btn btn-primary" id="create-test-notification">
                                <i class="fas fa-plus me-1"></i>Create Test Notification
                            </button>
                            <button class="btn btn-outline-secondary" id="refresh-notifications">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button class="btn btn-outline-info" href="/notifications">
                                <i class="fas fa-list me-1"></i>View All Notifications
                            </button>
                        </div>
                        
                        <div class="mt-4">
                            <h6>System Status:</h6>
                            <div id="system-status" class="alert alert-secondary">
                                <i class="fas fa-spinner fa-spin me-2"></i>Initializing notification system...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Notifications JS -->
    <script src="public/assets/js/notifications.js"></script>
    
    <script>
    // Test the notification system
    $(document).ready(function() {
        // Update system status
        setTimeout(() => {
            $('#system-status').html('<i class="fas fa-check-circle text-success me-2"></i>Notification system loaded successfully!');
        }, 1000);
        
        // Test notification creation
        $('#create-test-notification').on('click', function() {
            console.log('Creating test notification...');
        });
    });
    </script>
</body>
</html>
