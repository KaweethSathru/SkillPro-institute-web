<!-- instructor/reset_password.php -->
<?php
session_start();
include '../components/connection.php';

$error = '';
$success = '';

// Validate token
$token = $_GET['token'] ?? '';
if(empty($token)) {
    $error = "Invalid reset token.";
} else {
    $sql = "SELECT * FROM instructors WHERE reset_token = ? AND reset_expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$token]);
    $instructor = $stmt->fetch();
    
    if(!$instructor) {
        $error = "Invalid or expired reset token.";
    }
}

// Process password reset
if(isset($_POST['submit']) && empty($error)) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif(strlen($password) < 8) {
        $error = "Password must be at least 8 characters long!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE instructors 
                SET password = ?, reset_token = NULL, reset_expires = NULL 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if($stmt->execute([$hashed_password, $instructor['id']])) {
            $success = "Password has been reset successfully. You can now <a href='login.php'>login</a> with your new password.";
        } else {
            $error = "Error resetting password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Same styles as forgot_password.php */
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
                <?php else: ?>
                    <p>Please enter your new password below.</p>
                    
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-block">Reset Password</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>