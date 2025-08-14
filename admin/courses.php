<?php include 'header.php'; 

// Update query to include instructor information
$sql = "SELECT c.*, i.full_name AS instructor_name 
        FROM courses c 
        LEFT JOIN instructors i ON c.instructor_id = i.id 
        ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll();
?>

<div class="admin-content-header">
    <h1>Manage Courses</h1>
    <div class="action-buttons">
        <a href="add_course.php" class="btn"><i class="fas fa-plus"></i> Add New Course</a>
    </div>
</div>

<div class="admin-content-body">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
        <tr>
            <th>ID</th>
            <th>Course</th>
            <th>Category</th>
            <th>Instructor</th>
            <th>Duration</th>
            <th>Fees (LKR)</th>
            <th>Mode</th>
            <th>Branch</th>
            <th>Actions</th>
        </tr>
            </thead>
            <tbody>
                <?php foreach($courses as $course): ?>
                <tr>
                    <td><?= $course['id'] ?></td>
                    <td>
                        <div class="course-info">
                            <div class="course-image">
                                <img src="assets/images/courses/<?= $course['image'] ?: 'default-course.jpg' ?>" alt="<?= $course['name'] ?>">
                            </div>
                            <div>
                                <strong><?= $course['name'] ?></strong>
                                <div class="course-desc"><?= substr($course['description'], 0, 80) ?>...</div>
                            </div>
                        </div>
                    </td>
                    <td><?= $course['category'] ?></td>
                    <td>
                <?php if ($course['instructor_name']): ?>
                    <div class="instructor-info">
                        <span><?= $course['instructor_name'] ?></span>
                    </div>
                <?php else: ?>
                    <span class="text-muted">Not assigned</span>
                <?php endif; ?>
            </td>
                    <td><?= $course['duration'] ?></td>
                    <td><?= number_format($course['fees'], 2) ?></td>
                    <td><?= $course['mode'] ?></td>
                    <td><?= $course['branch'] ?></td>
                    <td class="actions">
                        <a href="edit_course.php?id=<?= $course['id'] ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="delete_course.php?id=<?= $course['id'] ?>" class="btn-icon delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this course?')"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>



<script>
    // Add loading indicator functionality
function updateInstructors() {
    const category = document.getElementById('category').value;
    const instructorSelect = document.getElementById('instructor_id');
    const loadingIndicator = document.getElementById('instructor-loading');
    
    // Show loading indicator
    if (loadingIndicator) loadingIndicator.style.display = 'block';
    instructorSelect.disabled = true;
    instructorSelect.innerHTML = '<option value="">Loading...</option>';
    
    // Simulate network delay for demo purposes
    setTimeout(() => {
        // Clear existing options
        instructorSelect.innerHTML = '';
        
        if (!category) {
            instructorSelect.innerHTML = '<option value="">Select a category first</option>';
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            return;
        }
        
        // Check if we have instructors for this specialization
        if (instructorsBySpecialization[category] && instructorsBySpecialization[category].length > 0) {
            instructorSelect.disabled = false;
            
            // Add instructors
            instructorsBySpecialization[category].forEach(instructor => {
                const option = document.createElement('option');
                option.value = instructor.id;
                option.textContent = instructor.name;
                if (currentInstructorId && instructor.id == currentInstructorId) {
                    option.selected = true;
                }
                instructorSelect.appendChild(option);
            });
        } else {
            instructorSelect.innerHTML = `<option value="">No ${category} instructors available</option>`;
        }
        
        // Hide loading indicator
        if (loadingIndicator) loadingIndicator.style.display = 'none';
    }, 500); // Simulate 500ms network delay
}
</script>