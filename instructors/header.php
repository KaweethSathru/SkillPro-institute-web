<!-- instructor/header.php -->
<?php
session_start();
if(!isset($_SESSION['instructor_id'])) {
    header('Location: login.php');
    exit();
}

include '../components/connection.php';

// Fetch instructor details
$sql = "SELECT * FROM instructors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['instructor_id']]);
$instructor = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$instructor) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
</head>
<body>
    <div class="instructor-dashboard">
        <!-- Sidebar -->
        <div class="instructor-sidebar">
            <div class="sidebar-header">
                <h2>Instructor Portal</h2>
            </div>
            
            <div class="sidebar-profile">
                <img src="../admin/assets/images/instructors/<?= $instructor['profile_image'] ?: 'default-instructor.jpg' ?>" alt="<?= $instructor['full_name'] ?>">
                <h3><?= $instructor['full_name'] ?></h3>
                <p><?= $instructor['specialization'] ?></p>
            </div>
            
            <div class="sidebar-menu">
                <ul>
                    <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="courses.php"><i class="fas fa-book"></i> My Courses</a></li>
                    <li><a href="schedule.php"><i class="fas fa-calendar-alt"></i> Schedule</a></li>
                    <li><a href="students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
                    <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="instructor-main">
            <!-- Topbar -->
            <div class="instructor-topbar">
                <div>
                    <span class="toggle-sidebar"><i class="fas fa-bars"></i></span>
                </div>
                <div class="user-info">
                    <span><?= $instructor['full_name'] ?></span>
                    <img src="../admin/assets/images/instructors/<?= $instructor['profile_image'] ?: 'default-instructor.jpg' ?>" alt="<?= $instructor['full_name'] ?>">
                </div>
            </div>
            
            <!-- Content Area -->
            <div class="instructor-content"></div>