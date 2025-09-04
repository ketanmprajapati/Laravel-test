<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Management - Laravel 10</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #48bb78;
            --danger-color: #f56565;
            --warning-color: #ed8936;
            --info-color: #4299e1;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            padding: 2rem;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .btn-modern {
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-modern:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-modern:hover:before {
            left: 100%;
        }
        
        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .btn-success-modern {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }
        
        .btn-danger-modern {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
        }
        
        .btn-warning-modern {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            color: white;
        }
        
        .table-modern {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .table-modern thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .table-modern thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .table-modern tbody td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table-modern tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
            transform: scale(1.01);
            transition: all 0.3s ease;
        }
        
        .modal-modern .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .modal-modern .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
        }
        
        .form-control-modern {
            border-radius: 50px;
            border: 2px solid #e2e8f0;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }
        
        .form-control-modern:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .alert-modern {
            border-radius: 15px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-container">
            <div class="header-section text-center">
                <h1 class="mb-3">
                    <i class="fas fa-users me-3"></i>
                    User Management System
                </h1>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Users List
                </h3>
                <button type="button" class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openCreateModal()">
                    <i class="fas fa-plus me-2"></i>Add New User
                </button>
            </div>
            
            <div id="alert-container"></div>
            
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body">
                        @forelse($users as $user)
                        <tr id="user-row-{{ $user->id }}" class="fade-in">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning-modern btn-modern me-2" onclick="editUser({{ $user->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger-modern btn-modern" onclick="deleteUser({{ $user->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="no-users-row">
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No users found. Add your first user!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div class="modal fade modal-modern" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">
                        <i class="fas fa-user-plus me-2"></i>
                        <span id="modal-title">Add New User</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="user_id" name="user_id">
                        <input type="hidden" id="form_method" value="POST">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control form-control-modern" id="name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control form-control-modern" id="email" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control form-control-modern" id="password" name="password" required>
                            <div class="invalid-feedback"></div>
                            <div class="form-text" id="password-help">Leave blank to keep current password (when editing)</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success-modern btn-modern" id="saveUserBtn" onclick="saveUser()">
                        <i class="fas fa-save me-2"></i>
                        <span id="save-btn-text">Save User</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentUserId = null;

        function openCreateModal() {
            currentUserId = null;
            document.getElementById('userForm').reset();
            document.getElementById('modal-title').textContent = 'Add New User';
            document.getElementById('save-btn-text').textContent = 'Save User';
            document.getElementById('form_method').value = 'POST';
            document.getElementById('password').required = true;
            document.getElementById('password-help').style.display = 'none';
            clearValidationErrors();
        }

        function editUser(userId) {
            currentUserId = userId;
            
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('user_id').value = data.user.id;
                        document.getElementById('name').value = data.user.name;
                        document.getElementById('email').value = data.user.email;
                        document.getElementById('password').value = '';
                        document.getElementById('password').required = false;
                        document.getElementById('modal-title').textContent = 'Edit User';
                        document.getElementById('save-btn-text').textContent = 'Update User';
                        document.getElementById('form_method').value = 'PUT';
                        document.getElementById('password-help').style.display = 'block';
                        
                        clearValidationErrors();
                        Swal.close();
                        new bootstrap.Modal(document.getElementById('userModal')).show();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Failed to load user data.', 'error');
                });
        }

        function saveUser() {
            const form = document.getElementById('userForm');
            const formData = new FormData(form);
            const method = document.getElementById('form_method').value;
            const userId = document.getElementById('user_id').value;
            
            let url = '/users';
            let fetchOptions = {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            };

            if (method === 'PUT' && userId) {
                formData.append('_method', 'PUT');
                url = `/users/${userId}`;
            }

            document.getElementById('saveUserBtn').classList.add('loading');
            document.getElementById('saveUserBtn').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';

            fetch(url, fetchOptions)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                        updateUserTable(data.user, method);
                    } else {
                        if (data.errors) {
                            displayValidationErrors(data.errors);
                        } else {
                            showAlert('danger', 'An error occurred while saving the user.');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'An error occurred while saving the user.');
                })
                .finally(() => {
                    document.getElementById('saveUserBtn').classList.remove('loading');
                    document.getElementById('saveUserBtn').innerHTML = '<i class="fas fa-save me-2"></i><span id="save-btn-text">Save User</span>';
                });
        }

        function deleteUser(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f56565',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`user-row-${userId}`).remove();
                            showAlert('success', data.message);
                            
                            // Check if table is empty
                            const tbody = document.getElementById('users-table-body');
                            if (tbody.children.length === 0) {
                                tbody.innerHTML = `
                                    <tr id="no-users-row">
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">No users found. Add your first user!</p>
                                        </td>
                                    </tr>
                                `;
                            }
                        } else {
                            showAlert('danger', 'Failed to delete user.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('danger', 'An error occurred while deleting the user.');
                    });
                }
            });
        }

        function updateUserTable(user, method) {
            const tbody = document.getElementById('users-table-body');
            
            if (method === 'POST') {
                const noUsersRow = document.getElementById('no-users-row');
                if (noUsersRow) {
                    noUsersRow.remove();
                }
                
                const newRow = createUserRow(user, tbody.children.length + 1);
                tbody.insertAdjacentHTML('afterbegin', newRow);
            } else if (method === 'PUT') {
                const existingRow = document.getElementById(`user-row-${user.id}`);
                if (existingRow) {
                    const rowIndex = existingRow.children[0].textContent;
                    existingRow.innerHTML = `
                        <td>${rowIndex}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${new Date(user.updated_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</td>
                        <td>
                            <button class="btn btn-sm btn-warning-modern btn-modern me-2" onclick="editUser(${user.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger-modern btn-modern" onclick="deleteUser(${user.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                }
            }
        }

        function createUserRow(user, index) {
            return `
                <tr id="user-row-${user.id}" class="fade-in">
                    <td>${index}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${new Date(user.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</td>
                    <td>
                        <button class="btn btn-sm btn-warning-modern btn-modern me-2" onclick="editUser(${user.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger-modern btn-modern" onclick="deleteUser(${user.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        }

        function showAlert(type, message) {
            const alertContainer = document.getElementById('alert-container');
            const alertId = 'alert-' + Date.now();
            
            const alertHtml = `
                <div id="${alertId}" class="alert alert-${type} alert-modern fade-in" role="alert">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" onclick="document.getElementById('${alertId}').remove()"></button>
                </div>
            `;
            
            alertContainer.insertAdjacentHTML('beforeend', alertHtml);
            
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        function displayValidationErrors(errors) {
            clearValidationErrors();
            
            Object.keys(errors).forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = input.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = errors[field][0];
                    }
                }
            });
        }

        function clearValidationErrors() {
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
                const feedback = input.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = '';
                }
            });
        }

        document.getElementById('userModal').addEventListener('hidden.bs.modal', function () {
            clearValidationErrors();
        });
    </script>
</body>
</html>