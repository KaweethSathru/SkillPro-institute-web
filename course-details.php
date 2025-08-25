<?php
include 'components/connection.php';
session_start();

// Get course ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch course details
$sql = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$course) {
    header("Location: courses.php");
    exit();
}

// Process enrollment if form submitted
$enrollment_success = false;
$error = '';

if(isset($_POST['enroll']) && isset($_SESSION['user_id'])) {
    $branch = filter_var($_POST['branch'], FILTER_SANITIZE_STRING);
    $payment_method = filter_var($_POST['payment_method'], FILTER_SANITIZE_STRING);
    
    // Validate inputs
    if(empty($branch) || empty($payment_method)) {
        $error = "Please fill all required fields!";
    } else {
        // Insert enrollment
        $sql = "INSERT INTO enrollments (student_id, course_id, branch, payment_method) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if($stmt->execute([$_SESSION['user_id'], $id, $branch, $payment_method])) {
            $enrollment_success = true;
        } else {
            $error = "Error processing enrollment. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $course['name'] ?> | SkillPro Institute</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/course.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include 'components/header.php'; ?>
    
    <section class="course-details-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="index.php">Home</a> / 
                <a href="courses.php">Courses</a> / 
                <span><?= $course['name'] ?></span>
            </div>
            <h1><?= $course['name'] ?></h1>
            <p class="course-category"><?= $course['category'] ?></p>
        </div>
    </section>
    
    <section class="course-details-content">
        <div class="container">
            <div class="course-main">
                <div class="course-image">
                    <?php if($course['image']): ?>
                        <img src="admin/assets/images/courses/<?= $course['image'] ?>" alt="<?= $course['name'] ?>">
                    <?php else: ?>
                        <div class="no-image">
                            <i class="fas fa-book"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="course-info-box">
                    <div class="info-item">
                        <i class="far fa-clock"></i>
                        <div>
                            <span>Duration</span>
                            <strong><?= $course['duration'] ?></strong>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <div>
                            <span>Course Fee</span>
                            <strong>LKR <?= number_format($course['fees'], 2) ?></strong>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-laptop-house"></i>
                        <div>
                            <span>Learning Mode</span>
                            <strong><?= $course['mode'] ?></strong>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <span>Available Branches</span>
                            <strong><?= $course['branch'] ?></strong>
                        </div>
                    </div>
                    
                    <?php if(isset($_SESSION['user_id']) && (!isset($_GET['register']) || !$enrollment_success)): ?>
                        <div class="enroll-box">
                            <a href="course-details.php?id=<?= $id ?>&register=1" class="btn enroll-btn">
                                <i class="fas fa-user-graduate"></i> Enroll Now
                            </a>
                            <p>Limited seats available for next batch</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="course-details">
                <div class="course-description">
                    <h2>Course Description</h2>
                    <p><?= $course['description'] ?></p>
                </div>
                
                <div class="course-syllabus">
                    <h2>What You'll Learn</h2>
                    <ul>
                        <?php 
                        // Sample learning points - in a real app, these would come from the database
                        $learning_points = [
                            "Fundamental concepts and principles",
                            "Hands-on practical skills",
                            "Industry-standard tools and techniques",
                            "Real-world project experience",
                            "Certification preparation"
                        ];
                        
                        foreach($learning_points as $point): 
                        ?>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <?= $point ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <?php if(isset($_GET['register']) && isset($_SESSION['user_id']) && !$enrollment_success): ?>
                    <div class="enrollment-form">
                        <h2>Enroll in <?= $course['name'] ?></h2>
                        
                        <?php if($error): ?>
                            <div class="error-message"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form action="" method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="branch">Select Branch *</label>
                                    <select id="branch" name="branch" class="form-control" required>
                                        <option value="">Choose branch</option>
                                        <option value="Colombo">Colombo</option>
                                        <option value="Kandy">Kandy</option>
                                        <option value="Matara">Matara</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="payment_method">Payment Method *</label>
                                    <select id="payment_method" name="payment_method" class="form-control" required>
                                        <option value="">Select payment method</option>
                                        <option value="Online">Online Payment</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cash">Cash Payment</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Course Fee: <strong>LKR <?= number_format($course['fees'], 2) ?></strong></label>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="enroll" class="btn">Complete Enrollment</button>
                                <a href="course-details.php?id=<?= $id ?>" class="btn secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
                
                <?php if($enrollment_success): ?>
                    <div class="enrollment-success">
                        <div class="success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2>Enrollment Successful!</h2>
                        <p>You have successfully enrolled in <strong><?= $course['name'] ?></strong></p>
                        <p>Your enrollment details have been sent to your email address.</p>
                        <div class="next-steps">
                            <a href="my-courses.php" class="btn">View My Courses</a>
                            <a href="courses.php" class="btn secondary">Browse More Courses</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <section class="related-courses">
        <div class="container">
            <h2 class="section-title">Related Courses</h2>
            <div class="courses-grid">
                <?php
                // Fetch related courses (same category)
                $sql = "SELECT * FROM courses 
                        WHERE category = ? AND id != ? 
                        ORDER BY RAND() LIMIT 3";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$course['category'], $id]);
                $related_courses = $stmt->fetchAll();
                
                if(count($related_courses) > 0):
                    foreach($related_courses as $related):
                ?>
                <div class="course-card">
                    <div class="course-image">
                        <?php if($related['image']): ?>
                            <img src="admin/assets/images/courses/<?= $related['image'] ?>" alt="<?= $related['name'] ?>">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-book"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="course-info">
                        <span class="course-category"><?= $related['category'] ?></span>
                        <h3><?= $related['name'] ?></h3>
                        <div class="course-meta">
                            <p><i class="far fa-clock"></i> <?= $related['duration'] ?></p>
                            <p><i class="fas fa-money-bill-wave"></i> LKR <?= number_format($related['fees'], 2) ?></p>
                        </div>
                        <a href="course-details.php?id=<?= $related['id'] ?>" class="btn secondary">View Details</a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p>No related courses found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <?php include 'components/footer.php'; ?>
</body>
</html>