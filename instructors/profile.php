<?php 
include 'header.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $qualifications = filter_var($_POST['qualifications'], FILTER_SANITIZE_STRING);
    $bio = filter_var($_POST['bio'], FILTER_SANITIZE_STRING);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $profile_image = $instructor['profile_image'];
    
    $update_password = false;
    
    // Handle password change if requested
    if (!empty($current_password)) {
        if (password_verify($current_password, $instructor['password'])) {
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password = true;
            }
        } else {
            $error = "Current password is incorrect!";
        }
    }
    
    // Handle file upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "../admin/assets/images/instructors/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Generate unique filename
        $filename = uniqid() . '.' . $imageFileType;
        $target_path = $target_dir . $filename;
        
        // Check if image file is actual image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
            // Move file to target directory
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_path)) {
                // Delete old image if exists
                if ($profile_image && file_exists("../admin/assets/images/instructors/$profile_image")) {
                    unlink("../admin/assets/images/instructors/$profile_image");
                }
                $profile_image = $filename;
            }
        }
    }
    
    // Update database
    if (empty($error)) {
        $sql = "UPDATE instructors 
                SET full_name = ?, email = ?, phone = ?, qualifications = ?, 
                    bio = ?, profile_image = ?";
        
        $params = [$full_name, $email, $phone, $qualifications, $bio, $profile_image];
        
        if ($update_password) {
            $sql .= ", password = ?";
            $params[] = $hashed_password;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $_SESSION['instructor_id'];
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute($params)) {
            // Update session data
            $_SESSION['instructor_name'] = $full_name;
            $_SESSION['instructor_email'] = $email;
            
            $success = "Profile updated successfully!";
            
            // Refresh instructor data
            $sql = "SELECT * FROM instructors WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$_SESSION['instructor_id']]);
            $instructor = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = "Error updating profile. Please try again.";
        }
    }
}
?>

<h1 class="page-title">My Profile</h1>

<div class="profile-container">
    <?php if(isset($error)): ?>
        <div class="error-message"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if(isset($success)): ?>
        <div class="success-message"><?= $success ?></div>
    <?php endif; ?>
    
    <form action="" method="POST" enctype="multipart/form-data" class="profile-form">
        <div class="form-row">
            <div class="form-group">
                <label for="profile_image">Profile Image</label>
                <div class="profile-image-upload">
                    <div class="image-preview">
                        <img src="../admin/assets/images/instructors/<?= $instructor['profile_image'] ?: 'default-instructor.jpg' ?>" alt="<?= $instructor['full_name'] ?>" id="profile-image-preview">
                    </div>
                    <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*">
                </div>
            </div>
            
            <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?= $instructor['full_name'] ?>" required>
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
        
        <div class="form-group">
            <label for="qualifications">Qualifications *</label>
            <textarea id="qualifications" name="qualifications" class="form-control" rows="3" required><?= $instructor['qualifications'] ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" class="form-control" rows="4"><?= $instructor['bio'] ?></textarea>
        </div>
        
        <div class="form-section">
            <h3>Change Password</h3>
            <p>Leave blank to keep current password</p>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn">Update Profile</button>
        </div>
    </form>
</div>

<script>
    // Profile image preview
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const preview = document.getElementById('profile-image-preview');
        
        if(this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>

<?php include 'footer.php'; ?>