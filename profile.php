<?php
include 'components/connection.php';
session_start();

// Get student details
$student_id = $_SESSION['user_id'];
$sql = "SELECT * FROM students WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header("Location: login.php");
    exit();
}

// Process profile image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    if ($_FILES['profile_image']['error'] == 0) {
        $target_dir = "assets/images/profiles/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
        $filename = "profile_" . $student_id . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $filename;
        
        // Check if image file is actual image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
            // Move file to target directory
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                // Delete old image if exists
                if ($student['profile_image'] && file_exists($student['profile_image'])) {
                    unlink($student['profile_image']);
                }
                
                // Update database
                $update_sql = "UPDATE students SET profile_image = :image WHERE id = :id";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->execute(['image' => $target_file, 'id' => $student_id]);
                
                // Refresh student data
                $stmt->execute(['id' => $student_id]);
                $student = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $success = "Profile image updated successfully!";
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    } else {
        $error = "Error uploading file: " . $_FILES['profile_image']['error'];
    }
}

// Get enrolled courses
$enrollment_sql = "SELECT courses.name, courses.category, enrollments.enrolled_at 
                   FROM enrollments 
                   JOIN courses ON enrollments.course_id = courses.id 
                   WHERE enrollments.student_id = :student_id";
$enrollment_stmt = $conn->prepare($enrollment_sql);
$enrollment_stmt->execute(['student_id' => $student_id]);
$enrollments = $enrollment_stmt->fetchAll();

// Get certificates for this student
$sql = "SELECT c.*, cr.name AS course_name, i.full_name AS instructor_name
        FROM certificates c
        JOIN courses cr ON c.course_id = cr.id
        JOIN instructors i ON c.instructor_id = i.id
        WHERE c.student_id = ?
        ORDER BY c.issue_date DESC";
        
$stmt = $conn->prepare($sql);
$stmt->execute([$student_id]);
$certificates = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body class="student-panel">
    <?php include 'components/header.php'; ?>
    
    <div class="profile-container">
        <!-- Profile Sidebar -->
        <div class="profile-sidebar">
            <form method="POST" enctype="multipart/form-data" class="profile-image-form">
                <div class="image-preview-container">
                    <div class="profile-pic">
                        <?php if ($student['profile_image']): ?>
                            <img src="<?= $student['profile_image'] ?>" alt="Student Profile Photo" id="profile-preview">
                        <?php else: ?>
                            <img src="assets/images/profile.png" alt="Student Avatar" id="profile-preview">
                        <?php endif; ?>
                    </div>
                    <div class="edit-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                
                <div class="upload-btn-wrapper">
                    <div class="upload-btn">
                        <i class="fas fa-upload"></i> Change Photo
                    </div>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" required>
                </div>
                
                <div class="image-upload-instructions">
                    Max 2MB • JPG, PNG
                </div>
                
                <button type="submit" class="btn" style="width: 100%; margin-top: 15px;">
                    <i class="fas fa-save"></i> Save Photo
                </button>
            </form>
            
            <?php if(isset($success)): ?>
                <div class="success-message" style="margin-top: 15px; padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; text-align: center;">
                    <i class="fas fa-check-circle"></i> <?= $success ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($error)): ?>
                <div class="error-message" style="margin-top: 15px; padding: 10px; background: #f8d7da; color: #721c24; border-radius: 5px; text-align: center;">
                    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                </div>
            <?php endif; ?>
            
            <div class="profile-header">
                <h1><?= $student['full_name'] ?></h1>
                <p>Student at SkillPro Institute</p>
            </div>
            
            <div class="info-card">
                <h3>Student ID</h3>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="info-text">
                        <p>SP-<?= str_pad($student['id'], 5, '0', STR_PAD_LEFT) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="profile-stats">
                <div class="stat-card">
                    <div class="number"><?= count($enrollments) ?></div>
                    <div class="label">Courses</div>
                </div>
                <div class="stat-card">
                    <div class="number"><?= count($certificates) ?></div>
                    <div class="label">Certificates</div>
                </div>
            </div>
            
            <a href="courses.php" class="btn" style="width: 100%; margin-top: 20px;">
                <i class="fas fa-book"></i> Browse Courses
            </a>
        </div>
        
        <!-- Main Content -->
        <div class="profile-content">
            <div class="info-card">
                <h3>Personal Information</h3>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="info-text">
                        <h4>Full Name</h4>
                        <p><?= $student['full_name'] ?></p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-at"></i>
                    </div>
                    <div class="info-text">
                        <h4>Email Address</h4>
                        <p><?= $student['email'] ?></p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-text">
                        <h4>Phone Number</h4>
                        <p><?= $student['phone'] ?: 'Not provided' ?></p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div class="info-text">
                        <h4>Username</h4>
                        <p><?= $student['username'] ?></p>
                    </div>
                </div>
            </div>
            
            <div class="info-card">
                <h3>Enrolled Courses</h3>
                
                <?php if(count($enrollments) > 0): ?>
                    <ul class="courses-list">
                        <?php foreach($enrollments as $enrollment): 
                            $enrollment_date = date('M d, Y', strtotime($enrollment['enrolled_at']));
                        ?>
                        <li class="course-item">
                            <div class="course-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="course-info">
                                <h4><?= $enrollment['name'] ?></h4>
                                <div class="course-meta">
                                    <span><?= $enrollment['category'] ?></span>
                                </div>
                            </div>
                            <div class="enrollment-date">
                                Enrolled: <?= $enrollment_date ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="no-enrollments">
                        <i class="fas fa-book-open"></i>
                        <h3>No Enrollments Yet</h3>
                        <p>You haven't enrolled in any courses yet.</p>
                        <a href="courses.php" class="btn secondary" style="margin-top: 15px;">Browse Courses</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="info-card">
                <h3>Account Actions</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">
                    <a href="certificates.php" class="btn secondary">
                        <i class="fas fa-certificate"></i> Certificates
                    </a>
                    <a href="contact.php" class="btn secondary">
                        <i class="fas fa-headset"></i> Support
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Profile image preview
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const preview = document.getElementById('profile-preview');
            
            if(this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
    
    <?php include 'components/footer.php'; ?>
</body>
</html>