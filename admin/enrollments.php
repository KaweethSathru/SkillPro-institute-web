<?php include 'header.php'; ?>

<?php
include '../components/connection.php';

// Handle enrollment status update
if (isset($_POST['update_status'])) {
    $enrollment_id = $_POST['enrollment_id'];
    $new_status = $_POST['new_status'];
    
    $update_sql = "UPDATE enrollments SET payment_status = :status WHERE id = :id";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->execute(['status' => $new_status, 'id' => $enrollment_id]);
    
    if ($update_stmt->rowCount() > 0) {
        $success_message = "Enrollment status updated successfully!";
    } else {
        $error_message = "Failed to update enrollment status.";
    }
}

// Get all enrollments with student and course details
$sql = "SELECT 
            enrollments.*, 
            students.full_name AS student_name, 
            students.email AS student_email, 
            students.phone AS student_phone,
            courses.name AS course_name,
            courses.fees AS course_fees,
            courses.duration AS course_duration,
            courses.mode AS course_mode,
            instructors.full_name AS instructor_name
        FROM enrollments
        JOIN students ON enrollments.student_id = students.id
        JOIN courses ON enrollments.course_id = courses.id
        LEFT JOIN instructors ON courses.instructor_id = instructors.id
        ORDER BY enrollments.enrolled_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$enrollments = $stmt->fetchAll();
?>

<div class="admin-content-header">
    <h1>Manage Enrollments</h1>
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
        <div class="filter-group">
            <label for="status-filter">Payment Status:</label>
            <select id="status-filter" class="admin-select">
                <option value="all">All Statuses</option>
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="course-filter">Course:</label>
            <select id="course-filter" class="admin-select">
                <option value="all">All Courses</option>
                <?php
                $course_sql = "SELECT DISTINCT name FROM courses";
                $course_stmt = $conn->prepare($course_sql);
                $course_stmt->execute();
                $courses = $course_stmt->fetchAll();
                
                foreach ($courses as $course): 
                ?>
                <option value="<?= $course['name'] ?>"><?= $course['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="branch-filter">Branch:</label>
            <select id="branch-filter" class="admin-select">
                <option value="all">All Branches</option>
                <option value="Colombo">Colombo</option>
                <option value="Kandy">Kandy</option>
                <option value="Matara">Matara</option>
            </select>
        </div>
        
        <button class="btn" id="apply-filters"><i class="fas fa-filter"></i> Apply Filters</button>
    </div>

    <div class="table-responsive">
        <table class="admin-table" id="enrollments-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Instructor</th>
                    <th>Branch</th>
                    <th>Enrollment Date</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($enrollments) > 0): ?>
                    <?php foreach ($enrollments as $enrollment): 
                        $enrollment_date = date('M d, Y', strtotime($enrollment['enrolled_at']));
                    ?>
                    <tr>
                        <td>ENR-<?= str_pad($enrollment['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <div class="student-info">
                                <strong><?= $enrollment['student_name'] ?></strong>
                                <div class="student-contact">
                                    <span><i class="fas fa-envelope"></i> <?= $enrollment['student_email'] ?></span>
                                    <span><i class="fas fa-phone"></i> <?= $enrollment['student_phone'] ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong><?= $enrollment['course_name'] ?></strong>
                            <div class="course-meta">
                                <span><?= $enrollment['course_duration'] ?></span> | 
                                <span><?= $enrollment['course_mode'] ?></span>
                            </div>
                        </td>
                        <td><?= $enrollment['instructor_name'] ?: 'Not assigned' ?></td>
                        <td><?= $enrollment['branch'] ?></td>
                        <td><?= $enrollment_date ?></td>
                        <td><?= $enrollment['payment_method'] ?></td>
                        <td>
                            <span class="status-badge <?= $enrollment['payment_status'] === 'Completed' ? 'active' : 'inactive' ?>">
                                <?= $enrollment['payment_status'] ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="#" class="btn-icon edit-status" data-id="<?= $enrollment['id'] ?>" data-status="<?= $enrollment['payment_status'] ?>" title="Update Status">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No enrollments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Enrollment Details Modal -->
<div class="admin-modal" id="enrollment-details-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Enrollment Details</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body" id="details-content">
            <!-- Content will be loaded via AJAX -->
        </div>
        <div class="modal-footer">
            <button class="btn secondary close-modal">Close</button>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="admin-modal" id="update-status-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Update Payment Status</h3>
            <button class="close-modal">&times;</button>
        </div>
        <form method="post" action="enrollments.php">
            <div class="modal-body">
                <input type="hidden" name="enrollment_id" id="update-enrollment-id">
                <div class="form-group">
                    <label for="new_status">Select Status:</label>
                    <select class="admin-select" name="new_status" id="new_status" required>
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn secondary close-modal">Cancel</button>
                <button type="submit" name="update_status" class="btn">Update Status</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Filter functionality
    document.getElementById('apply-filters').addEventListener('click', function() {
        const statusFilter = document.getElementById('status-filter').value;
        const courseFilter = document.getElementById('course-filter').value;
        const branchFilter = document.getElementById('branch-filter').value;
        
        const table = document.getElementById('enrollments-table');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let row of rows) {
            const statusCell = row.cells[7].textContent || row.cells[7].innerText;
            const courseCell = row.cells[2].textContent || row.cells[2].innerText;
            const branchCell = row.cells[4].textContent || row.cells[4].innerText;
            
            const statusMatch = statusFilter === 'all' || statusCell.includes(statusFilter);
            const courseMatch = courseFilter === 'all' || courseCell.includes(courseFilter);
            const branchMatch = branchFilter === 'all' || branchCell.includes(branchFilter);
            
            row.style.display = (statusMatch && courseMatch && branchMatch) ? '' : 'none';
        }
    });
    
    // Update status modal
    document.querySelectorAll('.edit-status').forEach(button => {
        button.addEventListener('click', function() {
            const enrollmentId = this.getAttribute('data-id');
            const currentStatus = this.getAttribute('data-status');
            
            document.getElementById('update-enrollment-id').value = enrollmentId;
            document.getElementById('new_status').value = currentStatus;
            document.getElementById('update-status-modal').style.display = 'flex';
        });
    });
    
    // Close modals
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            modals.forEach(modal => modal.style.display = 'none');
        });
    });
</script>

<?php include 'footer.php'; ?>