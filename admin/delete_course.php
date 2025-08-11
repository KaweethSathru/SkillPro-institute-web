<?php
include 'header.php';

include '../components/connection.php';

// Get course ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id > 0) {
    // Fetch course to get image name
    $sql = "SELECT image FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $course = $stmt->fetch();
    
    // Delete course
    $sql = "DELETE FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$id])) {
        // Delete associated image
        if($course && $course['image'] && file_exists("../images/courses/{$course['image']}")) {
            unlink("../images/courses/{$course['image']}");
        }
        $_SESSION['success_message'] = "Course deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting course. Please try again.";
    }
} else {
    $_SESSION['error_message'] = "Invalid course ID.";
}

echo "<script>window.location.href='courses.php';</script>";
exit();
?>