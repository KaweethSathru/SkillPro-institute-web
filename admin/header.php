<?php

include '../components/connection.php';

session_start();
if(!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js">
</head>
<body>
    <div class="admin-dashboard">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h2>SkillPro Admin</h2>
            </div>
            
            <div class="sidebar-menu">
                <ul>
                    <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="students.php"><i class="fas fa-user-graduate"></i> Students</a></li>
                    <li><a href="instructors.php"><i class="fas fa-chalkboard-teacher"></i> Instructors</a></li>
                    <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>
                    <li><a href="enrollments.php"><i class="fas fa-clipboard-list"></i> Enrollments</a></li>
                    <li><a href="certificates.php"><i class="fas fa-certificate"></i> Certificates</a></li>
                    <li><a href="events.php"><i class="fas fa-calendar-alt"></i> Events</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="admin-main">
            <!-- Topbar -->
            <div class="admin-topbar">
                <div>
                    <span class="toggle-sidebar"><i class="fas fa-bars"></i></span>
                </div>
                <div class="user-info">
                    <span>Hello, <?= $_SESSION['admin_name'] ?></span>
                    <img src="assets/images/logo.png" alt="Admin">
                </div>
            </div>
            
            <!-- Content Area -->
            <div class="admin-content"></div>