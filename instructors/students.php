<?php 
include 'header.php';

// Get the logged-in instructor ID
$instructor_id = $_SESSION['instructor_id'];

// Get students enrolled in courses taught by this instructor
$sql = "SELECT 
            students.id, 
            students.full_name, 
            students.email, 
            students.phone,
            courses.name AS course_name,
            enrollments.enrolled_at,
            enrollments.branch
        FROM enrollments
        JOIN students ON enrollments.student_id = students.id
        JOIN courses ON enrollments.course_id = courses.id
        WHERE courses.instructor_id = :instructor_id
        ORDER BY enrollments.enrolled_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute(['instructor_id' => $instructor_id]);
$students = $stmt->fetchAll();
?>

<h1 class="page-title">My Students</h1>

<div class="students-container">
    <?php if (count($students) > 0): ?>
        <div class="students-header">
            <div class="total-students">
                <i class="fas fa-user-graduate"></i>
                <span><?= count($students) ?> Students</span>
            </div>
            <div class="search-filter">
                <input type="text" id="search-students" placeholder="Search students...">
            </div>
        </div>
        
        <div class="students-grid">
            <?php foreach ($students as $student): 
                $enrollment_date = date('M d, Y', strtotime($student['enrolled_at']));
            ?>
            <div class="student-card">
                <div class="student-avatar">
                    <img src="assets/images/student-avatar.jpg" alt="Student Avatar">
                </div>
                <div class="student-info">
                    <h3><?= $student['full_name'] ?></h3>
                    <div class="student-meta">
                        <div><i class="fas fa-envelope"></i> <?= $student['email'] ?></div>
                        <div><i class="fas fa-phone"></i> <?= $student['phone'] ?></div>
                    </div>
                    <div class="course-info">
                        <span><i class="fas fa-book"></i> <?= $student['course_name'] ?></span>
                        <span><i class="fas fa-building"></i> <?= $student['branch'] ?></span>
                        <span><i class="fas fa-calendar-day"></i> <?= $enrollment_date ?></span>
                    </div>
                </div>
                <div class="student-actions">
                    <button class="btn-icon message-btn" title="Send Message">
                        <i class="fas fa-comment"></i>
                    </button>
                    <button class="btn-icon view-btn" title="View Profile">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-students">
            <div class="no-students-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h2>No Students Yet</h2>
            <p>You don't have any students enrolled in your courses.</p>
            <a href="#" class="btn">View My Courses</a>
        </div>
    <?php endif; ?>
</div>

<script>
    // Student search functionality
    document.getElementById('search-students').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const studentCards = document.querySelectorAll('.student-card');
        
        studentCards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const email = card.querySelector('.student-meta div:first-child').textContent.toLowerCase();
            const course = card.querySelector('.course-info span:first-child').textContent.toLowerCase();
            
            if (name.includes(searchTerm) || email.includes(searchTerm) || course.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>

<?php include 'footer.php'; ?>