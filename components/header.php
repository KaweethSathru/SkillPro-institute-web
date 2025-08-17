<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="header">
    <nav class="navbar">
        <a href="index.php" class="logo">
            <img src="../assets/images/logo.png" alt="SkillPro Institute">
            <span>SkillPro Institute</span>
        </a>
        
        <ul class="nav-links">
            <li><a href="index.php" class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">Home</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="instructors.php">Instructors</a></li>
            <li><a href="events.php">Events</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        
        <div class="auth-buttons">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="user-dropdown">
                    <button class="user-btn">
                        <i class="fas fa-user-circle"></i> <?= $_SESSION['username'] ?>
                    </button>
                    <div class="dropdown-content">
                        <a href="profile.php">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>