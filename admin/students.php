<?php include 'header.php'; ?>

<?php
include '../components/connection.php';

// Handle student deletion
if (isset($_GET['delete'])) {
    $student_id = $_GET['delete'];
    
    // First, delete related enrollments
    $delete_enrollments = "DELETE FROM enrollments WHERE student_id = :id";
    $stmt_enroll = $conn->prepare($delete_enrollments);
    $stmt_enroll->execute(['id' => $student_id]);
    
    // Then delete the student
    $delete_student = "DELETE FROM students WHERE id = :id";
    $stmt_student = $conn->prepare($delete_student);
    $stmt_student->execute(['id' => $student_id]);
    
    if ($stmt_student->rowCount() > 0) {
        $success_message = "Student and related enrollments deleted successfully!";
    } else {
        $error_message = "Failed to delete student.";
    }
}

// Handle search
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM students 
            WHERE full_name LIKE :search 
            OR email LIKE :search 
            OR phone LIKE :search 
            OR username LIKE :search 
            ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['search' => "%$search%"]);
} else {
    $sql = "SELECT * FROM students ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$students = $stmt->fetchAll();
?>

<div class="admin-content-header">
    <h1>Manage Students</h1>
    <div class="action-buttons">
        <a href="#" class="btn"><i class="fas fa-plus"></i> Add New Student</a>
        <a href="#" class="btn secondary" id="export-students"><i class="fas fa-download"></i> Export Data</a>
    </div>
</div>

<?php if (isset($success_message)): ?>
    <div class="admin-alert success">
        <i class="fas fa-check-circle"></i> <?= $success_message ?>
    </div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="admin-alert error">
        <i class="fas fa-exclamation-circle"></i> <?= $error_message ?>
    </div>
<?php endif; ?>

<div class="admin-content-body">
    <div class="admin-filters">
        <form method="GET" action="students.php" class="search-form">
            <div class="search-group">
                <input type="text" name="search" placeholder="Search students..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn-icon"><i class="fas fa-search"></i></button>
            </div>
            <a href="students.php" class="btn secondary">Clear Filters</a>
        </form>
        
        <div class="filter-group">
            <label for="status-filter">Account Status:</label>
            <select id="status-filter" class="admin-select">
                <option value="all">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table" id="students-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Contact Information</th>
                    <th>Username</th>
                    <th>Enrollments</th>
                    <th>Last Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($students) > 0): ?>
                    <?php foreach ($students as $student): 
                        // Get enrollment count for this student
                        $enroll_sql = "SELECT COUNT(*) AS enroll_count FROM enrollments WHERE student_id = :id";
                        $enroll_stmt = $conn->prepare($enroll_sql);
                        $enroll_stmt->execute(['id' => $student['id']]);
                        $enrollment = $enroll_stmt->fetch();
                        $enroll_count = $enrollment['enroll_count'];
                    ?>
                    <tr>
                        <td>STU-<?= str_pad($student['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <div class="student-info">
                                <div class="student-avatar">
                                    <img src="assets/images/student-avatar.jpg" alt="Student Avatar">
                                </div>
                                <div>
                                    <strong><?= $student['full_name'] ?></strong>
                                    <div class="student-id">ID: STU-<?= str_pad($student['id'], 5, '0', STR_PAD_LEFT) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="contact-info">
                                <div><i class="fas fa-envelope"></i> <?= $student['email'] ?></div>
                                <div><i class="fas fa-phone"></i> <?= $student['phone'] ?: 'Not provided' ?></div>
                            </div>
                        </td>
                        <td><?= $student['username'] ?></td>
                        <td>
                            <div class="enrollment-info">
                                <span class="enrollment-count"><?= $enroll_count ?></span> courses
                            </div>
                        </td>
                        <td>Today</td>
                        <td class="actions">
                            <a href="#" class="btn-icon view-details" title="View Details"><i class="fas fa-eye"></i></a>
                            <a href="#" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="students.php?delete=<?= $student['id'] ?>" class="btn-icon delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this student? This will also remove all their enrollments.')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="no-results">
                                <i class="fas fa-user-graduate fa-3x"></i>
                                <h3>No Students Found</h3>
                                <p>Try adjusting your search or add a new student</p>
                                <a href="#" class="btn">Add New Student</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="pagination">
        <a href="#" class="page-item disabled"><i class="fas fa-chevron-left"></i></a>
        <a href="#" class="page-item active">1</a>
        <a href="#" class="page-item">2</a>
        <a href="#" class="page-item">3</a>
        <a href="#" class="page-item"><i class="fas fa-chevron-right"></i></a>
    </div>
</div>

<!-- Student Details Modal -->
<div class="admin-modal" id="student-details-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Student Details</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="student-details">
                <div class="detail-row">
                    <div class="detail-header">
                        <div class="student-avatar-lg">
                            <img src="assets/images/student-avatar.jpg" alt="Student Avatar">
                        </div>
                        <div>
                            <h2>John Doe</h2>
                            <div class="student-id">ID: STU-00125</div>
                            <div class="student-status"><span class="status-badge active">Active</span></div>
                        </div>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-section">
                        <h4><i class="fas fa-user"></i> Personal Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">Full Name</div>
                                <div class="detail-value">John Doe</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Username</div>
                                <div class="detail-value">johndoe123</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Email</div>
                                <div class="detail-value">john.doe@example.com</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Phone</div>
                                <div class="detail-value">+94 77 123 4567</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Registration Date</div>
                                <div class="detail-value">Aug 15, 2025</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <h4><i class="fas fa-book"></i> Enrollments</h4>
                        <div class="enrollments-list">
                            <div class="enrollment-item">
                                <div class="course-name">Software Engineering</div>
                                <div class="enrollment-meta">
                                    <span>Colombo Branch</span>
                                    <span>Online</span>
                                    <span>Enrolled: Aug 15, 2025</span>
                                </div>
                                <div class="payment-status"><span class="status-badge inactive">Pending Payment</span></div>
                            </div>
                            
                            <div class="enrollment-item">
                                <div class="course-name">Web Development Fundamentals</div>
                                <div class="enrollment-meta">
                                    <span>Colombo Branch</span>
                                    <span>On-site</span>
                                    <span>Enrolled: Jul 28, 2025</span>
                                </div>
                                <div class="payment-status"><span class="status-badge active">Completed</span></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <h4><i class="fas fa-chart-bar"></i> Activity Summary</h4>
                        <div class="activity-grid">
                            <div class="activity-stat">
                                <div class="stat-number">5</div>
                                <div class="stat-label">Courses Enrolled</div>
                            </div>
                            <div class="activity-stat">
                                <div class="stat-number">3</div>
                                <div class="stat-label">Certificates</div>
                            </div>
                            <div class="activity-stat">
                                <div class="stat-number">12</div>
                                <div class="stat-label">Completed Modules</div>
                            </div>
                            <div class="activity-stat">
                                <div class="stat-number">85%</div>
                                <div class="stat-label">Avg. Progress</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn secondary close-modal">Close</button>
            <button class="btn">Edit Profile</button>
        </div>
    </div>
</div>

<script>
    // Modal functionality
    const modals = document.querySelectorAll('.admin-modal');
    const closeButtons = document.querySelectorAll('.close-modal');
    
    // View details modal
    document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('student-details-modal').style.display = 'flex';
        });
    });
    
    // Close modals
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            modals.forEach(modal => modal.style.display = 'none');
        });
    });
    
    // Close modal when clicking outside
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Filter functionality
    document.getElementById('status-filter').addEventListener('change', function() {
        const status = this.value;
        const table = document.getElementById('students-table');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let row of rows) {
            if (row.cells.length < 2) continue;
            
            if (status === 'all') {
                row.style.display = '';
            } else {
                // This is just a placeholder - in real implementation you'd check actual status
                const isActive = Math.random() > 0.3; // Simulate active/inactive
                row.style.display = 
                    (status === 'active' && isActive) || 
                    (status === 'inactive' && !isActive) ? '' : 'none';
            }
        }
    });
    
    // Export functionality
    document.getElementById('export-students').addEventListener('click', function(e) {
        e.preventDefault();
        alert('Export functionality would be implemented here. Data would be exported in CSV format.');
    });
</script>

<?php include 'footer.php'; ?>