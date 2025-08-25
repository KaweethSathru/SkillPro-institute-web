<?php include 'header.php'; ?>

<?php
include '../components/connection.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $instructor_id = $_POST['instructor_id'];
    $class_date = $_POST['class_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $topic = $_POST['topic'];
    $location = $_POST['location'];
    
    // Insert into database
    $sql = "INSERT INTO class_schedules (course_id, instructor_id, class_date, start_time, end_time, topic, location) 
            VALUES (:course_id, :instructor_id, :class_date, :start_time, :end_time, :topic, :location)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'course_id' => $course_id,
        'instructor_id' => $instructor_id,
        'class_date' => $class_date,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'topic' => $topic,
        'location' => $location
    ]);
    
    if ($stmt->rowCount() > 0) {
        $success_message = "Class schedule added successfully!";
    } else {
        $error_message = "Failed to add class schedule. Please try again.";
    }
}

// Get all active courses
$courses_sql = "SELECT * FROM courses WHERE start_date <= CURDATE() ORDER BY name";
$courses_stmt = $conn->prepare($courses_sql);
$courses_stmt->execute();
$courses = $courses_stmt->fetchAll();

// Get all instructors
$instructors_sql = "SELECT * FROM instructors WHERE status = 'Active' ORDER BY full_name";
$instructors_stmt = $conn->prepare($instructors_sql);
$instructors_stmt->execute();
$instructors = $instructors_stmt->fetchAll();

// Get all schedules
$schedules_sql = "SELECT cs.*, c.name as course_name, i.full_name as instructor_name 
                  FROM class_schedules cs
                  JOIN courses c ON cs.course_id = c.id
                  JOIN instructors i ON cs.instructor_id = i.id
                  ORDER BY cs.class_date DESC, cs.start_time DESC";
$schedules_stmt = $conn->prepare($schedules_sql);
$schedules_stmt->execute();
$schedules = $schedules_stmt->fetchAll();
?>

<div class="admin-content-header">
    <h1>Manage Class Schedules</h1>
    <button class="btn" onclick="document.getElementById('add-schedule-modal').style.display='block'">
        <i class="fas fa-plus"></i> Add New Schedule
    </button>
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
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Instructor</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Topic</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($schedules) > 0): ?>
                    <?php foreach ($schedules as $schedule): 
                        $class_date = date('M d, Y', strtotime($schedule['class_date']));
                        $start_time = date('h:i A', strtotime($schedule['start_time']));
                        $end_time = date('h:i A', strtotime($schedule['end_time']));
                    ?>
                    <tr>
                        <td><?= $schedule['course_name'] ?></td>
                        <td><?= $schedule['instructor_name'] ?></td>
                        <td><?= $class_date ?></td>
                        <td><?= $start_time ?> - <?= $end_time ?></td>
                        <td><?= $schedule['topic'] ?></td>
                        <td><?= $schedule['location'] ?></td>
                        <td class="actions">
                            <a href="#" class="btn-icon edit-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="schedule.php?delete=<?= $schedule['id'] ?>" class="btn-icon delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this schedule?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="no-results">
                                <i class="fas fa-calendar-times fa-3x"></i>
                                <h3>No Schedules Found</h3>
                                <p>Add a new class schedule to get started</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Schedule Modal -->
<div id="add-schedule-modal" class="admin-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Class Schedule</h3>
            <span class="close-modal" onclick="document.getElementById('add-schedule-modal').style.display='none'">&times;</span>
        </div>
        <div class="modal-body">
            <form method="POST" action="schedule.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="course_id">Course</label>
                        <select id="course_id" name="course_id" class="form-control" required>
                            <option value="">Select a course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['id'] ?>"><?= $course['name'] ?> (<?= $course['branch'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="instructor_id">Instructor</label>
                        <select id="instructor_id" name="instructor_id" class="form-control" required>
                            <option value="">Select an instructor</option>
                            <?php foreach ($instructors as $instructor): ?>
                                <option value="<?= $instructor['id'] ?>"><?= $instructor['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="class_date">Class Date</label>
                        <input type="date" id="class_date" name="class_date" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input type="time" id="start_time" name="start_time" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input type="time" id="end_time" name="end_time" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="topic">Topic (Optional)</label>
                    <input type="text" id="topic" name="topic" class="form-control" placeholder="Enter class topic">
                </div>
                
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" class="form-control" placeholder="Enter class location" required>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn secondary" onclick="document.getElementById('add-schedule-modal').style.display='none'">Cancel</button>
                    <button type="submit" class="btn">Add Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .admin-content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .text-center {
        text-align: center;
    }
    
    .no-results {
        padding: 40px 20px;
        color: #777;
    }
    
    .no-results i {
        color: #e0e0e0;
        margin-bottom: 15px;
    }
    
    .no-results h3 {
        margin-bottom: 10px;
        color: var(--primary);
    }
</style>

<script>
    // Modal functionality
    window.onclick = function(event) {
        const modal = document.getElementById('add-schedule-modal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    
    // Set minimum date to today
    document.getElementById('class_date').min = new Date().toISOString().split("T")[0];
</script>

<?php include 'footer.php'; ?>