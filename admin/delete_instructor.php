<?php
include '../components/connection.php';
?>

<?php
include 'header.php';

// Get instructor ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id > 0) {
    // Fetch instructor to get image name
    $sql = "SELECT profile_image FROM instructors WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $instructor = $stmt->fetch();
    
    // Delete instructor
    $sql = "DELETE FROM instructors WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$id])) {
        // Delete associated image
        if($instructor && $instructor['profile_image'] && file_exists("assets/images/instructors/{$instructor['profile_image']}")) {
            unlink("assets/images/instructors/{$instructor['profile_image']}");
        }
        $_SESSION['success_message'] = "Instructor deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting instructor. Please try again.";
    }
} else {
    $_SESSION['error_message'] = "Invalid instructor ID.";
}

echo "<script>window.location.href='instructors.php';</script>";
exit();
?>