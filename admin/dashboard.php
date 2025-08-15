<?php include 'header.php'; ?>
<?php
include '../components/connection.php';

// Get real counts from the database
$student_count = $conn->query("SELECT COUNT(*) FROM students")->fetchColumn();
$instructor_count = $conn->query("SELECT COUNT(*) FROM instructors")->fetchColumn();
$course_count = $conn->query("SELECT COUNT(*) FROM courses")->fetchColumn();

// Get recent enrollments
$enrollment_sql = "SELECT students.full_name, courses.name, enrollments.branch 
                   FROM enrollments
                   JOIN students ON enrollments.student_id = students.id
                   JOIN courses ON enrollments.course_id = courses.id
                   ORDER BY enrollments.enrolled_at DESC
                   LIMIT 4";
$enrollment_stmt = $conn->prepare($enrollment_sql);
$enrollment_stmt->execute();
$recent_enrollments = $enrollment_stmt->fetchAll();

// Get upcoming events
$events_sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 4";
$events_stmt = $conn->prepare($events_sql);
$events_stmt->execute();
$upcoming_events = $events_stmt->fetchAll();
?>

<div class="dashboard-container">
    <div class="welcome-banner">
        <h1>Welcome back, Admin!</h1>
        <p>Here's what's happening at SkillPro Institute today</p>
    </div>
    
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(12, 75, 101, 0.1); color: var(--primary);">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-number"><?= $student_count ?></div>
            <div class="stat-title">Total Students</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(243, 156, 18, 0.1); color: var(--secondary);">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-number"><?= $instructor_count ?></div>
            <div class="stat-title">Instructors</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(39, 174, 96, 0.1); color: #27ae60;">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-number"><?= $course_count ?></div>
            <div class="stat-title">Courses</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-number">LKR 2.5M</div>
            <div class="stat-title">Monthly Revenue</div>
        </div>
    </div>
    
    <div class="dashboard-sections">
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Recent Enrollments</h2>
                <a href="enrollments.php" class="btn small">View All</a>
            </div>
            <div class="section-content">
                <?php if (count($recent_enrollments) > 0): ?>
                    <ul class="recent-list">
                        <?php foreach ($recent_enrollments as $enrollment): ?>
                        <li class="recent-item">
                            <div class="recent-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="recent-info">
                                <h4><?= $enrollment['full_name'] ?></h4>
                                <div class="recent-meta">
                                    <?= $enrollment['name'] ?> | <?= $enrollment['branch'] ?>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-user-graduate"></i>
                        <h3>No Recent Enrollments</h3>
                        <p>No students have enrolled recently</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Upcoming Events</h2>
                <a href="events.php" class="btn small">View All</a>
            </div>
            <div class="section-content">
                <?php if (count($upcoming_events) > 0): ?>
                    <ul class="recent-list">
                        <?php foreach ($upcoming_events as $event): 
                            $date = date('M d', strtotime($event['event_date']));
                        ?>
                        <li class="recent-item">
                            <div class="event-date">
                                <?= $date ?>
                            </div>
                            <div class="recent-info">
                                <h4><?= $event['title'] ?></h4>
                                <div class="recent-meta">
                                    <?= $event['location'] ?> | <?= $event['branch'] ?>
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
    </div>
    
    <div class="dashboard-section" style="margin-top: 30px;">
        <div class="section-header">
            <h2>Quick Actions</h2>
        </div>
        <div class="section-content">
            <div class="quick-actions">
                <a href="students.php" class="action-card">
                    <i class="fas fa-user-graduate"></i>
                    <h3>Manage Students</h3>
                </a>
                
                <a href="instructors.php" class="action-card">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>Manage Instructors</h3>
                </a>
                
                <a href="courses.php" class="action-card">
                    <i class="fas fa-book"></i>
                    <h3>Manage Courses</h3>
                </a>
                
                <a href="enrollments.php" class="action-card">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>View Enrollments</h3>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>