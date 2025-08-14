<?php
include 'components/connection.php';
session_start();

// Get student details
$student_id = $_SESSION['user_id'];
$sql = "SELECT * FROM students WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header("Location: login.php");
    exit();
}

// Get enrolled courses
$enrollment_sql = "SELECT courses.name, courses.category, enrollments.enrolled_at 
                   FROM enrollments 
                   JOIN courses ON enrollments.course_id = courses.id 
                   WHERE enrollments.student_id = :student_id";
$enrollment_stmt = $conn->prepare($enrollment_sql);
$enrollment_stmt->execute(['student_id' => $student_id]);
$enrollments = $enrollment_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body class="student-panel">
    <?php include 'components/header.php'; ?>
    
    <div class="profile-container">
        <!-- Profile Sidebar -->
        <div class="profile-sidebar">
            <div class="profile-pic">
                <img src="assets/images/student-avatar.jpg" alt="Student Avatar">
                <div class="edit-icon">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
            
            <div class="profile-header">
                <h1><?= $student['full_name'] ?></h1>
                <p>Student at SkillPro Institute</p>
            </div>
            
            <div class="info-card">
                <h3>Student ID</h3>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="info-text">
                        <p>SP-<?= str_pad($student['id'], 5, '0', STR_PAD_LEFT) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="profile-stats">
                <div class="stat-card">
                    <div class="number"><?= count($enrollments) ?></div>
                    <div class="label">Courses</div>
                </div>
                <div class="stat-card">
                    <div class="number">12</div>
                    <div class="label">Certificates</div>
                </div>
            </div>
            
            <a href="courses.php" class="btn" style="width: 100%; margin-top: 20px;">
                <i class="fas fa-book"></i> Browse Courses
            </a>
        </div>
        
        <!-- Main Content -->
        <div class="profile-content">
            <div class="info-card">
                <h3>Personal Information</h3>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="info-text">
                        <h4>Full Name</h4>
                        <p><?= $student['full_name'] ?></p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-at"></i>
                    </div>
                    <div class="info-text">
                        <h4>Email Address</h4>
                        <p><?= $student['email'] ?></p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-text">
                        <h4>Phone Number</h4>
                        <p><?= $student['phone'] ?: 'Not provided' ?></p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div class="info-text">
                        <h4>Username</h4>
                        <p><?= $student['username'] ?></p>
                    </div>
                </div>
            </div>
            
            <div class="info-card">
                <h3>Enrolled Courses</h3>
                
                <?php if(count($enrollments) > 0): ?>
                    <ul class="courses-list">
                        <?php foreach($enrollments as $enrollment): 
                            $enrollment_date = date('M d, Y', strtotime($enrollment['enrolled_at']));
                        ?>
                        <li class="course-item">
                            <div class="course-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="course-info">
                                <h4><?= $enrollment['name'] ?></h4>
                                <div class="course-meta">
                                    <span><?= $enrollment['category'] ?></span>
                                </div>
                            </div>
                            <div class="enrollment-date">
                                Enrolled: <?= $enrollment_date ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="no-enrollments">
                        <i class="fas fa-book-open"></i>
                        <h3>No Enrollments Yet</h3>
                        <p>You haven't enrolled in any courses yet.</p>
                        <a href="courses.php" class="btn secondary" style="margin-top: 15px;">Browse Courses</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="info-card">
                <h3>Account Actions</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">
                    <a href="#" class="btn secondary">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                    <a href="courses.php" class="btn secondary">
                        <i class="fas fa-book"></i> My Courses
                    </a>
                    <a href="#" class="btn secondary">
                        <i class="fas fa-certificate"></i> Certificates
                    </a>
                    <a href="contact.php" class="btn secondary">
                        <i class="fas fa-headset"></i> Support
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'components/footer.php'; ?>
</body>
</html>