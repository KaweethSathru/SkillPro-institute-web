<!-- components/login.php -->
<?php

include 'connection.php';

$error = '';

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    // Determine table based on role
    $table = match($role) {
        'admin' => 'admin',
        'instructor' => 'instructors',
        'student' => 'students',
        default => 'students'
    };
    
    $sql = "SELECT * FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $role;
        $_SESSION['full_name'] = $user['full_name'];
        
        // Redirect based on role
        switch($role) {
            case 'admin':
                header('Location: admin/dashboard.php');
                break;
            case 'instructor':
                header('Location: instructor/dashboard.php');
                break;
            default:
                echo "<script>window.location.href='index.php';</script>";
        }
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
    <title>Login | SkillPro Institute</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-image"></div>
        <div class="auth-form-container">
            <div class="auth-form">
                <h2>Login to Your Account</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <div class="role-selector">
                        <label class="role-option" for="role-student">
                            <input type="radio" name="role" id="role-student" value="student" checked>
                            <i class="fas fa-user-graduate"></i>
                            <div>Student</div>
                        </label>
                        <label class="role-option" for="role-instructor">
                            <input type="radio" name="role" id="role-instructor" value="instructor">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <div>Instructor</div>
                        </label>
                        <label class="role-option" for="role-admin">
                            <input type="radio" name="role" id="role-admin" value="admin">
                            <i class="fas fa-user-cog"></i>
                            <div>Admin</div>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-block">Login</button>
                    </div>
                    
                    <div class="auth-links">
                        <p>Don't have an account? <a href="../register.php">Register here</a></p>
                        <p><a href="forgot_password.php">Forgot your password?</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Role selection functionality
        const roleOptions = document.querySelectorAll('.role-option');
        roleOptions.forEach(option => {
            option.addEventListener('click', () => {
                roleOptions.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
                option.querySelector('input').checked = true;
            });
        });
    </script>
</body>
</html>