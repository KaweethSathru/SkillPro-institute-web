<?php
include '../components/connection.php';
session_start();

// Check if instructor is logged in
if (!isset($_SESSION['instructor_id'])) {
    header('Location: login.php');
    exit();
}

$instructor_id = $_SESSION['instructor_id'];

// Get instructor's courses
$courses_sql = "SELECT id FROM courses WHERE instructor_id = :instructor_id";
$courses_stmt = $conn->prepare($courses_sql);
$courses_stmt->execute(['instructor_id' => $instructor_id]);
$instructor_courses = $courses_stmt->fetchAll(PDO::FETCH_COLUMN);

// Get schedules for instructor's courses
$schedules = [];
if (!empty($instructor_courses)) {
    $placeholders = str_repeat('?,', count($instructor_courses) - 1) . '?';
    $schedules_sql = "SELECT cs.*, c.name as course_name 
                      FROM class_schedules cs
                      JOIN courses c ON cs.course_id = c.id
                      WHERE cs.course_id IN ($placeholders) AND cs.class_date >= CURDATE()
                      ORDER BY cs.class_date ASC, cs.start_time ASC";
    $schedules_stmt = $conn->prepare($schedules_sql);
    $schedules_stmt->execute($instructor_courses);
    $schedules = $schedules_stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teaching Schedule | Instructor Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="instructor-panel">
    <?php include 'header.php'; ?>
    
    <div class="container">
        <div class="schedule-container">
            <div class="schedule-header">
                <h1 class="page-title">My Teaching Schedule</h1>
                <p>View your upcoming classes and teaching schedule</p>
            </div>
            
            <?php if (empty($instructor_courses)): ?>
                <div class="no-schedules">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Assigned Courses</h3>
                    <p>You are not assigned to teach any courses yet.</p>
                </div>
            <?php elseif (empty($schedules)): ?>
                <div class="no-schedules">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Upcoming Classes</h3>
                    <p>There are no scheduled classes for your courses.</p>
                </div>
            <?php else: ?>
                <div class="schedule-filters">
                    <div class="filter-group">
                        <label for="course-filter">Filter by Course:</label>
                        <select id="course-filter" class="schedule-select">
                            <option value="all">All Courses</option>
                            <?php
                            $unique_courses = [];
                            foreach ($schedules as $schedule) {
                                if (!in_array($schedule['course_name'], $unique_courses)) {
                                    $unique_courses[] = $schedule['course_name'];
                                    echo '<option value="' . htmlspecialchars($schedule['course_name']) . '">' . $schedule['course_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="date-filter">Filter by Date:</label>
                        <select id="date-filter" class="schedule-select">
                            <option value="all">All Dates</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>
                </div>
                
                <div class="schedule-grid" id="schedule-list">
                    <?php foreach ($schedules as $schedule): 
                        $class_date = date('l, F j, Y', strtotime($schedule['class_date']));
                        $start_time = date('h:i A', strtotime($schedule['start_time']));
                        $end_time = date('h:i A', strtotime($schedule['end_time']));
                        $is_today = date('Y-m-d') == $schedule['class_date'];
                        $date_class = $is_today ? 'today' : '';
                    ?>
                    <div class="schedule-card" data-course="<?= $schedule['course_name'] ?>" data-date="<?= $schedule['class_date'] ?>">
                        <div class="schedule-header-row">
                            <div>
                                <h3 class="course-name"><?= $schedule['course_name'] ?></h3>
                            </div>
                            <div class="schedule-date <?= $date_class ?>">
                                <?= $class_date ?>
                            </div>
                        </div>
                        
                        <div class="schedule-details">
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="detail-text">
                                    <div class="detail-label">Time</div>
                                    <div class="detail-value"><?= $start_time ?> - <?= $end_time ?></div>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="detail-text">
                                    <div class="detail-label">Location</div>
                                    <div class="detail-value"><?= $schedule['location'] ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($schedule['topic'])): ?>
                            <div class="schedule-topic">
                                <h4>Topic</h4>
                                <p><?= $schedule['topic'] ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="schedule-actions">
                            <button class="btn small">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn small secondary">
                                <i class="fas fa-users"></i> View Attendance
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Filter functionality
        document.getElementById('course-filter').addEventListener('change', filterSchedules);
        document.getElementById('date-filter').addEventListener('change', filterSchedules);
        
        function filterSchedules() {
            const courseFilter = document.getElementById('course-filter').value;
            const dateFilter = document.getElementById('date-filter').value;
            const scheduleCards = document.querySelectorAll('.schedule-card');
            const today = new Date().toISOString().split('T')[0];
            
            scheduleCards.forEach(card => {
                const course = card.dataset.course;
                const date = card.dataset.date;
                
                let courseMatch = courseFilter === 'all' || course === courseFilter;
                let dateMatch = true;
                
                if (dateFilter !== 'all') {
                    const classDate = new Date(date);
                    const now = new Date();
                    
                    if (dateFilter === 'today') {
                        dateMatch = date === today;
                    } else if (dateFilter === 'week') {
                        const startOfWeek = new Date(now);
                        startOfWeek.setDate(now.getDate() - now.getDay());
                        startOfWeek.setHours(0, 0, 0, 0);
                        
                        const endOfWeek = new Date(now);
                        endOfWeek.setDate(now.getDate() + (6 - now.getDay()));
                        endOfWeek.setHours(23, 59, 59, 999);
                        
                        dateMatch = classDate >= startOfWeek && classDate <= endOfWeek;
                    } else if (dateFilter === 'month') {
                        dateMatch = classDate.getMonth() === now.getMonth() && classDate.getFullYear() === now.getFullYear();
                    }
                }
                
                card.style.display = (courseMatch && dateMatch) ? 'block' : 'none';
            });
        }
    </script>
    
    <?php include 'footer.php'; ?>
</body>
</html>