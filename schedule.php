<?php
include 'components/connection.php';
session_start();

// Check if student is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['user_id'];

// Get student's enrolled courses
$enrolled_courses_sql = "SELECT course_id FROM enrollments WHERE student_id = :student_id";
$enrolled_courses_stmt = $conn->prepare($enrolled_courses_sql);
$enrolled_courses_stmt->execute(['student_id' => $student_id]);
$enrolled_courses = $enrolled_courses_stmt->fetchAll(PDO::FETCH_COLUMN);

// Get schedules for enrolled courses
$schedules = [];
if (!empty($enrolled_courses)) {
    $placeholders = str_repeat('?,', count($enrolled_courses) - 1) . '?';
    $schedules_sql = "SELECT cs.*, c.name as course_name, i.full_name as instructor_name 
                      FROM class_schedules cs
                      JOIN courses c ON cs.course_id = c.id
                      JOIN instructors i ON cs.instructor_id = i.id
                      WHERE cs.course_id IN ($placeholders) AND cs.class_date >= CURDATE()
                      ORDER BY cs.class_date ASC, cs.start_time ASC";
    $schedules_stmt = $conn->prepare($schedules_sql);
    $schedules_stmt->execute($enrolled_courses);
    $schedules = $schedules_stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule | Student Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/schedule.css">
</head>
<body class="student-panel">
    <?php include 'components/header.php'; ?>
    
    <div class="container">
        <div class="schedule-container">
            <div class="schedule-header">
                <h1 class="page-title">My Class Schedule</h1>
                <p>View your upcoming classes and course schedules</p>
            </div>
            
            <?php if (empty($enrolled_courses)): ?>
                <div class="no-schedules">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Enrolled Courses</h3>
                    <p>You are not enrolled in any courses yet. Visit the courses page to enroll.</p>
                    <a href="courses.php" class="btn" style="margin-top: 20px;">Browse Courses</a>
                </div>
            <?php elseif (empty($schedules)): ?>
                <div class="no-schedules">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Upcoming Classes</h3>
                    <p>There are no scheduled classes for your enrolled courses.</p>
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
                                <div class="instructor-name">Instructor: <?= $schedule['instructor_name'] ?></div>
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
    
    <?php include 'components/footer.php'; ?>
</body>
</html>