<?php include 'header.php'; ?>

<?php
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
    
    // Handle file upload
    $poster = '';
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
                $poster = $filename;
            }
        }
    }
    
    // Insert into database
    $sql = "INSERT INTO events (title, description, event_date, start_time, end_time, location, branch, event_type, poster) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$title, $description, $event_date, $start_time, $end_time, $location, $branch, $event_type, $poster])) {
        $_SESSION['success_message'] = "Event added successfully!";
        echo "<script>window.location.href='events.php';</script>";
        exit();
    } else {
        $error = "Error adding event. Please try again.";
    }
}
?>

<div class="admin-content-header">
    <h1>Add New Event</h1>
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
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="event_type">Event Type *</label>
                <select id="event_type" name="event_type" class="form-control" required>
                    <option value="">Select Type</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Job Fair">Job Fair</option>
                    <option value="Batch Start">Batch Start</option>
                    <option value="Holiday">Holiday</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Event Description *</label>
            <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="event_date">Event Date *</label>
                <input type="date" id="event_date" name="event_date" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="start_time">Start Time *</label>
                <input type="time" id="start_time" name="start_time" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="end_time">End Time *</label>
                <input type="time" id="end_time" name="end_time" class="form-control" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="location">Location *</label>
                <input type="text" id="location" name="location" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="branch">Branch *</label>
                <select id="branch" name="branch" class="form-control" required>
                    <option value="">Select Branch</option>
                    <option value="Colombo">Colombo</option>
                    <option value="Kandy">Kandy</option>
                    <option value="Matara">Matara</option>
                    <option value="All">All Branches</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="poster">Event Poster</label>
            <div class="file-upload">
                <input type="file" id="poster" name="poster" class="form-control" accept="image/*">
                <div class="file-preview">
                    <div class="preview-instruction">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to upload or drag and drop</p>
                        <p>Recommended size: 1200x800px (Max 2MB)</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="reset" class="btn secondary">Reset</button>
            <button type="submit" class="btn">Add Event</button>
        </div>
    </form>
</div>

<script>
    // File upload preview
    document.getElementById('poster').addEventListener('change', function(e) {
        const preview = document.querySelector('.file-preview');
        
        if(this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.innerHTML = '';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                preview.appendChild(img);
            }
            
            reader.readAsDataURL(this.files[0]);
        } else {
            preview.innerHTML = `
                <div class="preview-instruction">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload or drag and drop</p>
                    <p>Recommended size: 1200x800px (Max 2MB)</p>
                </div>
            `;
        }
    });
    
    // Drag and drop functionality
    const dropArea = document.querySelector('.file-preview');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropArea.classList.add('highlight');
    }
    
    function unhighlight() {
        dropArea.classList.remove('highlight');
    }
    
    dropArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('poster').files = files;
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        document.getElementById('poster').dispatchEvent(event);
    }
</script>

<?php include 'footer.php'; ?>