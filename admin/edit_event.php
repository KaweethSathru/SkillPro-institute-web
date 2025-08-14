<?php include 'header.php'; ?>

<?php
// Get event ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch event details
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$event) {
    $_SESSION['error_message'] = "Event not found!";
    echo "<script>window.location.href='events.php';</script>";
    exit();
}

// Process form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $event_date = filter_var($_POST['event_date'], FILTER_SANITIZE_STRING);
    $start_time = filter_var($_POST['start_time'], FILTER_SANITIZE_STRING);
    $end_time = filter_var($_POST['end_time'], FILTER_SANITIZE_STRING);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    $branch = filter_var($_POST['branch'], FILTER_SANITIZE_STRING);
    $event_type = filter_var($_POST['event_type'], FILTER_SANITIZE_STRING);
    $poster = $event['poster']; // Keep existing poster by default
    
    // Handle file upload
    if(isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $target_dir = "assets/images/events/";
        $target_file = $target_dir . basename($_FILES["poster"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Generate unique filename
        $filename = uniqid() . '.' . $imageFileType;
        $target_path = $target_dir . $filename;
        
        // Check if image file is actual image
        $check = getimagesize($_FILES["poster"]["tmp_name"]);
        if($check !== false) {
            // Move file to target directory
            if(move_uploaded_file($_FILES["poster"]["tmp_name"], $target_path)) {
                // Delete old poster if exists
                if($poster && file_exists("assets/images/events/$poster")) {
                    unlink("assets/images/events/$poster");
                }
                $poster = $filename;
            }
        }
    }
    
    // Update database
    $sql = "UPDATE events 
            SET title = ?, description = ?, event_date = ?, start_time = ?, 
                end_time = ?, location = ?, branch = ?, event_type = ?, poster = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$title, $description, $event_date, $start_time, $end_time, $location, $branch, $event_type, $poster, $id])) {
        $_SESSION['success_message'] = "Event updated successfully!";
        echo "<script>window.location.href='events.php';</script>";
        exit();
    } else {
        $error = "Error updating event. Please try again.";
    }
}
?>

<div class="admin-content-header">
    <h1>Edit Event: <?= $event['title'] ?></h1>
    <a href="events.php" class="btn"><i class="fas fa-arrow-left"></i> Back to Events</a>
</div>

<div class="admin-content-body">
    <?php if(isset($error)): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>
    
    <form action="" method="POST" enctype="multipart/form-data" class="event-form">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Event Title *</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= $event['title'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="event_type">Event Type *</label>
                <select id="event_type" name="event_type" class="form-control" required>
                    <option value="Workshop" <?= $event['event_type'] == 'Workshop' ? 'selected' : '' ?>>Workshop</option>
                    <option value="Seminar" <?= $event['event_type'] == 'Seminar' ? 'selected' : '' ?>>Seminar</option>
                    <option value="Job Fair" <?= $event['event_type'] == 'Job Fair' ? 'selected' : '' ?>>Job Fair</option>
                    <option value="Batch Start" <?= $event['event_type'] == 'Batch Start' ? 'selected' : '' ?>>Batch Start</option>
                    <option value="Holiday" <?= $event['event_type'] == 'Holiday' ? 'selected' : '' ?>>Holiday</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Event Description *</label>
            <textarea id="description" name="description" class="form-control" rows="5" required><?= $event['description'] ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="event_date">Event Date *</label>
                <input type="date" id="event_date" name="event_date" class="form-control" value="<?= $event['event_date'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="start_time">Start Time *</label>
                <input type="time" id="start_time" name="start_time" class="form-control" value="<?= $event['start_time'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="end_time">End Time *</label>
                <input type="time" id="end_time" name="end_time" class="form-control" value="<?= $event['end_time'] ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="location">Location *</label>
                <input type="text" id="location" name="location" class="form-control" value="<?= $event['location'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="branch">Branch *</label>
                <select id="branch" name="branch" class="form-control" required>
                    <option value="Colombo" <?= $event['branch'] == 'Colombo' ? 'selected' : '' ?>>Colombo</option>
                    <option value="Kandy" <?= $event['branch'] == 'Kandy' ? 'selected' : '' ?>>Kandy</option>
                    <option value="Matara" <?= $event['branch'] == 'Matara' ? 'selected' : '' ?>>Matara</option>
                    <option value="All" <?= $event['branch'] == 'All' ? 'selected' : '' ?>>All Branches</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="poster">Event Poster</label>
            <div class="file-upload">
                <input type="file" id="poster" name="poster" class="form-control" accept="image/*">
                <div class="file-preview">
                    <?php if($event['poster']): ?>
                        <img src="assets/images/events/<?= $event['poster'] ?>" alt="<?= $event['title'] ?>" style="max-width: 100%;">
                    <?php else: ?>
                        <div class="preview-instruction">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload or drag and drop</p>
                            <p>Recommended size: 1200x800px (Max 2MB)</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="reset" class="btn secondary">Reset</button>
            <button type="submit" class="btn">Update Event</button>
        </div>
    </form>
</div>

<script>
    // Same JavaScript as add_event.php for file upload preview
</script>

<?php include 'footer.php'; ?>