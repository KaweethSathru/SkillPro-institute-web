<!-- register.php -->
<?php
include 'components/connection.php';
session_start();

// Redirect logged in users
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SkillPro Institute</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'components/register.php'; ?>
</body>
</html>