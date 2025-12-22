<?php 
helper('text');
// Include header template
$headerData = [
    'title' => $title ?? 'View Students',
    'role' => 'teacher',
    'is_logged_in' => true,
    'name' => session()->get('name')
];
echo view('templates/header', $headerData);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-people me-2"></i>
                        <?= esc($page_title) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (isset($students) && !empty($students)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Email</th>
                                        <th>Course</th>
                                        <th>Current Grade</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <td><?= esc($student['name'] ?? '') ?></td>
                                            <td><?= esc($student['email'] ?? '') ?></td>
                                            <td><?= esc($student['course'] ?? '') ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?= esc($student['grade'] ?? 'N/A') ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('teacher/viewStudent/' . (isset($student['id']) ? $student['id'] : 1)) ?>" class="btn btn-outline-primary" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('teacher/emailStudent/' . (isset($student['id']) ? $student['id'] : 1)) ?>" class="btn btn-outline-success" title="Send Email">
                                                        <i class="bi bi-envelope"></i>
                                                    </a>
                                                    <a href="<?= base_url('teacher/editStudent/' . (isset($student['id']) ? $student['id'] : 1)) ?>" class="btn btn-outline-warning" title="Edit Student">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-people fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No students found</h5>
                            <p class="text-muted">You don't have any students assigned to your courses yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo view('templates/footer'); ?>
