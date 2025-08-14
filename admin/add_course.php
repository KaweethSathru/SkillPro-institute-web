<?php include 'header.php'; 

// Fetch all instructors with their specializations
$sql = "SELECT id, full_name, specialization FROM instructors";
$stmt = $conn->prepare($sql);
$stmt->execute();
$instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group instructors by specialization for JavaScript
$instructors_by_specialization = [];
foreach ($instructors as $instructor) {
    $specialization = $instructor['specialization'];
    if (!isset($instructors_by_specialization[$specialization])) {
        $instructors_by_specialization[$specialization] = [];
    }
    $instructors_by_specialization[$specialization][] = [
        'id' => $instructor['id'],
        'name' => $instructor['full_name']
    ];
}
?>

<?php
// Process form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $duration = filter_var($_POST['duration'], FILTER_SANITIZE_STRING);
    $fees = filter_var($_POST['fees'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $mode = filter_var($_POST['mode'], FILTER_SANITIZE_STRING);
    $branch = filter_var($_POST['branch'], FILTER_SANITIZE_STRING);
    $start_date = filter_var($_POST['start_date'], FILTER_SANITIZE_STRING);
    $instructor_id = filter_var($_POST['instructor_id'], FILTER_SANITIZE_NUMBER_INT);
    
    // Handle file upload
    $image = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "assets/images/courses/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Generate unique filename
        $filename = uniqid() . '.' . $imageFileType;
        $target_path = $target_dir . $filename;
        
        // Check if image file is actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // Move file to target directory
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_path)) {
                $image = $filename;
            }
        }
    }
    
    // Insert into database
    $sql = "INSERT INTO courses (name, category, description, duration, fees, mode, branch, image, start_date, instructor_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$name, $category, $description, $duration, $fees, $mode, $branch, $image, $start_date, $instructor_id])) {
        $_SESSION['success_message'] = "Course added successfully!";
        echo "<script>window.location.href='courses.php';</script>";
        exit();
    } else {
        $error = "Error adding course. Please try again.";
    }
}
?>

<div class="admin-content-header">
    <h1>Add New Course</h1>
    <a href="courses.php" class="btn"><i class="fas fa-arrow-left"></i> Back to Courses</a>
</div>

<div class="admin-content-body">
    <?php if(isset($error)): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>
    
    <form action="" method="POST" enctype="multipart/form-data" class="course-form">
        <div class="form-row">
            <div class="form-group">
                <label for="name">Course Name *</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="category">Category *</label>
                <select id="category" name="category" class="form-control" required onchange="updateInstructors()">
                    <option value="">Select Category</option>
                    <option value="ICT">ICT</option>
                    <option value="Plumbing">Plumbing</option>
                    <option value="Welding">Welding</option>
                    <option value="Hotel Management">Hotel Management</option>
                    <option value="Electrical">Electrical</option>
                    <option value="Automotive">Automotive</option>
                    <option value="Culinary Arts">Culinary Arts</option>
                </select>
            </div>

            <div class="form-group">
                <label for="instructor_id">Instructor *</label>
                <select id="instructor_id" name="instructor_id" class="form-control" required disabled>
                    <option value="">Select a category first</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Course Description *</label>
            <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="duration">Duration *</label>
                <input type="text" id="duration" name="duration" class="form-control" required placeholder="e.g., 3 Months">
            </div>
            
            <div class="form-group">
                <label for="fees">Fees (LKR) *</label>
                <input type="number" id="fees" name="fees" class="form-control" step="0.01" min="0" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="mode">Course Mode *</label>
                <select id="mode" name="mode" class="form-control" required>
                    <option value="">Select Mode</option>
                    <option value="Online">Online</option>
                    <option value="On-site">On-site</option>
                    <option value="Hybrid">Hybrid</option>
                </select>
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
        
        <div class="form-row">
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="image">Course Image</label>
                <div class="file-upload">
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <div class="file-preview"></div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="reset" class="btn secondary">Reset</button>
            <button type="submit" class="btn">Add Course</button>
        </div>
    </form>
</div>

<script>
    // Image preview for file upload
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.querySelector('.file-preview');
        preview.innerHTML = '';
        
        if(this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '200px';
                preview.appendChild(img);
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Pass PHP data to JavaScript
const instructorsBySpecialization = <?= json_encode($instructors_by_specialization) ?>;

function updateInstructors() {
    const category = document.getElementById('category').value;
    const instructorSelect = document.getElementById('instructor_id');
    
    // Clear existing options
    instructorSelect.innerHTML = '';
    
    if (!category) {
        instructorSelect.disabled = true;
        instructorSelect.innerHTML = '<option value="">Select a category first</option>';
        return;
    }
    
    // Check if we have instructors for this specialization
    if (instructorsBySpecialization[category] && instructorsBySpecialization[category].length > 0) {
        instructorSelect.disabled = false;
        
        // Add default option
        instructorSelect.innerHTML = '<option value="">Select Instructor</option>';
        
        // Add instructors
        instructorsBySpecialization[category].forEach(instructor => {
            const option = document.createElement('option');
            option.value = instructor.id;
            option.textContent = instructor.name;
            instructorSelect.appendChild(option);
        });
    } else {
        instructorSelect.disabled = true;
        instructorSelect.innerHTML = `<option value="">No ${category} instructors available</option>`;
    }
}

// Initialize instructors dropdown on page load
document.addEventListener('DOMContentLoaded', function() {
    // Trigger category change if it has a value
    const categorySelect = document.getElementById('category');
    if (categorySelect.value) {
        updateInstructors();
    }
});
</script>

<?php include 'footer.php'; ?>