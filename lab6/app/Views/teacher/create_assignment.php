<?php 
helper('text');
// Include header template
$headerData = [
    'title' => $title ?? 'Create Assignment',
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
                        <i class="bi bi-clipboard-plus me-2"></i>
                        <?= esc($page_title) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('teacher/assignments/store') ?>">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Assignment Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="col-md-6">
                                <label for="course" class="form-label">Course</label>
                                <select class="form-select" id="course" name="course_id" required>
                                    <option value="">Select Course</option>
                                    <?php if (isset($courses)): ?>
                                        <?php foreach ($courses as $course): ?>
                                            <option value="<?= $course['id'] ?>"><?= esc($course['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="datetime-local" class="form-control" id="due_date" name="due_date" required>
                                <small class="form-text text-muted">Select date and time for assignment deadline</small>
                            </div>
                            <div class="col-md-6">
                                <label for="points" class="form-label">Total Points</label>
                                <input type="number" class="form-control" id="points" name="points" min="1" value="100" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="instructions" class="form-label">Instructions</label>
                            <textarea class="form-control" id="instructions" name="instructions" rows="4"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('teacher/assignments') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Back to Assignments
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i> Create Assignment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set minimum date to current date and time
document.addEventListener('DOMContentLoaded', function() {
    const dueDateInput = document.getElementById('due_date');
    const now = new Date();
    
    // Set default value to tomorrow at 11:59 PM
    const tomorrow = new Date(now);
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(23, 59, 0, 0);
    
    const defaultYear = tomorrow.getFullYear();
    const defaultMonth = String(tomorrow.getMonth() + 1).padStart(2, '0');
    const defaultDay = String(tomorrow.getDate()).padStart(2, '0');
    const defaultHours = String(tomorrow.getHours()).padStart(2, '0');
    const defaultMinutes = String(tomorrow.getMinutes()).padStart(2, '0');
    
    const defaultDateTime = `${defaultYear}-${defaultMonth}-${defaultDay}T${defaultHours}:${defaultMinutes}`;
    dueDateInput.value = defaultDateTime;
    
    // Allow any date selection - no minimum date restriction
    // dueDateInput.min = minDateTime; // Removed this line to allow any date selection
});
</script>

<?php echo view('templates/footer'); ?>
