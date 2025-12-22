<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
        .action-btn {
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
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
                                <i class="fas fa-users text-primary me-3"></i>
                                User Management
                            </h1>
                            <p class="text-muted mb-0">Manage system users, roles, and permissions</p>
                        </div>
                        <div>
                            <button class="btn btn-success me-2" onclick="showCreateUserModal()">
                                <i class="fas fa-plus-circle me-1"></i>Add User
                            </button>
                            <button class="btn btn-secondary" onclick="goToDashboard()">
                                <i class="fas fa-arrow-left me-1"></i>Back to Admin
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-card p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search users..." onkeyup="searchUsers()">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end gap-2">
                                <select class="form-select" id="roleFilter" style="width: auto;" onchange="filterUsers()">
                                    <option value="">All Roles</option>
                                    <option value="admin">Admin</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="student">Student</option>
                                </select>
                                <select class="form-select" id="statusFilter" style="width: auto;" onchange="filterUsers()">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <button class="btn btn-primary" onclick="refreshUsers()">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="row">
            <div class="col-12">
                <div class="dashboard-card p-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <?= strtoupper(substr($user['first_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'success' : 'info') ?>">
                                            <?= ucfirst(esc($user['role'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst(esc($user['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-primary action-btn" onclick="viewUser(<?= $user['id'] ?>)" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning action-btn" onclick="editUser(<?= $user['id'] ?>)" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger action-btn" onclick="deleteUser(<?= $user['id'] ?>)" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <?= csrf_field() ?>
                        <input type="hidden" id="userId" name="id">
                        <div class="mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3" id="passwordField">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveUser()">Save User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewUserContent">
                    <!-- User details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        let usersData = <?= json_encode($users) ?>;

        // Go to dashboard function
        function goToDashboard() {
            window.location.href = '/dashboard'; // Navigate to Student Portal dashboard
        }

        // Show create user modal
        function showCreateUserModal() {
            document.getElementById('userModalTitle').textContent = 'Create User';
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('passwordField').style.display = 'block';
            document.getElementById('password').required = true;
            
            const modal = new bootstrap.Modal(document.getElementById('userModal'));
            modal.show();
        }

        // View user details
        function viewUser(id) {
            const user = usersData.find(u => u.id == id);
            if (user) {
                const content = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID:</strong> ${user.id || 'N/A'}</p>
                            <p><strong>Name:</strong> ${(user.first_name || '') + ' ' + (user.last_name || '') || 'No Name'}</p>
                            <p><strong>Email:</strong> ${user.email || 'No Email'}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Role:</strong> <span class="badge bg-${user.role === 'admin' ? 'danger' : (user.role === 'teacher' ? 'success' : 'info')}">${user.role || 'Unknown'}</span></p>
                            <p><strong>Status:</strong> <span class="badge bg-${user.status === 'active' ? 'success' : 'secondary'}">${user.status || 'Unknown'}</span></p>
                        </div>
                    </div>
                `;
                document.getElementById('viewUserContent').innerHTML = content;
                
                const modal = new bootstrap.Modal(document.getElementById('viewUserModal'));
                modal.show();
            }
        }

        // Edit user
        function editUser(id) {
            const user = usersData.find(u => u.id == id);
            if (user) {
                document.getElementById('userModalTitle').textContent = 'Edit User';
                document.getElementById('userId').value = user.id || '';
                document.getElementById('firstName').value = user.first_name || '';
                document.getElementById('lastName').value = user.last_name || '';
                document.getElementById('email').value = user.email || '';
                document.getElementById('role').value = user.role || 'student';
                document.getElementById('status').value = user.status || 'active';
                document.getElementById('passwordField').style.display = 'none';
                document.getElementById('password').required = false;
                
                const modal = new bootstrap.Modal(document.getElementById('userModal'));
                modal.show();
            }
        }

        // Delete user
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                fetch('<?= base_url('/admin/delete-user') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({user_id: id})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the user.');
                });
            }
        }

        // Save user (create or update)
        function saveUser() {
            console.log('saveUser function called');
            const form = document.getElementById('userForm');
            console.log('Form element:', form);
            
            if (!form) {
                alert('Form not found!');
                return;
            }
            
            const formData = new FormData(form);
            const userId = document.getElementById('userId').value;
            
            console.log('User ID:', userId);
            console.log('Form data:', Object.fromEntries(formData));
            
            const isEdit = userId !== '';
            const url = isEdit ? '<?= base_url('/admin/update-user') ?>' : '<?= base_url('/admin/create-user') ?>';
            
            console.log('URL:', url);
            console.log('Is Edit:', isEdit);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert(isEdit ? 'User updated successfully!' : 'User created successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the user: ' + error.message);
            });
        }

        // Search users
        function searchUsers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const filteredUsers = usersData.filter(user => 
                (user.first_name + ' ' + user.last_name).toLowerCase().includes(searchTerm) || 
                user.email.toLowerCase().includes(searchTerm)
            );
            renderUsersTable(filteredUsers);
        }

        // Filter users
        function filterUsers() {
            const roleFilter = document.getElementById('roleFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            let filteredUsers = usersData;
            
            if (roleFilter) {
                filteredUsers = filteredUsers.filter(user => user.role === roleFilter);
            }
            
            if (statusFilter) {
                filteredUsers = filteredUsers.filter(user => user.status === statusFilter);
            }
            
            renderUsersTable(filteredUsers);
        }

        // Render users table
        function renderUsersTable(users) {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';
            
            users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.id || 'N/A'}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                ${(user.first_name || 'U').charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <div class="fw-semibold">${(user.first_name || '') + ' ' + (user.last_name || '') || 'No Name'}</div>
                            </div>
                        </div>
                    </td>
                    <td>${user.email || 'No Email'}</td>
                    <td>
                        <span class="badge bg-${user.role === 'admin' ? 'danger' : (user.role === 'teacher' ? 'success' : 'info')}">
                            ${user.role || 'Unknown'}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-${user.status === 'active' ? 'success' : 'secondary'}">
                            ${user.status || 'Unknown'}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-primary action-btn" onclick="viewUser(${user.id})" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning action-btn" onclick="editUser(${user.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger action-btn" onclick="deleteUser(${user.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Refresh users
        function refreshUsers() {
            location.reload();
        }
    </script>
</body>
</html>
