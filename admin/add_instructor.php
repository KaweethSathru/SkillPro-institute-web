<?php
include '../components/connection.php';
?>

<?php include 'header.php'; ?>

<?php
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
    $password = password_hash('instructor123', PASSWORD_DEFAULT); // Default password
    
    // Handle file upload
    $profile_image = '';
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
                $profile_image = $filename;
            }
        }
    }
    
    // Insert into database
    $sql = "INSERT INTO instructors (full_name, username, email, phone, qualifications, specialization, 
            bio, experience, branch, status, profile_image, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$full_name, $username, $email, $phone, $qualifications, $specialization, 
                      $bio, $experience, $branch, $status, $profile_image, $password])) {
        $_SESSION['success_message'] = "Instructor added successfully!";
        echo "<script>window.location.href='instructors.php';</script>";
        exit();
    } else {
        $error = "Error adding instructor. Please try again.";
    }
}
?>

<div class="admin-content-header">
    <h1>Add New Instructor</h1>
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
                <input type="text" id="full_name" name="full_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone *</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="qualifications">Qualifications *</label>
                <textarea id="qualifications" name="qualifications" class="form-control" rows="3" required></textarea>
                <small class="form-text">Separate qualifications with commas</small>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="specialization">Specialization *</label>
                <select id="specialization" name="specialization" class="form-control" required>
                    <option value="">Select Specialization</option>
                    <option value="ICT">ICT</option>
                    <option value="Plumbing">Plumbing</option>
                    <option value="Welding">Welding</option>
                    <option value="Hotel Management">Hotel Management</option>
                    <option value="Electrical">Electrical</option>
                    <option value="Automotive">Automotive</option>
                    <option value="Culinary Arts">Culinary Arts</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="experience">Years of Experience *</label>
                <select id="experience" name="experience" class="form-control" required>
                    <option value="">Select Experience</option>
                    <option value="1-3 years">1-3 years</option>
                    <option value="3-5 years">3-5 years</option>
                    <option value="5-10 years">5-10 years</option>
                    <option value="10+ years">10+ years</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
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
            
            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="Active" selected>Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" class="form-control" rows="4"></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="profile_image">Profile Image</label>
                <div class="file-upload">
                    <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*">
                    <div class="file-preview"></div>
                </div>
            </div>
        </div>
        
        <div class="form-note">
            <p><strong>Note:</strong> The default password for new instructors is "instructor123". They can change it after logging in.</p>
        </div>
        
        <div class="form-actions">
            <button type="reset" class="btn secondary">Reset</button>
            <button type="submit" class="btn">Add Instructor</button>
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