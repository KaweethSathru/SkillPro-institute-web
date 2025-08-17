<?php include 'header.php'; ?>
<?php
include '../components/connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $instructor_id = $_POST['instructor_id'];
    $issue_date = $_POST['issue_date'];
    
    // Generate unique certificate number
    $certificate_number = 'SPC-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    $sql = "INSERT INTO certificates (student_id, course_id, certificate_number, issue_date, instructor_id)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$student_id, $course_id, $certificate_number, $issue_date, $instructor_id])) {
        $success = "Certificate issued successfully!";
    } else {
        $error = "Error issuing certificate. Please try again.";
    }
}

// Get all certificates
$sql = "SELECT c.*, s.full_name AS student_name, cr.name AS course_name, i.full_name AS instructor_name
        FROM certificates c
        JOIN students s ON c.student_id = s.id
        JOIN courses cr ON c.course_id = cr.id
        JOIN instructors i ON c.instructor_id = i.id
        ORDER BY c.issue_date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$certificates = $stmt->fetchAll();

// Get students for dropdown
$students = $conn->query("SELECT * FROM students")->fetchAll();

// Get courses for dropdown
$courses = $conn->query("SELECT * FROM courses")->fetchAll();

// Get instructors for dropdown
$instructors = $conn->query("SELECT * FROM instructors")->fetchAll();
?>

<h1 class="page-title">Certificate Management</h1>

<div class="admin-content-body">
    <?php if(isset($success)): ?>
        <div class="admin-alert success">
            <i class="fas fa-check-circle"></i> <?= $success ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($error)): ?>
        <div class="admin-alert error">
            <i class="fas fa-exclamation-circle"></i> <?= $error ?>
        </div>
    <?php endif; ?>
    
    <div class="admin-card">
        <h2>Issue New Certificate</h2>
        <form method="POST" class="certificate-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="student_id">Student *</label>
                    <select id="student_id" name="student_id" class="admin-select" required>
                        <option value="">Select Student</option>
                        <?php foreach($students as $student): ?>
                            <option value="<?= $student['id'] ?>">
                                <?= $student['full_name'] ?> (ID: STU-<?= str_pad($student['id'], 5, '0', STR_PAD_LEFT) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="course_id">Course *</label>
                    <select id="course_id" name="course_id" class="admin-select" required>
                        <option value="">Select Course</option>
                        <?php foreach($courses as $course): ?>
                            <option value="<?= $course['id'] ?>"><?= $course['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="instructor_id">Instructor *</label>
                    <select id="instructor_id" name="instructor_id" class="admin-select" required>
                        <option value="">Select Instructor</option>
                        <?php foreach($instructors as $instructor): ?>
                            <option value="<?= $instructor['id'] ?>"><?= $instructor['full_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="issue_date">Issue Date *</label>
                    <input type="date" id="issue_date" name="issue_date" class="admin-input" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">Issue Certificate</button>
            </div>
        </form>
    </div>
    
    <div class="admin-card">
        <h2>Issued Certificates</h2>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Certificate No.</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Instructor</th>
                        <th>Issue Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($certificates) > 0): ?>
                        <?php foreach($certificates as $cert): ?>
                            <tr>
                                <td><?= $cert['certificate_number'] ?></td>
                                <td><?= $cert['student_name'] ?></td>
                                <td><?= $cert['course_name'] ?></td>
                                <td><?= $cert['instructor_name'] ?></td>
                                <td><?= date('M d, Y', strtotime($cert['issue_date'])) ?></td>
                                <td class="actions">
                                    <a href="view_certificate.php?id=<?= $cert['id'] ?>" target="_blank" class="btn-icon" title="View Certificate">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No certificates issued yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>