<?php
include '../components/connection.php';
?>

<?php include 'header.php'; ?>

<?php
// Get instructor ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch instructor details
$sql = "SELECT * FROM instructors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$instructor = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$instructor) {
    $_SESSION['error_message'] = "Instructor not found!";
    header("Location: instructors.php");
    exit();
}

// Process form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $qualifications = filter_var($_POST['qualifications'], FILTER_SANITIZE_STRING);
    $specialization = filter_var($_POST['specialization'], FILTER_SANITIZE_STRING);
    $experience = filter_var($_POST['experience'], FILTER_SANITIZE_STRING);
    $bio = filter_var($_POST['bio'], FILTER_SANITIZE_STRING);
    $branch = filter_var($_POST['branch'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    $profile_image = $instructor['profile_image']; // Keep existing image by default
    
    // Handle file upload
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "assets/images/instructors/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Generate unique filename
        $filename = uniqid() . '.' . $imageFileType;
        $target_path = $target_dir . $filename;
        
        // Check if image file is actual image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if($check !== false) {
            // Move file to target directory
            if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_path)) {
                // Delete old image if exists
                if($profile_image && file_exists("assets/images/instructors/$profile_image")) {
                    unlink("assets/images/instructors/$profile_image");
                }
                $profile_image = $filename;
            }
        }
    }
    
    // Update database
    $sql = "UPDATE instructors 
            SET full_name = ?, username = ?, email = ?, phone = ?, qualifications = ?, 
                specialization = ?, bio = ?, experience = ?, branch = ?, status = ?, 
                profile_image = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$full_name, $username, $email, $phone, $qualifications, 
                      $specialization, $bio, $experience, $branch, $status, 
                      $profile_image, $id])) {
        $_SESSION['success_message'] = "Instructor updated successfully!";
        echo "<script>window.location.href='instructors.php';</script>";
        exit();
    } else {
        $error = "Error updating instructor. Please try again.";
    }
}
?>

<div class="admin-content-header">
    <h1>Edit Instructor: <?= $instructor['full_name'] ?></h1>
    <a href="instructors.php" class="btn"><i class="fas fa-arrow-left"></i> Back to Instructors</a>
</div>

<div class="admin-content-body">
    <?php if(isset($error)): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>
    
    <form action="" method="POST" enctype="multipart/form-data" class="instructor-form">
        <div class="form-row">
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?= $instructor['full_name'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" class="form-control" value="<?= $instructor['username'] ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= $instructor['email'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone *</label>
                <input type="text" id="phone" name="phone" class="form-control" value="<?= $instructor['phone'] ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="qualifications">Qualifications *</label>
                <textarea id="qualifications" name="qualifications" class="form-control" rows="3" required><?= $instructor['qualifications'] ?></textarea>
                <small class="form-text">Separate qualifications with commas</small>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="specialization">Specialization *</label>
                <select id="specialization" name="specialization" class="form-control" required>
                    <option value="ICT" <?= $instructor['specialization'] == 'ICT' ? 'selected' : '' ?>>ICT</option>
                    <option value="Plumbing" <?= $instructor['specialization'] == 'Plumbing' ? 'selected' : '' ?>>Plumbing</option>
                    <option value="Welding" <?= $instructor['specialization'] == 'Welding' ? 'selected' : '' ?>>Welding</option>
                    <option value="Hotel Management" <?= $instructor['specialization'] == 'Hotel Management' ? 'selected' : '' ?>>Hotel Management</option>
                    <option value="Electrical" <?= $instructor['specialization'] == 'Electrical' ? 'selected' : '' ?>>Electrical</option>
                    <option value="Automotive" <?= $instructor['specialization'] == 'Automotive' ? 'selected' : '' ?>>Automotive</option>
                    <option value="Culinary Arts" <?= $instructor['specialization'] == 'Culinary Arts' ? 'selected' : '' ?>>Culinary Arts</option>
                    <option value="Other" <?= $instructor['specialization'] == 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="experience">Years of Experience *</label>
                <select id="experience" name="experience" class="form-control" required>
                    <option value="1-3 years" <?= $instructor['experience'] == '1-3 years' ? 'selected' : '' ?>>1-3 years</option>
                    <option value="3-5 years" <?= $instructor['experience'] == '3-5 years' ? 'selected' : '' ?>>3-5 years</option>
                    <option value="5-10 years" <?= $instructor['experience'] == '5-10 years' ? 'selected' : '' ?>>5-10 years</option>
                    <option value="10+ years" <?= $instructor['experience'] == '10+ years' ? 'selected' : '' ?>>10+ years</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="branch">Branch *</label>
                <select id="branch" name="branch" class="form-control" required>
                    <option value="Colombo" <?= $instructor['branch'] == 'Colombo' ? 'selected' : '' ?>>Colombo</option>
                    <option value="Kandy" <?= $instructor['branch'] == 'Kandy' ? 'selected' : '' ?>>Kandy</option>
                    <option value="Matara" <?= $instructor['branch'] == 'Matara' ? 'selected' : '' ?>>Matara</option>
                    <option value="All" <?= $instructor['branch'] == 'All' ? 'selected' : '' ?>>All Branches</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="Active" <?= $instructor['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= $instructor['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" class="form-control" rows="4"><?= $instructor['bio'] ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="profile_image">Profile Image</label>
                <div class="file-upload">
                    <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*">
                    <div class="file-preview">
                        <?php if($instructor['profile_image']): ?>
                            <img src="assets/images/instructors/<?= $instructor['profile_image'] ?>" alt="<?= $instructor['full_name'] ?>" style="max-width: 200px;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="reset" class="btn secondary">Reset</button>
            <button type="submit" class="btn">Update Instructor</button>
        </div>
    </form>
</div>

<script>
    // Image preview for file upload
    document.getElementById('profile_image').addEventListener('change', function(e) {
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
</script>

<?php include 'footer.php'; ?>