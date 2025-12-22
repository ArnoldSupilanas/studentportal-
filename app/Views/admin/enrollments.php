<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .dashboard-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            text-align: center;
            padding: 25px;
            border-radius: 15px;
            color: white;
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: scale(1.05);
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .enrollment-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .action-btn {
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
        }
        .avatar-sm {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="mb-2">
                                <i class="fas fa-user-graduate text-primary me-3"></i>
                                Enrollment Management
                            </h1>
                            <p class="text-muted mb-0">Manage student enrollments and track course participation</p>
                        </div>
                        <div>
                            <button class="btn btn-primary me-2" onclick="showBulkEnrollModal()">
                                <i class="fas fa-users me-1"></i>Bulk Enrollment
                            </button>
                            <button class="btn btn-info" onclick="exportEnrollments()">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                            <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-users fa-2x mb-3"></i>
                    <div class="stats-number" id="total-enrollments">0</div>
                    <div>Total Enrollments</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <i class="fas fa-check-circle fa-2x mb-3"></i>
                    <div class="stats-number" id="active-enrollments">0</div>
                    <div>Active Enrollments</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
                    <i class="fas fa-trophy fa-2x mb-3"></i>
                    <div class="stats-number" id="completed-enrollments">0</div>
                    <div>Completed</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                    <i class="fas fa-times-circle fa-2x mb-3"></i>
                    <div class="stats-number" id="dropped-enrollments">0</div>
                    <div>Dropped</div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-3">
                <div class="dashboard-card p-4">
                    <h5 class="mb-3">Enrollment Trends</h5>
                    <div class="chart-container">
                        <canvas id="enrollmentChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="dashboard-card p-4">
                    <h5 class="mb-3">Course Distribution</h5>
                    <div class="chart-container">
                        <canvas id="courseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollments Table -->
        <div class="row">
            <div class="col-12">
                <div class="dashboard-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Recent Enrollments</h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                                <option value="">All Status</option>
                                <option value="enrolled">Enrolled</option>
                                <option value="completed">Completed</option>
                                <option value="dropped">Dropped</option>
                            </select>
                            <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search..." style="width: 200px;">
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshEnrollments()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Enrollment Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="enrollmentsTableBody">
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-muted">Loading enrollments...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <nav aria-label="Enrollment pagination">
                        <ul class="pagination justify-content-center" id="pagination">
                            <!-- Pagination will be loaded here -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Enrollment Modal -->
    <div class="modal fade" id="bulkEnrollModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Enrollment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="bulkEnrollForm">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="courseSelect" class="form-label">Select Course</label>
                            <select class="form-select" id="courseSelect" name="course_id" required>
                                <option value="">Choose a course...</option>
                                <?php if (!empty($courses)): ?>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?= $course['id'] ?>"><?= esc($course['title']) ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No courses available</option>
                                <?php endif; ?>
                            </select>
                            <!-- Debug: Show course count -->
                            <small class="text-muted">Available courses: <?= count($courses) ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="studentSelect" class="form-label">Select Students</label>
                            <select class="form-select" id="studentSelect" name="students[]" multiple style="height: 120px;">
                                <option value="">Loading students...</option>
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple students</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="processBulkEnroll()">Enroll Selected</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Enrollment Modal -->
    <div class="modal fade" id="editEnrollmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Enrollment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editEnrollmentForm">
                        <input type="hidden" id="editEnrollmentId" name="id">
                        
                        <div class="mb-3">
                            <label for="editCourseSelect" class="form-label">Course</label>
                            <select class="form-select" id="editCourseSelect" name="course_id" required>
                                <option value="">Choose a course...</option>
                                <?php if (!empty($courses)): ?>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?= $course['id'] ?>"><?= esc($course['title']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editStatusSelect" class="form-label">Status</label>
                            <select class="form-select" id="editStatusSelect" name="status" required>
                                <option value="">Select status...</option>
                                <option value="enrolled">Enrolled</option>
                                <option value="completed">Completed</option>
                                <option value="dropped">Dropped</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editProgressInput" class="form-label">Progress (%)</label>
                            <input type="number" class="form-control" id="editProgressInput" name="progress" 
                                   min="0" max="100" placeholder="Enter progress percentage">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateEnrollment()">Update Enrollment</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let currentPage = 1;
        let enrollmentsData = [];

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            loadEnrollmentStats();
            loadEnrollments();
            loadCourses();
            loadStudents();
            initCharts();
        });

        // Load enrollment statistics
        function loadEnrollmentStats() {
            fetch('<?= base_url('/admin/enrollment-stats') ?>')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-enrollments').textContent = data.total_enrollments || 0;
                    document.getElementById('active-enrollments').textContent = data.active_enrollments || 0;
                    document.getElementById('completed-enrollments').textContent = data.completed_enrollments || 0;
                    document.getElementById('dropped-enrollments').textContent = data.dropped_enrollments || 0;
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                    // Set default values on error
                    document.getElementById('total-enrollments').textContent = '0';
                    document.getElementById('active-enrollments').textContent = '0';
                    document.getElementById('completed-enrollments').textContent = '0';
                    document.getElementById('dropped-enrollments').textContent = '0';
                });
        }

        // Load enrollments
        function loadEnrollments(page = 1) {
            const status = document.getElementById('statusFilter').value;
            const search = document.getElementById('searchInput').value;
            
            let url = `<?= base_url('/admin/enrollment-list') ?>?page=${page}`;
            if (status) url += `&status=${status}`;
            if (search) url += `&search=${search}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    enrollmentsData = data.enrollments || [];
                    renderEnrollmentsTable(enrollmentsData);
                    renderPagination(data.pagination);
                })
                .catch(error => {
                    console.error('Error loading enrollments:', error);
                    document.getElementById('enrollmentsTableBody').innerHTML = 
                        '<tr><td colspan="6" class="text-center text-danger">Error loading enrollments</td></tr>';
                });
        }

        // Render enrollments table
        function renderEnrollmentsTable(enrollments) {
            const tbody = document.getElementById('enrollmentsTableBody');
            
            if (!enrollments || enrollments.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No enrollments found</td></tr>';
                return;
            }
            
            tbody.innerHTML = '';
            
            enrollments.forEach(enrollment => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                ${enrollment.first_name ? enrollment.first_name.charAt(0).toUpperCase() : 'S'}
                            </div>
                            <div>
                                <div class="fw-semibold">${enrollment.first_name || 'Unknown'} ${enrollment.last_name || ''}</div>
                                <small class="text-muted">${enrollment.email || ''}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold">${enrollment.course_title || 'Unknown Course'}</div>
                        <small class="text-muted">ID: ${enrollment.course_id}</small>
                    </td>
                    <td>
                        <span class="status-badge ${getStatusClass(enrollment.status)}">
                            ${enrollment.status || 'unknown'}
                        </span>
                    </td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar ${getProgressClass(enrollment.progress)}" 
                                 style="width: ${enrollment.progress || 0}%">
                                ${enrollment.progress || 0}%
                            </div>
                        </div>
                    </td>
                    <td>
                        <small>${formatDate(enrollment.enrollment_date)}</small>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-primary action-btn" onclick="viewEnrollment(${enrollment.id})" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning action-btn" onclick="editEnrollment(${enrollment.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info action-btn" onclick="viewCourseStudents(${enrollment.course_id})" title="Students">
                                <i class="fas fa-users"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning action-btn" onclick="viewEnrollmentStats(${enrollment.course_id})" title="Stats">
                                <i class="fas fa-chart-line"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger action-btn" onclick="deleteEnrollment(${enrollment.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Helper functions
        function getStatusClass(status) {
            switch(status) {
                case 'enrolled': return 'bg-success text-white';
                case 'completed': return 'bg-info text-white';
                case 'dropped': return 'bg-warning text-dark';
                default: return 'bg-secondary text-white';
            }
        }

        function getProgressClass(progress) {
            if (progress >= 80) return 'bg-success';
            if (progress >= 50) return 'bg-info';
            if (progress >= 20) return 'bg-warning';
            return 'bg-danger';
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }

        // Render pagination
        function renderPagination(pagination) {
            const paginationElement = document.getElementById('pagination');
            paginationElement.innerHTML = '';
            
            if (!pagination || pagination.total_pages <= 1) return;
            
            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${pagination.current_page === 1 ? 'disabled' : ''}`;
            const prevLink = document.createElement('a');
            prevLink.className = 'page-link';
            prevLink.href = '#';
            prevLink.textContent = 'Previous';
            prevLink.addEventListener('click', function(e) {
                e.preventDefault();
                if (pagination.current_page > 1) {
                    loadEnrollments(pagination.current_page - 1);
                }
            });
            prevLi.appendChild(prevLink);
            paginationElement.appendChild(prevLi);
            
            // Page numbers
            for (let i = 1; i <= pagination.total_pages; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === pagination.current_page ? 'active' : ''}`;
                const link = document.createElement('a');
                link.className = 'page-link';
                link.href = '#';
                link.textContent = i;
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    loadEnrollments(i);
                });
                li.appendChild(link);
                paginationElement.appendChild(li);
            }
            
            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}`;
            const nextLink = document.createElement('a');
            nextLink.className = 'page-link';
            nextLink.href = '#';
            nextLink.textContent = 'Next';
            nextLink.addEventListener('click', function(e) {
                e.preventDefault();
                if (pagination.current_page < pagination.total_pages) {
                    loadEnrollments(pagination.current_page + 1);
                }
            });
            nextLi.appendChild(nextLink);
            paginationElement.appendChild(nextLi);
        }

        // Modal functions
        function showBulkEnrollModal() {
            const modal = new bootstrap.Modal(document.getElementById('bulkEnrollModal'));
            modal.show();
        }

        // Event listeners
        document.getElementById('statusFilter').addEventListener('change', () => loadEnrollments());
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') loadEnrollments();
        });

        function refreshEnrollments() {
            loadEnrollmentStats();
            loadEnrollments();
        }

        // Working button functions
        function viewEnrollment(id) {
            const enrollment = enrollmentsData.find(e => e.id == id);
            if (enrollment) {
                const details = `
                    Enrollment Details:
                    -----------------
                    Student: ${(enrollment.first_name || '') + ' ' + (enrollment.last_name || '') || 'Unknown Student'}
                    Email: ${enrollment.email || 'No Email'}
                    Course: ${enrollment.course_title || 'Unknown Course'}
                    Status: ${enrollment.status || 'Unknown'}
                    Progress: ${enrollment.progress || 0}%
                    Enrollment Date: ${formatDate(enrollment.enrollment_date)}
                `;
                alert(details);
            }
        }

        function viewCourseStudents(courseId) {
            window.open(`<?= base_url('/admin/course-students') ?>?course_id=${courseId}`, '_blank', 'width=800,height=600');
        }

        function viewEnrollmentStats(courseId) {
            window.open(`<?= base_url('/admin/course-stats') ?>?course_id=${courseId}`, '_blank', 'width=800,height=600');
        }

        function deleteEnrollment(id) {
            if (confirm('Are you sure you want to delete this enrollment? This action cannot be undone.')) {
                fetch('<?= base_url('/admin/delete-enrollment') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({enrollment_id: id})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Enrollment deleted successfully!');
                        refreshEnrollments();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the enrollment.');
                });
            }
        }

        function exportEnrollments() {
            console.log('Export enrollments');
            
            // Get current filter values
            const search = document.getElementById('searchInput')?.value || '';
            const status = document.getElementById('statusFilter')?.value || '';
            
            // Build export URL with filters
            let exportUrl = '<?= base_url('admin/export-enrollments') ?>';
            const params = new URLSearchParams();
            
            if (search) params.append('search', search);
            if (status) params.append('status', status);
            
            if (params.toString()) {
                exportUrl += '?' + params.toString();
            }
            
            // Create download link
            const link = document.createElement('a');
            link.href = exportUrl;
            link.download = 'enrollments_export.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function processBulkEnroll() {
            const courseId = document.getElementById('courseSelect').value;
            const studentSelect = document.getElementById('studentSelect');
            const selectedStudents = Array.from(studentSelect.selectedOptions).map(option => option.value);
            
            if (!courseId) {
                alert('Please select a course');
                return;
            }
            
            if (selectedStudents.length === 0) {
                alert('Please select at least one student');
                return;
            }
            
            // Show loading state
            const enrollButton = event.target;
            const originalText = enrollButton.textContent;
            enrollButton.textContent = 'Enrolling...';
            enrollButton.disabled = true;
            
            // Create FormData for better CSRF handling
            const formData = new FormData();
            formData.append('course_id', courseId);
            
            // Add each student ID
            selectedStudents.forEach((studentId, index) => {
                formData.append(`student_ids[${index}]`, studentId);
            });
            
            // Add CSRF token from form
            const csrfToken = document.querySelector('input[name="csrf_test_name"]')?.value;
            if (csrfToken) {
                formData.append('csrf_test_name', csrfToken);
            }
            
            // Send bulk enrollment request
            fetch('<?= base_url('/admin/bulk-enroll') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Successfully enrolled ${selectedStudents.length} student(s) in the course!`);
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('bulkEnrollModal'));
                    modal.hide();
                    
                    // Refresh enrollments
                    refreshEnrollments();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing bulk enrollment.');
            })
            .finally(() => {
                // Restore button state
                enrollButton.textContent = originalText;
                enrollButton.disabled = false;
            });
        }

        // Edit enrollment functions
        function editEnrollment(id) {
            fetch(`<?= base_url('/admin/get-enrollment') ?>/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const enrollment = data.enrollment;
                        
                        // Populate form fields
                        document.getElementById('editEnrollmentId').value = enrollment.id;
                        document.getElementById('editCourseSelect').value = enrollment.course_id;
                        document.getElementById('editStatusSelect').value = enrollment.status;
                        document.getElementById('editProgressInput').value = enrollment.progress || 0;
                        
                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('editEnrollmentModal'));
                        modal.show();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while loading enrollment data.');
                });
        }

        function updateEnrollment() {
            const form = document.getElementById('editEnrollmentForm');
            const formData = new FormData(form);
            
            // Convert FormData to JSON
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            fetch('<?= base_url('/admin/edit-enrollment') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Enrollment updated successfully!');
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editEnrollmentModal'));
                    modal.hide();
                    
                    // Refresh enrollments
                    refreshEnrollments();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the enrollment.');
            });
        }

        function loadCourses() {
            // First try to load from API
            fetch('<?= base_url('/admin/course-list') ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    const bulkSelect = document.getElementById('courseSelect');
                    const editSelect = document.getElementById('editCourseSelect');
                    
                    // Reset selects
                    bulkSelect.innerHTML = '<option value="">Choose a course...</option>';
                    if (editSelect) {
                        editSelect.innerHTML = '<option value="">Choose a course...</option>';
                    }
                    
                    if (data && data.courses && data.courses.length > 0) {
                        // Use API data
                        data.courses.forEach(course => {
                            bulkSelect.innerHTML += `<option value="${course.id}">${course.title}</option>`;
                            if (editSelect) {
                                editSelect.innerHTML += `<option value="${course.id}">${course.title}</option>`;
                            }
                        });
                    } else {
                        // Fallback to mock data
                        const mockCourses = [
                            {id: 1, title: 'Introduction to Computer Science'},
                            {id: 2, title: 'Web Development Fundamentals'},
                            {id: 3, title: 'Database Management Systems'},
                            {id: 4, title: 'Advanced Programming'},
                            {id: 5, title: 'Network Security'},
                            {id: 6, title: 'Mobile App Development'},
                            {id: 7, title: 'Data Science Fundamentals'},
                            {id: 8, title: 'Machine Learning Basics'}
                        ];
                        
                        mockCourses.forEach(course => {
                            bulkSelect.innerHTML += `<option value="${course.id}">${course.title}</option>`;
                            if (editSelect) {
                                editSelect.innerHTML += `<option value="${course.id}">${course.title}</option>`;
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading courses:', error);
                    // Fallback to mock data on error
                    const bulkSelect = document.getElementById('courseSelect');
                    const editSelect = document.getElementById('editCourseSelect');
                    
                    bulkSelect.innerHTML = '<option value="">Choose a course...</option>';
                    if (editSelect) {
                        editSelect.innerHTML = '<option value="">Choose a course...</option>';
                    }
                    
                    const mockCourses = [
                        {id: 1, title: 'Introduction to Computer Science'},
                        {id: 2, title: 'Web Development Fundamentals'},
                        {id: 3, title: 'Database Management Systems'},
                        {id: 4, title: 'Advanced Programming'},
                        {id: 5, title: 'Network Security'},
                        {id: 6, title: 'Mobile App Development'},
                        {id: 7, title: 'Data Science Fundamentals'},
                        {id: 8, title: 'Machine Learning Basics'}
                    ];
                    
                    mockCourses.forEach(course => {
                        bulkSelect.innerHTML += `<option value="${course.id}">${course.title}</option>`;
                        if (editSelect) {
                            editSelect.innerHTML += `<option value="${course.id}">${course.title}</option>`;
                        }
                    });
                });
        }

        function loadStudents() {
            fetch('<?= base_url('/admin/students-list') ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    const studentSelect = document.getElementById('studentSelect');
                    studentSelect.innerHTML = '<option value="">Select students...</option>';
                    
                    if (data && data.students && data.students.length > 0) {
                        // Use API data
                        data.students.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.textContent = `${student.first_name} ${student.last_name} (${student.email})`;
                            studentSelect.appendChild(option);
                        });
                    } else {
                        // Fallback to mock data
                        const mockStudents = [
                            {id: 1, first_name: 'John', last_name: 'Doe', email: 'john.doe@example.com'},
                            {id: 2, first_name: 'Jane', last_name: 'Smith', email: 'jane.smith@example.com'},
                            {id: 3, first_name: 'Peter', last_name: 'Jones', email: 'peter.jones@example.com'},
                            {id: 4, first_name: 'Mary', last_name: 'Brown', email: 'mary.brown@example.com'},
                            {id: 5, first_name: 'James', last_name: 'Wilson', email: 'james.wilson@example.com'},
                            {id: 6, first_name: 'Sarah', last_name: 'Taylor', email: 'sarah.taylor@example.com'}
                        ];
                        
                        mockStudents.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.textContent = `${student.first_name} ${student.last_name} (${student.email})`;
                            studentSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                    // Fallback to mock data on error
                    const studentSelect = document.getElementById('studentSelect');
                    studentSelect.innerHTML = '<option value="">Select students...</option>';
                    
                    const mockStudents = [
                        {id: 1, first_name: 'John', last_name: 'Doe', email: 'john.doe@example.com'},
                        {id: 2, first_name: 'Jane', last_name: 'Smith', email: 'jane.smith@example.com'},
                        {id: 3, first_name: 'Peter', last_name: 'Jones', email: 'peter.jones@example.com'},
                        {id: 4, first_name: 'Mary', last_name: 'Brown', email: 'mary.brown@example.com'},
                        {id: 5, first_name: 'James', last_name: 'Wilson', email: 'james.wilson@example.com'},
                        {id: 6, first_name: 'Sarah', last_name: 'Taylor', email: 'sarah.taylor@example.com'}
                    ];
                    
                    mockStudents.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = `${student.first_name} ${student.last_name} (${student.email})`;
                        studentSelect.appendChild(option);
                    });
                });
        }

        function initCharts() {
            // Initialize enrollment trends chart
            const ctx1 = document.getElementById('enrollmentChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'New Enrollments',
                        data: [12, 19, 3, 5, 2, 3],
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Initialize course distribution chart
            const ctx2 = document.getElementById('courseChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Math', 'Science', 'English', 'History'],
                    datasets: [{
                        data: [30, 25, 20, 25],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
