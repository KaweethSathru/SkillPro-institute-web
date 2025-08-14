<!-- courses.php -->
<?php
include 'components/connection.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses | SkillPro Institute</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/course.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include 'components/header.php'; ?>
    
    <section class="page-header">
        <div class="container">
            <h1>Our Vocational Courses</h1>
            <p>Practical skills for real-world careers</p>
        </div>
    </section>
    
    <section class="courses-section">
        <div class="container">
            <div class="courses-filter">
                <div class="filter-options">
                    <button class="filter-btn active" data-filter="all">All Courses</button>
                    <button class="filter-btn" data-filter="ict">ICT</button>
                    <button class="filter-btn" data-filter="technical">Technical Trades</button>
                    <button class="filter-btn" data-filter="hospitality">Hospitality</button>
                </div>
                <div class="search-box">
                    <input type="text" id="course-search" placeholder="Search courses...">
                    <button><i class="fas fa-search"></i></button>
                </div>
            </div>
            
            <div class="courses-grid">
                <?php
                $sql = "SELECT * FROM courses ORDER BY created_at DESC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $courses = $stmt->fetchAll();
                
                if(count($courses) > 0):
                    foreach($courses as $course):
                        $category_class = strtolower(str_replace(' ', '-', $course['category']));
                ?>
                <div class="course-card <?= $category_class ?>">
                    <div class="course-image">
                        <?php if($course['image']): ?>
                            <img src="admin/assets/images/courses/<?= $course['image'] ?>" alt="<?= $course['name'] ?>">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-book"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="course-info">
                        <span class="course-category"><?= $course['category'] ?></span>
                        <h3><?= $course['name'] ?></h3>
                        <div class="course-meta">
                            <p><i class="far fa-clock"></i> <?= $course['duration'] ?></p>
                            <p><i class="fas fa-money-bill-wave"></i> LKR <?= number_format($course['fees'], 2) ?></p>
                        </div>
                        <p><?= substr($course['description'], 0, 100) ?>...</p>
                        <div class="course-actions">
                            <a href="course-details.php?id=<?= $course['id'] ?>" class="btn secondary">View Details</a>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <a href="course-details.php?id=<?= $course['id'] ?>&register=1" class="btn">Enroll Now</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="no-courses">
                    <i class="fas fa-book-open"></i>
                    <h3>No Courses Available</h3>
                    <p>Check back later for our upcoming courses.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Start Your Career Journey?</h2>
                <p>Join thousands of successful graduates who transformed their lives with SkillPro Institute</p>
                <div class="cta-buttons">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="courses.php" class="btn">Browse Courses</a>
                    <?php else: ?>
                        <a href="register.php" class="btn">Create Account</a>
                        <a href="login.php" class="btn secondary">Login to Enroll</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'components/footer.php'; ?>
    
    <script>
        // Course filtering
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
                
                // Filter courses
                const filter = button.dataset.filter;
                const courses = document.querySelectorAll('.course-card');
                
                courses.forEach(course => {
                    if (filter === 'all') {
                        course.style.display = 'block';
                    } else {
                        if (course.classList.contains(filter)) {
                            course.style.display = 'block';
                        } else {
                            course.style.display = 'none';
                        }
                    }
                });
            });
        });
        
        // Course search
        document.getElementById('course-search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const courses = document.querySelectorAll('.course-card');
            
            courses.forEach(course => {
                const title = course.querySelector('h3').textContent.toLowerCase();
                const category = course.querySelector('.course-category').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || category.includes(searchTerm)) {
                    course.style.display = 'block';
                } else {
                    course.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>