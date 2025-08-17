<!-- instructor/login.php -->
<?php
session_start();
include '../components/connection.php';

// Redirect logged in instructors
if(isset($_SESSION['instructor_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if(isset($_POST['submit'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM instructors WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    $instructor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($instructor && password_verify($password, $instructor['password'])) {
        $_SESSION['instructor_id'] = $instructor['id'];
        $_SESSION['instructor_name'] = $instructor['full_name'];
        $_SESSION['instructor_username'] = $instructor['username'];
        $_SESSION['instructor_email'] = $instructor['email'];
        $_SESSION['instructor_branch'] = $instructor['branch'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Login | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="instructor-login">
        <div class="login-form-container">
            <div class="login-form">
                <div class="login-logo">
                    <img src="assets/images/logo.png" alt="SkillPro Institute">
                    <h1>Instructor Portal</h1>
                </div>
                
                <?php if ($error): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="forgot-password">
                        <a href="forgot_password.php">Forgot your password?</a>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-block">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>