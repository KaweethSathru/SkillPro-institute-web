<?php
include 'connection.php';

// Redirect if already logged in
if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'student') {
    header('Location: index.php');
    exit();
}

$error = '';

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    // Only check students table
    $sql = "SELECT * FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = 'student';
        $_SESSION['full_name'] = $user['full_name'];
        
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="student-login">
        <div class="login-form-container">
            <div class="login-form">
                <div class="login-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h2>Student Login</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn-block">Login</button>
                    </div>
                    
                    <div class="auth-links">
                        <p>Don't have an account? <a href="../register.php">Register here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>