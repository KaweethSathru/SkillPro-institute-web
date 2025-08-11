<?php include 'header.php'; ?>
<?php
include '../components/connection.php';
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
                    <th>Duration</th>
                    <th>Fees (LKR)</th>
                    <th>Mode</th>
                    <th>Branch</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM courses ORDER BY created_at DESC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $courses = $stmt->fetchAll();
                
                if(count($courses) > 0):
                    foreach($courses as $course):
                ?>
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
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No courses found. Add your first course!</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>