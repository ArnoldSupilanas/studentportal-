<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - LMS System</title>
    
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
        
        .notification-unread {
            border-left: 4px solid #007bff;
            background-color: #f8f9ff;
        }
        
        .notification-meta {
            font-size: 0.875rem;
            color: #6c757d;
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

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url('/auth/dashboard') ?>">
                <i class="fas fa-graduation-cap me-2"></i>LMS System
            </a>
            
            <div class="navbar-nav ms-auto">
                <!-- Notifications Dropdown -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
                            <?= $unread_count ?>
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
                
                <!-- User Menu -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user me-2"></i><?= session()->get('username') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="<?= site_url('/profile') ?>"><i class="fas fa-user-circle me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('/settings') ?>"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= site_url('/auth/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bell me-2"></i>All Notifications
                        </h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary" id="refresh-notifications">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button class="btn btn-sm btn-outline-primary ms-2" id="create-test-notification">
                                <i class="fas fa-plus me-1"></i>Create Test
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($notifications)): ?>
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-bell-slash fa-3x mb-3"></i>
                                <h5>No notifications yet</h5>
                                <p>You're all caught up! No new notifications to show.</p>
                            </div>
                        <?php else: ?>
                            <div class="notification-list">
                                <?php foreach ($notifications as $notification): ?>
                                    <div class="notification-item p-3 mb-2 border rounded <?= !$notification['is_read'] ? 'notification-unread' : '' ?>" 
                                         data-notification-id="<?= $notification['id'] ?>">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <?php
                                                $iconClass = 'fas fa-info-circle text-info';
                                                switch ($notification['type']) {
                                                    case 'success':
                                                        $iconClass = 'fas fa-check-circle text-success';
                                                        break;
                                                    case 'warning':
                                                        $iconClass = 'fas fa-exclamation-triangle text-warning';
                                                        break;
                                                    case 'danger':
                                                        $iconClass = 'fas fa-exclamation-circle text-danger';
                                                        break;
                                                }
                                                ?>
                                                <i class="<?= $iconClass ?> fa-lg"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?= esc($notification['title']) ?></h6>
                                                <p class="mb-2 text-muted"><?= esc($notification['message']) ?></p>
                                                <div class="notification-meta">
                                                    <small>
                                                        <i class="fas fa-clock me-1"></i>
                                                        <?= date('M j, Y g:i A', strtotime($notification['created_at'])) ?>
                                                        <?php if (!$notification['is_read']): ?>
                                                            <span class="badge bg-primary ms-2">New</span>
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>Notification Stats
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Unread:</span>
                            <span class="badge bg-primary"><?= $unread_count ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Total:</span>
                            <span class="badge bg-secondary"><?= count($notifications) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Read:</span>
                            <span class="badge bg-success"><?= count($notifications) - $unread_count ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm" id="mark-all-read">
                                <i class="fas fa-check-double me-1"></i>Mark All as Read
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" id="refresh-notifications">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button class="btn btn-outline-info btn-sm" id="create-test-notification">
                                <i class="fas fa-plus me-1"></i>Create Test Notification
                            </button>
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
    <script src="<?= base_url('public/assets/js/notifications.js') ?>"></script>
</body>
</html>
