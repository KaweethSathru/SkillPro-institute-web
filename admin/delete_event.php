<?php
include 'header.php';

// Get event ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id > 0) {
    // Fetch event to get poster name
    $sql = "SELECT poster FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $event = $stmt->fetch();
    
    // Delete event
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$id])) {
        // Delete associated poster if exists
        if($event && $event['poster'] && file_exists("assets/images/events/{$event['poster']}")) {
            unlink("assets/images/events/{$event['poster']}");
        }
        $_SESSION['success_message'] = "Event deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting event. Please try again.";
    }
} else {
    $_SESSION['error_message'] = "Invalid event ID.";
}

echo "<script>window.location.href='events.php';</script>";
exit();
?>