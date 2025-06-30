<?php
require '../config.php';
// Redirect to login if not logged in
$user_id = $_SESSION['user_id'];
if (!is_logged_in() || $_SESSION['user_gulo'] == true) {
    redirect('../');
}
// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start_from = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_condition = '';
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $search_condition = " WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}

// Fetch data
$sql = "SELECT * FROM users $search_condition ORDER BY id ASC LIMIT $start_from, $limit";
$result = $conn->query($sql);

// Total records count
$count_sql = "SELECT COUNT(*) as total FROM users $search_condition";
$total_result = $conn->query($count_sql);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Calculate page range
$start_range = max(1, $page - 2);
$end_range = min($total_pages, $page + 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced User Management</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --danger-color: #e74a3b;
            --dark-color: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', sans-serif;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
            border-bottom: none;
        }
        
        .user-table th {
            background-color: var(--dark-color);
            color: white;
            position: sticky;
            top: 0;
            font-weight: 600;
        }
        
        .table-container {
            max-height: 600px;
            overflow-y: auto;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }
        
        .btn-view {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-edit {
            color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-delete {
            color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .pagination .page-link {
            color: var(--primary-color);
        }
        
        .search-box {
            max-width: 400px;
        }
        
        .action-buttons .btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            border-radius: 0.25rem;
        }
        
        .status-active {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--secondary-color);
        }
        
        .status-inactive {
            background-color: rgba(231, 74, 59, 0.1);
            color: var(--danger-color);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="card shadow-lg">
            <div class="card-header text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-users-cog me-2"></i>User Management System</h3>
                    <div class="d-flex align-items-center">
                        <form class="d-flex search-box" method="get" action="">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control border-end-0" placeholder="Search users..." value="<?= htmlspecialchars($search) ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                <?php if (!empty($search)): ?>
                                    <a href="?" class="btn btn-danger ms-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                        <button class="btn btn-success ms-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-plus me-1"></i> Add User
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive table-container mb-4">
                        <table class="table table-hover user-table">
                            <thead>
                                <tr>
                                    <th width="35%">Name</th>
                                    <th width="40%">Email</th>
                                    <th width="25%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($row["name"]) ?>&background=random" class="rounded-circle" width="30" alt="User Avatar">
                                            </div>
                                            <?= htmlspecialchars($row["name"]) ?>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($row["email"]) ?></td>
                                    <td class="text-center action-buttons">
                                        <button class="btn btn-sm btn-view me-1" title="Login" onclick="location.href='login.php?id=<?=$row['id']?>'">
                                            <i class="fas fa-sign-in"></i>
                                        </button>
                                        <!-- <button class="btn btn-sm btn-edit me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button> 
                                        <button class="btn btn-sm btn-delete" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>-->
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing <strong><?= ($start_from + 1) ?></strong> to <strong><?= min($start_from + $limit, $total_rows) ?></strong> of <strong><?= $total_rows ?></strong> entries
                            <?php if (!empty($search)): ?>
                                (filtered from total)
                            <?php endif; ?>
                        </div>
                        
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                                <!-- First Page -->
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=1<?= !empty($search) ? '&search='.urlencode($search) : '' ?>" aria-label="First">
                                        <i class="fas fa-angle-double-left"></i>
                                    </a>
                                </li>
                                
                                <!-- Previous Page -->
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page-1 ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>" aria-label="Previous">
                                        <i class="fas fa-angle-left"></i>
                                    </a>
                                </li>
                                
                                <!-- Page Numbers -->
                                <?php if ($start_range > 1): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                                
                                <?php for ($i = $start_range; $i <= $end_range; $i++): ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($end_range < $total_pages): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                                
                                <!-- Next Page -->
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page+1 ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>" aria-label="Next">
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </li>
                                
                                <!-- Last Page -->
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $total_pages ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>" aria-label="Last">
                                        <i class="fas fa-angle-double-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-user-slash fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">
                            <?php if (!empty($search)): ?>
                                No users found matching your search criteria
                            <?php else: ?>
                                No users found in the database
                            <?php endif; ?>
                        </h4>
                        <?php if (!empty($search)): ?>
                            <a href="?" class="btn btn-primary mt-2">
                                <i class="fas fa-undo me-1"></i> Reset Search
                            </a>
                        <?php else: ?>
                            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fas fa-plus me-1"></i> Add New User
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addUserModalLabel"><i class="fas fa-user-plus me-2"></i>Add New User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="mb-3">
                            <label for="userName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="userName" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="userEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="userRole" class="form-label">Role</label>
                            <select class="form-select" id="userRole" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="editor">Editor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="userStatus" id="statusActive" value="1" checked>
                                <label class="form-check-label" for="statusActive">
                                    Active
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="userStatus" id="statusInactive" value="0">
                                <label class="form-check-label" for="statusInactive">
                                    Inactive
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Tooltip initialization
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>