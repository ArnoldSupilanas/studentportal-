<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('admin/dashboard') ?>">
                <i class="fas fa-user-shield me-2"></i>Admin Dashboard
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/users') ?>">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('admin/courses') ?>">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('admin/settings') ?>">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('auth/logout') ?>">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="bg-danger text-white py-4">
        <div class="container">
            <h1 class="mb-0">
                <i class="fas fa-cog me-2"></i>
                <?= $page_title ?>
            </h1>
            <p class="mb-0"><?= $description ?></p>
        </div>
    </div>

    <!-- Settings Content -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            System Configuration
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="settingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="site_name" class="form-label">Site Name</label>
                                        <input type="text" class="form-control" id="site_name" name="site_name" value="<?= esc($settings['site_name']) ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_file_size" class="form-label">Max File Size</label>
                                        <input type="text" class="form-control" id="max_file_size" name="max_file_size" value="<?= esc($settings['max_file_size']) ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="site_description" class="form-label">Site Description</label>
                                <textarea class="form-control" id="site_description" name="site_description" rows="3"><?= esc($settings['site_description']) ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="session_timeout" class="form-label">Session Timeout</label>
                                <input type="text" class="form-control" id="session_timeout" name="session_timeout" value="<?= esc($settings['session_timeout']) ?>">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="allow_registration" name="allow_registration" <?= $settings['allow_registration'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="allow_registration">
                                            Allow Registration
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" <?= $settings['email_notifications'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="email_notifications">
                                            Email Notifications
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" <?= $settings['maintenance_mode'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="maintenance_mode">
                                            Maintenance Mode
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="resetSettings()">
                                    <i class="fas fa-undo me-2"></i>Reset to Defaults
                                </button>
                                <button type="button" class="btn btn-success" onclick="saveSettings()">
                                    <i class="fas fa-save me-2"></i>Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- System Status Card -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-server me-2"></i>
                            System Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fas fa-database text-success me-2"></i>
                                    Database: <span class="badge bg-success">Connected</span>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-hdd text-success me-2"></i>
                                    Storage: <span class="badge bg-success">Available</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fas fa-clock text-info me-2"></i>
                                    Server Time: <span class="badge bg-info"><?= date('H:i:s') ?></span>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-calendar text-info me-2"></i>
                                    Date: <span class="badge bg-info"><?= date('Y-m-d') ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions Sidebar -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="clearCache()">
                                <i class="fas fa-trash me-2"></i>Clear Cache
                            </button>
                            <button class="btn btn-outline-warning" onclick="backupDatabase()">
                                <i class="fas fa-download me-2"></i>Backup Database
                            </button>
                            <button class="btn btn-outline-danger" onclick="restartSystem()">
                                <i class="fas fa-sync me-2"></i>Restart System
                            </button>
                            <button class="btn btn-outline-info" onclick="exportLogs()">
                                <i class="fas fa-file-export me-2"></i>Export Logs
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function saveSettings() {
            const form = document.getElementById('settingsForm');
            const formData = new FormData(form);
            
            // Convert checkbox values
            formData.set('allow_registration', document.getElementById('allow_registration').checked);
            formData.set('email_notifications', document.getElementById('email_notifications').checked);
            formData.set('maintenance_mode', document.getElementById('maintenance_mode').checked);
            
            // Show loading state
            const saveBtn = event.target;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            
            // Simulate API call
            setTimeout(() => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Settings';
                alert('Settings saved successfully!');
            }, 1500);
        }
        
        function resetSettings() {
            if (confirm('Are you sure you want to reset all settings to defaults?')) {
                document.getElementById('site_name').value = 'LMS Portal';
                document.getElementById('site_description').value = 'Learning Management System';
                document.getElementById('max_file_size').value = '10MB';
                document.getElementById('session_timeout').value = '30 minutes';
                document.getElementById('allow_registration').checked = true;
                document.getElementById('email_notifications').checked = true;
                document.getElementById('maintenance_mode').checked = false;
                alert('Settings reset to defaults!');
            }
        }
        
        function clearCache() {
            if (confirm('Clear all system cache?')) {
                alert('Cache cleared successfully!');
            }
        }
        
        function backupDatabase() {
            if (confirm('Create database backup?')) {
                alert('Database backup created successfully!');
            }
        }
        
        function restartSystem() {
            if (confirm('Restart the system? This will temporarily disrupt service.')) {
                alert('System restart initiated!');
            }
        }
        
        function exportLogs() {
            alert('System logs exported successfully!');
        }
    </script>
</body>
</html>
