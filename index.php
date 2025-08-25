<?php
include 'components/connection.php';
session_start();

// Fetch featured courses
$course_sql = "SELECT * FROM courses ORDER BY created_at DESC LIMIT 4";
$course_stmt = $conn->prepare($course_sql);
$course_stmt->execute();
$featured_courses = $course_stmt->fetchAll();

// Fetch upcoming events
$event_sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3";
$event_stmt = $conn->prepare($event_sql);
$event_stmt->execute();
$upcoming_events = $event_stmt->fetchAll();

// Fetch featured instructors
$instructor_sql = "SELECT * FROM instructors WHERE status = 'Active' ORDER BY RAND() LIMIT 3";
$instructor_stmt = $conn->prepare($instructor_sql);
$instructor_stmt->execute();
$featured_instructors = $instructor_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body class="student-home">
    <?php include 'components/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="student-hero">
        <div class="container">
            <h1>Welcome to SkillPro Institute</h1>
            <p>Gain practical skills and advance your career with our industry-relevant vocational training programs</p>
            <div class="hero-btns">
                <a href="courses.php" class="btn">Browse Courses</a>
                <a href="events.php" class="btn secondary">View Events</a>
            </div>
        </div>
    </section>
    
    <!-- Quick Stats -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-number">24+</div>
            <div class="stat-title">Vocational Courses</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-number">48</div>
            <div class="stat-title">Expert Instructors</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-number">1250+</div>
            <div class="stat-title">Successful Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-number">18</div>
            <div class="stat-title">Upcoming Events</div>
        </div>
    </div>
    
    <!-- Featured Courses -->
    <section class="home-section">
        <div class="section-header">
            <h2>Featured Courses</h2>
            <p>Discover our most popular vocational training programs designed to equip you with practical skills</p>
        </div>
        
        <div class="courses-grid">
            <?php foreach($featured_courses as $course): ?>
            <div class="course-card">
                <div class="course-image">
                    <?php if($course['image']): ?>
                        <img src="admin/assets/images/courses/<?= $course['image'] ?: 'default-course.jpg' ?>" alt="<?= $course['name'] ?>">
                    <?php else: ?>
                        <div class="no-image" style="background:#f8f9fa;height:100%;display:flex;align-items:center;justify-content:center;color:var(--primary);">
                            <i class="fas fa-book fa-3x"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="course-info">
                    <span class="course-category"><?= $course['category'] ?></span>
                    <h3><?= $course['name'] ?></h3>
                    <div class="course-meta">
                        <span><i class="far fa-clock"></i> <?= $course['duration'] ?></span>
                        <span><i class="fas fa-money-bill-wave"></i> LKR <?= number_format($course['fees'], 2) ?></span>
                    </div>
                    <p><?= substr($course['description'], 0, 80) ?>...</p>
                    <div class="course-actions">
                        <a href="course-details.php?id=<?= $course['id'] ?>" class="btn secondary">Details</a>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="course-details.php?id=<?= $course['id'] ?>&register=1" class="btn">Enroll</a>
                        <?php else: ?>
                            <a href="login.php" class="btn">Login to Enroll</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="center-btn" style="margin-top: 40px;">
            <a href="courses.php" class="btn">View All Courses</a>
        </div>
    </section>
    
    <!-- Upcoming Events -->
    <section class="home-section events-container">
        <div class="section-header">
            <h2>Upcoming Events</h2>
            <p>Join our workshops, seminars, and career development events to enhance your skills</p>
        </div>
        
        <div class="events-grid">
            <?php foreach($upcoming_events as $event): 
                $date = date('d', strtotime($event['event_date']));
                $month = date('M', strtotime($event['event_date']));
                $time = date('h:i A', strtotime($event['start_time'])) . ' - ' . date('h:i A', strtotime($event['end_time']));
            ?>
            <div class="event-card">
                <div class="event-date">
                    <div class="event-day"><?= $date ?></div>
                    <div class="event-month"><?= $month ?></div>
                </div>
                <div class="event-info">
                    <span class="event-type <?= strtolower(str_replace(' ', '-', $event['event_type'])) ?>">
                        <?= $event['event_type'] ?>
                    </span>
                    <h3><?= $event['title'] ?></h3>
                    <div class="event-meta">
                        <div><i class="far fa-clock"></i> <?= $time ?></div>
                        <div><i class="fas fa-map-marker-alt"></i> <?= $event['location'] ?></div>
                        <div><i class="fas fa-building"></i> <?= $event['branch'] ?></div>
                    </div>
                    <p><?= substr($event['description'], 0, 80) ?>...</p>
                    <a href="events.php" class="btn-text">View Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="center-btn" style="margin-top: 40px;">
            <a href="events.php" class="btn">View All Events</a>
        </div>
    </section>
    
    <!-- Featured Instructors -->
    <section class="home-section">
        <div class="section-header">
            <h2>Meet Our Instructors</h2>
            <p>Learn from industry experts with years of practical experience in their fields</p>
        </div>
        
        <div class="instructors-grid">
            <?php foreach($featured_instructors as $instructor): 
                $qualifications = explode(',', $instructor['qualifications']);
            ?>
            <div class="instructor-card">
                <div class="instructor-image">
                    <img src="admin/assets/images/instructors/<?= $instructor['profile_image'] ?: 'default-instructor.jpg' ?>" alt="<?= $instructor['full_name'] ?>">
                    <div class="instructor-specialization"><?= $instructor['specialization'] ?></div>
                </div>
                <div class="instructor-info">
                    <h3 class="instructor-name"><?= $instructor['full_name'] ?></h3>
                    <div class="instructor-qualifications">
                        <?php foreach($qualifications as $qualification): ?>
                            <span class="qualification"><?= trim($qualification) ?></span><br>
                        <?php endforeach; ?>
                    </div>
                    <div class="instructor-experience">
                        <i class="fas fa-briefcase"></i>
                        <?= $instructor['experience'] ?> Experience
                    </div>
                    <a href="instructors.php" class="btn secondary">View Profile</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="center-btn" style="margin-top: 40px;">
            <a href="instructors.php" class="btn">View All Instructors</a>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="student-cta">
        <div class="container">
            <h2>Ready to Transform Your Career?</h2>
            <p>Join thousands of students who have gained practical skills and advanced their careers with SkillPro Institute</p>
            <div class="cta-buttons">
                <a href="components/register.php" class="btn">Create Account</a>
                <a href="contact.php" class="btn secondary">Contact Us</a>
            </div>
        </div>
    </section>
    
    <?php include 'components/footer.php'; ?>
</body>
</html>