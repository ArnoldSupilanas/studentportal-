<!-- Navigation with Notification System -->
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
                    <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger <?= ($unread_notification_count ?? 0) > 0 ? '' : 'd-none' ?>">
                        <?= $unread_notification_count ?? 0 ?>
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
                    <li><a class="dropdown-item" href="<?= site_url('/notifications') ?>"><i class="fas fa-bell me-2"></i>All Notifications</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= site_url('/auth/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Toast Container for Notifications -->
<div id="toast-container" class="toast-container"></div>

<!-- Scripts for Notification System -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('public/assets/js/notifications.js') ?>"></script>
