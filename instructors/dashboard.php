<?php include 'header.php'; ?>

<?php
include '../components/connection.php';

// Get the logged-in instructor ID
$instructor_id = $_SESSION['instructor_id'];

// Get instructor details
$instructor_sql = "SELECT * FROM instructors WHERE id = :id";
$instructor_stmt = $conn->prepare($instructor_sql);
$instructor_stmt->execute(['id' => $instructor_id]);
$instructor = $instructor_stmt->fetch(PDO::FETCH_ASSOC);

// Get courses taught by this instructor
$courses_sql = "SELECT * FROM courses WHERE instructor_id = :instructor_id";
$courses_stmt = $conn->prepare($courses_sql);
$courses_stmt->execute(['instructor_id' => $instructor_id]);
$courses = $courses_stmt->fetchAll();

// Get student count for instructor's courses
$student_count = 0;
if (count($courses) > 0) {
    $course_ids = array_column($courses, 'id');
    $placeholders = implode(',', array_fill(0, count($course_ids), '?'));
    $students_sql = "SELECT COUNT(DISTINCT student_id) FROM enrollments WHERE course_id IN ($placeholders)";
    $students_stmt = $conn->prepare($students_sql);
    $students_stmt->execute($course_ids);
    $student_count = $students_stmt->fetchColumn();
}

// Get students for the instructor
$students_sql = "SELECT 
            students.id, 
            students.full_name, 
            students.email, 
            courses.name AS course_name
        FROM enrollments
        JOIN students ON enrollments.student_id = students.id
        JOIN courses ON enrollments.course_id = courses.id
        WHERE courses.instructor_id = :instructor_id
        GROUP BY students.id
        ORDER BY students.full_name ASC
        LIMIT 5";
$students_stmt = $conn->prepare($students_sql);
$students_stmt->execute(['instructor_id' => $instructor_id]);
$my_students = $students_stmt->fetchAll();

// Get upcoming events for these courses
$events_sql = "SELECT * FROM events 
               WHERE branch IN (SELECT branch FROM courses WHERE instructor_id = :instructor_id)
               AND event_date >= CURDATE() 
               ORDER BY event_date ASC 
               LIMIT 4";
$events_stmt = $conn->prepare($events_sql);
$events_stmt->execute(['instructor_id' => $instructor_id]);
$upcoming_events = $events_stmt->fetchAll();
?>

<div class="instructor-dashboard">
    <div class="welcome-banner">
        <h1>Welcome back, <?= $instructor['full_name'] ?>!</h1>
        <p>Here's what's happening with your courses today</p>
    </div>
    
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(12, 75, 101, 0.1); color: var(--primary);">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-number"><?= count($courses) ?></div>
            <div class="stat-title">Courses Teaching</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(243, 156, 18, 0.1); color: var(--secondary);">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-number"><?= $student_count ?></div>
            <div class="stat-title">Students</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(39, 174, 96, 0.1); color: #27ae60;">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-number"><?= count($upcoming_events) ?></div>
            <div class="stat-title">Upcoming Events</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">98%</div>
            <div class="stat-title">Attendance Rate</div>
        </div>
    </div>
    
    <div class="dashboard-sections">
        <div class="dashboard-section">
            <div class="section-header">
                <h2>My Courses</h2>
                <a href="courses.php" class="btn small">View All</a>
            </div>
            <div class="section-content">
                <?php if (count($courses) > 0): ?>
                    <ul class="recent-list">
                        <?php foreach ($courses as $course): 
                            $start_date = date('M d, Y', strtotime($course['start_date']));
                        ?>
                        <li class="recent-item">
                            <div class="recent-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="recent-info">
                                <h4><?= $course['name'] ?></h4>
                                <div class="recent-meta">
                                    <?= $course['category'] ?> | <?= $course['branch'] ?>
                                </div>
                                <div class="recent-meta">
                                    <small>Starts: <?= $start_date ?></small>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-book"></i>
                        <h3>No Courses Assigned</h3>
                        <p>You haven't been assigned to any courses yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="dashboard-section">
            <div class="section-header">
                <h2>My Students</h2>
                <a href="students.php" class="btn small">View All</a>
            </div>
            <div class="section-content">
                <?php if (count($my_students) > 0): ?>
                    <ul class="recent-list">
                        <?php foreach ($my_students as $student): ?>
                        <li class="recent-item">
                            <div class="recent-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="recent-info">
                                <h4><?= $student['full_name'] ?></h4>
                                <div class="recent-meta">
                                    <?= $student['email'] ?>
                                </div>
                                <div class="recent-meta">
                                    <small><?= $student['course_name'] ?></small>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-user-graduate"></i>
                        <h3>No Students Yet</h3>
                        <p>No students are enrolled in your courses</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Upcoming Events</h2>
            <a href="events.php" class="btn small">View Calendar</a>
        </div>
        <div class="section-content">
            <?php if (count($upcoming_events) > 0): ?>
                <ul class="recent-list">
                    <?php foreach ($upcoming_events as $event): 
                        $date = date('M d', strtotime($event['event_date']));
                        $time = date('h:i A', strtotime($event['start_time']));
                    ?>
                    <li class="recent-item">
                        <div class="event-date">
                            <?= $date ?>
                        </div>
                        <div class="recent-info">
                            <h4><?= $event['title'] ?></h4>
                            <div class="recent-meta">
                                <?= $time ?> | <?= $event['location'] ?>
                            </div>
                            <div class="recent-meta">
                                <small><?= $event['branch'] ?> Branch</small>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>No Upcoming Events</h3>
                    <p>No events scheduled in the near future</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="dashboard-section" style="margin-top: 30px;">
        <div class="section-header">
            <h2>Quick Actions</h2>
        </div>
        <div class="section-content">
            <div class="quick-actions">
                <a href="courses.php" class="action-card">
                    <i class="fas fa-book"></i>
                    <h3>My Courses</h3>
                </a>
                
                <a href="students.php" class="action-card">
                    <i class="fas fa-user-graduate"></i>
                    <h3>My Students</h3>
                </a>
                
                <a href="profile.php" class="action-card">
                    <i class="fas fa-user"></i>
                    <h3>My Profile</h3>
                </a>
                
                <a href="events.php" class="action-card">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Events</h3>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>