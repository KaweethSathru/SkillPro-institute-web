<!-- create_admin.php -->
<?php
include 'components/connection.php';

// Check if admin already exists
$stmt = $conn->prepare("SELECT * FROM admin LIMIT 1");
$stmt->execute();
$admin = $stmt->fetch();

if(!$admin) {
    $username = 'admin';
    $email = 'admin@skillpro.lk';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $full_name = 'Admin User';
    
    $sql = "INSERT INTO admin (username, email, full_name, password) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$username, $email, $full_name, $password])) {
        echo "Admin account created successfully!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        echo "<a href='admin/login.php'>Login to Admin Panel</a>";
    } else {
        echo "Failed to create admin account.";
    }
} else {
    echo "Admin account already exists. <a href='admin/login.php'>Login here</a>";
}
?>