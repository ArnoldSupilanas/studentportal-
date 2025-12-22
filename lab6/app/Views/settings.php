<?php 
helper('text');
// Include header template
$headerData = [
    'title' => $title ?? 'Settings',
    'role' => $user['role'] ?? 'student',
    'is_logged_in' => true,
    'name' => $user['first_name'] . ' ' . $user['last_name']
];
echo view('templates/header', $headerData);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-gear me-2"></i>
                        <?= esc($page_title) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted"><?= esc($description) ?></p>
                    
                    <!-- Account Settings -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h5 class="mb-3">Account Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="<?= esc($user['first_name'] . ' ' . $user['last_name']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" value="<?= esc($user['email']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control" value="<?= ucfirst(esc($user['role'])) ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 3rem;">
                                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                </div>
                                <p class="mt-2 text-muted">Profile Avatar</p>
                                <button class="btn btn-sm btn-outline-primary">Change Avatar</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notification Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Notification Preferences</h5>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                <label class="form-check-label" for="emailNotifications">
                                    Email Notifications
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="assignmentReminders" checked>
                                <label class="form-check-label" for="assignmentReminders">
                                    Assignment Reminders
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="gradeUpdates" checked>
                                <label class="form-check-label" for="gradeUpdates">
                                    Grade Updates
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Security Settings</h5>
                            <button class="btn btn-outline-primary me-2">
                                <i class="bi bi-key me-2"></i>Change Password
                            </button>
                            <button class="btn btn-outline-secondary">
                                <i class="bi bi-shield-check me-2"></i>Two-Factor Authentication
                            </button>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
                        </button>
                        <button class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo view('templates/footer'); ?>
