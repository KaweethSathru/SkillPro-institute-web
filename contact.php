<?php
include 'components/connection.php';
session_start();

// Check if student is logged in
$student_logged_in = false;
$student_name = "";
$student_email = "";

// FIX: Changed 'user_role' to 'role'
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'student') {
    $student_logged_in = true;
    
    // Get student details
    $student_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM students WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($student) {
        $student_name = $student['full_name'];
        $student_email = $student['email'];
    }
}

// Process form submission
$form_submitted = false;
$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $error_message = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        try {
            $sql = "INSERT INTO contact_us (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'message' => $message
            ]);
            
            $form_submitted = true;
            $success_message = "Thank you for contacting us! We'll get back to you soon.";
        } catch (PDOException $e) {
            $error_message = "Error submitting your message. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/contact.css">
</head>
<body class="student-panel">
    <?php include 'components/header.php'; ?>
    
    <section class="contact-hero">
        <div class="contact-header">
            <h1>Contact SkillPro Institute</h1>
            <p>Have questions or need assistance? Reach out to us using the form below or through our contact information. We're here to help you with your vocational training journey.</p>
        </div>
    </section>

    <div class="contact-container">
        <div class="contact-content">
            <div class="contact-form-section">
                <?php if(!empty($error_message)): ?>
                    <div class="message-response error-message">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if($form_submitted): ?>
                    <div class="message-response success-message">
                        <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                    </div>
                <?php else: ?>
                    <form class="contact-form" method="POST" action="contact.php">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                value="<?php echo $student_logged_in ? htmlspecialchars($student_name) : ''; ?>" 
                                required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                value="<?php echo $student_logged_in ? htmlspecialchars($student_email) : ''; ?>" 
                                required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" class="form-control" placeholder="How can we help you?" required></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                <?php endif; ?>
            </div>
            
            <div class="contact-info-section">
                <div class="contact-info-card">
                    <h3>Get In Touch</h3>
                    
                    <div class="contact-detail">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Phone</h4>
                            <p>+94 112 345 678</p>
                        </div>
                    </div>
                    
                    <div class="contact-detail">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Email</h4>
                            <p>info@skillpro.edu.lk</p>
                        </div>
                    </div>
                    
                    <div class="contact-detail">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Office Hours</h4>
                            <p>Monday - Friday: 8:30 AM - 5:00 PM<br>
                            Saturday: 8:30 AM - 1:00 PM</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-info-card">
                    <h3>Our Branches</h3>
                    <ul class="branches-list">
                        <li>
                            <div class="branch-name">Colombo Branch</div>
                            <div class="branch-address">123 Galle Road, Colombo 03</div>
                        </li>
                        <li>
                            <div class="branch-name">Kandy Branch</div>
                            <div class="branch-address">456 Peradeniya Road, Kandy</div>
                        </li>
                        <li>
                            <div class="branch-name">Matara Branch</div>
                            <div class="branch-address">789 Matara Road, Matara</div>
                        </li>
                    </ul>
                </div>
                
                <div class="contact-info-card">
                    <h3>Follow Us</h3>
                    <div style="display: flex; gap: 15px; margin-top: 15px;">
                        <a href="#" style="color: #3b5998; font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                        <a href="#" style="color: #1da1f2; font-size: 1.5rem;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: #0077b5; font-size: 1.5rem;"><i class="fab fa-linkedin"></i></a>
                        <a href="#" style="color: #e1306c; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'components/footer.php'; ?>
</body>
</html>