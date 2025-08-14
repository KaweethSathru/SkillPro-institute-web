<?php include 'header.php'; ?>

<?php
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

// Get course ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch course details
$sql = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$course) {
    $_SESSION['error_message'] = "Course not found!";
    header("Location: courses.php");
    exit();
}

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
    $image = $course['image']; // Keep existing image by default
    
    // Handle file upload
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
                // Delete old image if exists
                if($image && file_exists("assets/images/courses/$image")) {
                    unlink("assets/images/courses/$image");
                }
                $image = $filename;
            }
        }
    }
    
    // Update database
    $sql = "UPDATE courses 
            SET name = ?, category = ?, description = ?, duration = ?, fees = ?, mode = ?, 
                branch = ?, image = ?, start_date = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$name, $category, $description, $duration, $fees, $mode, $branch, $image, $start_date, $id])) {
        $_SESSION['success_message'] = "Course updated successfully!";
        echo "<script>window.location.href='courses.php';</script>";
        exit();
    } else {
        $error = "Error updating course. Please try again.";
    }
}
?>

<div class="admin-content-header">
    <h1>Edit Course: <?= $course['name'] ?></h1>
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
                <input type="text" id="name" name="name" class="form-control" value="<?= $course['name'] ?>" required>
            </div>
            
            <div class="form-group">
        <label for="category">Category *</label>
        <select id="category" name="category" class="form-control" required onchange="updateInstructors()">
            <option value="ICT" <?= $course['category'] == 'ICT' ? 'selected' : '' ?>>ICT</option>
            <option value="Plumbing" <?= $course['category'] == 'Plumbing' ? 'selected' : '' ?>>Plumbing</option>
            <option value="Welding" <?= $course['category'] == 'Welding' ? 'selected' : '' ?>>Welding</option>
            <option value="Hotel Management" <?= $course['category'] == 'Hotel Management' ? 'selected' : '' ?>>Hotel Management</option>
            <option value="Electrical" <?= $course['category'] == 'Electrical' ? 'selected' : '' ?>>Electrical</option>
            <option value="Automotive" <?= $course['category'] == 'Automotive' ? 'selected' : '' ?>>Automotive</option>
            <option value="Culinary Arts" <?= $course['category'] == 'Culinary Arts' ? 'selected' : '' ?>>Culinary Arts</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="instructor_id">Instructor *</label>
        <select id="instructor_id" name="instructor_id" class="form-control" required>
            <option value="">Loading instructors...</option>
        </select>
    </div>
        </div>
        
        <div class="form-group">
            <label for="description">Course Description *</label>
            <textarea id="description" name="description" class="form-control" rows="5" required><?= $course['description'] ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="duration">Duration *</label>
                <input type="text" id="duration" name="duration" class="form-control" value="<?= $course['duration'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="fees">Fees (LKR) *</label>
                <input type="number" id="fees" name="fees" class="form-control" step="0.01" min="0" value="<?= $course['fees'] ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="mode">Course Mode *</label>
                <select id="mode" name="mode" class="form-control" required>
                    <option value="Online" <?= $course['mode'] == 'Online' ? 'selected' : '' ?>>Online</option>
                    <option value="On-site" <?= $course['mode'] == 'On-site' ? 'selected' : '' ?>>On-site</option>
                    <option value="Hybrid" <?= $course['mode'] == 'Hybrid' ? 'selected' : '' ?>>Hybrid</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="branch">Branch *</label>
                <select id="branch" name="branch" class="form-control" required>
                    <option value="Colombo" <?= $course['branch'] == 'Colombo' ? 'selected' : '' ?>>Colombo</option>
                    <option value="Kandy" <?= $course['branch'] == 'Kandy' ? 'selected' : '' ?>>Kandy</option>
                    <option value="Matara" <?= $course['branch'] == 'Matara' ? 'selected' : '' ?>>Matara</option>
                    <option value="All" <?= $course['branch'] == 'All' ? 'selected' : '' ?>>All Branches</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="<?= $course['start_date'] ?>">
            </div>
            
            <div class="form-group">
                <label for="image">Course Image</label>
                <div class="file-upload">
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <div class="file-preview">
                        <?php if($course['image']): ?>
                            <img src="assets/images/courses/<?= $course['image'] ?>" alt="<?= $course['name'] ?>" style="max-width: 200px;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="reset" class="btn secondary">Reset</button>
            <button type="submit" class="btn">Update Course</button>
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
const currentInstructorId = <?= $course['instructor_id'] ?: 'null' ?>;

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
        
        // Add instructors
        instructorsBySpecialization[category].forEach(instructor => {
            const option = document.createElement('option');
            option.value = instructor.id;
            option.textContent = instructor.name;
            option.selected = (instructor.id == currentInstructorId);
            instructorSelect.appendChild(option);
        });
    } else {
        instructorSelect.disabled = true;
        instructorSelect.innerHTML = `<option value="">No ${category} instructors available</option>`;
    }
}

// Initialize instructors dropdown on page load
document.addEventListener('DOMContentLoaded', function() {
    updateInstructors();
});
</script>

<?php include 'footer.php'; ?>