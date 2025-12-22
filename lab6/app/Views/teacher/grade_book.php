<?php 
helper('text');
// Include header template
$headerData = [
    'title' => $title ?? 'Grade Book',
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
                        <i class="bi bi-bar-chart me-2"></i>
                        <?= esc($page_title) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (isset($courses) && !empty($courses)): ?>
                        <?php foreach ($courses as $course): ?>
                            <div class="mb-5">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-book me-2"></i>
                                    <?= esc($course['name']) ?>
                                </h5>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Student Name</th>
                                                <th>Assignment 1</th>
                                                <th>Assignment 2</th>
                                                <th>Assignment 3</th>
                                                <th>Average</th>
                                                <th>Grade</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($course['students'] as $student): ?>
                                                <tr>
                                                    <td><?= esc($student['name']) ?></td>
                                                    <?php foreach ($student['assignments'] as $assignment): ?>
                                                        <td>
                                                            <span class="badge bg-<?= $assignment >= 90 ? 'success' : ($assignment >= 80 ? 'primary' : ($assignment >= 70 ? 'warning' : 'danger')) ?>">
                                                                <?= $assignment ?>
                                                            </span>
                                                        </td>
                                                    <?php endforeach; ?>
                                                    <td>
                                                        <strong><?= number_format($student['average'], 1) ?></strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?= $student['average'] >= 90 ? 'success' : ($student['average'] >= 80 ? 'primary' : ($student['average'] >= 70 ? 'warning' : 'danger')) ?>">
                                                            <?= $student['average'] >= 90 ? 'A' : ($student['average'] >= 80 ? 'B' : ($student['average'] >= 70 ? 'C' : 'D')) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-outline-primary" title="Edit Grades">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button class="btn btn-outline-info" title="View Details">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-bar-chart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No grades available</h5>
                            <p class="text-muted">No courses with student grades found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo view('templates/footer'); ?>
