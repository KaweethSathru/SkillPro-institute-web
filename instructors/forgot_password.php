<!-- instructor/forgot_password.php -->
<?php
session_start();
include '../components/connection.php';

$error = '';
$success = '';

if(isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    // Check if email exists
    $sql = "SELECT * FROM instructors WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $instructor = $stmt->fetch();
    
    if($instructor) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hour expiration
        
        // Store token in database
        $sql = "UPDATE instructors SET reset_token = ?, reset_expires = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$token, $expires, $instructor['id']]);
        
        // Send reset email (simulated)
        $reset_link = "http://yourdomain.com/instructor/reset_password.php?token=$token";
        $success = "Password reset instructions have been sent to your email.";
    } else {
        $error = "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .auth-container {
            display: flex;
            min-height: 100vh;
            background: #f5f5f5;
        }
        
        .auth-form-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .auth-form {
            background: white;
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        
        .auth-form h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--primary);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
        }
        
        .btn-block {
            width: 100%;
            padding: 12px;
            font-size: 1.1rem;
        }
        
        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .error-message {
            color: #e74c3c;
            background: #fceae9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .success-message {
            color: #28a745;
            background: #eafaea;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-form-container">
            <div class="auth-form">
                <h2>Reset Your Password</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message"><?= $success ?></div>
                <?php endif; ?>
                
                <p>Enter your email address and we'll send you instructions to reset your password.</p>
                
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-block">Send Reset Instructions</button>
                    </div>
                    
                    <div class="auth-links">
                        <p>Remember your password? <a href="login.php">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>