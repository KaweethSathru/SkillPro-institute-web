<?php
include 'components/connection.php';

// Fetch featured instructors
$instructor_sql = "SELECT * FROM instructors WHERE status = 'Active' ORDER BY RAND() LIMIT 4";
$instructor_stmt = $conn->prepare($instructor_sql);
$instructor_stmt->execute();
$featured_instructors = $instructor_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/about.css">
</head>
<body class="student-panel">
    <?php include 'components/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="about-hero-content">
            <h1>About SkillPro Institute</h1>
            <p>Empowering individuals with practical skills for career success since 2010</p>
            <a href="courses.php" class="btn">Explore Our Courses</a>
        </div>
    </section>
    
    <!-- Mission & Vision -->
    <section class="about-section">
        <div class="about-container">
            <div class="mission-vision">
                <div class="mission-card">
                    <i class="fas fa-bullseye"></i>
                    <h3>Our Mission</h3>
                    <p>To provide high-quality vocational training that equips individuals with practical skills and industry-relevant knowledge, empowering them for successful careers and contributing to Sri Lanka's economic development.</p>
                </div>
                <div class="vision-card">
                    <i class="fas fa-eye"></i>
                    <h3>Our Vision</h3>
                    <p>To be Sri Lanka's leading vocational training institute recognized for excellence in skill development, innovation in training methodologies, and producing highly employable graduates.</p>
                </div>
            </div>
            
            <div class="section-header">
                <h2>Our History</h2>
                <p>SkillPro Institute has been transforming lives through vocational education for over a decade</p>
            </div>
            
            <div class="history-content">
                <div class="history-text">
                    <h3>Building Skills Since 2010</h3>
                    <p>Established in 2010 under the Tertiary and Vocational Education Commission (TVEC) of Sri Lanka, SkillPro Institute began with a single branch in Colombo and a mission to bridge the skills gap in the Sri Lankan workforce. Our founders recognized the need for practical, industry-aligned training that would prepare students for real-world challenges.</p>
                    <p>Over the years, we've expanded to three branches across the country - Colombo, Kandy, and Matara - serving thousands of students annually. Our growth has been guided by our commitment to quality education, industry partnerships, and student success.</p>
                    <p>Today, SkillPro Institute stands as a benchmark for vocational training excellence in Sri Lanka, with a graduate employment rate of 92% and partnerships with over 200 industry organizations.</p>
                </div>
                <div class="history-image">
                    <img src="assets/images/building.jpg" alt="SkillPro Institute Building">
                </div>
            </div>
            
            <div class="stats-container">
                <div class="stat-box">
                    <div class="stat-number">15,000+</div>
                    <div class="stat-title">Students Trained</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">24</div>
                    <div class="stat-title">Vocational Courses</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">92%</div>
                    <div class="stat-title">Employment Rate</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">3</div>
                    <div class="stat-title">Branches</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Values Section -->
    <section class="values-section">
        <div class="about-container">
            <div class="section-header">
                <h2>Our Core Values</h2>
                <p>The principles that guide everything we do at SkillPro Institute</p>
            </div>
            
            <div class="values-grid">
                <div class="value-card">
                    <i class="fas fa-user-graduate"></i>
                    <h3>Student-Centered</h3>
                    <p>Our students are at the heart of everything we do. We prioritize their learning experience, career goals, and personal development.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-medal"></i>
                    <h3>Excellence</h3>
                    <p>We maintain the highest standards in our curriculum, teaching methodologies, and student outcomes.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-hands-helping"></i>
                    <h3>Practical Skills</h3>
                    <p>We focus on hands-on training and real-world application to ensure our graduates are job-ready.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-sync-alt"></i>
                    <h3>Continuous Improvement</h3>
                    <p>We regularly update our programs and teaching methods to keep pace with industry advancements.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Branches Section -->
    <section class="branches-section">
        <div class="about-container">
            <div class="section-header">
                <h2>Our Branches</h2>
                <p>Access quality vocational training across Sri Lanka</p>
            </div>
            
            <div class="branches-grid">
                <div class="branch-card">
                    <div class="branch-image">
                        <img src="assets/images/building.jpg" alt="Colombo Branch">
                    </div>
                    <div class="branch-info">
                        <h3>Colombo Branch</h3>
                        <div class="branch-meta">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Galle Road, Colombo 03</span>
                        </div>
                        <div class="branch-meta">
                            <i class="fas fa-phone-alt"></i>
                            <span>+94 112 345 678</span>
                        </div>
                        <div class="branch-meta">
                            <i class="fas fa-clock"></i>
                            <span>Mon-Fri: 8:30 AM - 5:00 PM</span>
                        </div>
                    </div>
                </div>
                
                <div class="branch-card">
                    <div class="branch-image">
                        <img src="assets/images/kandy_branch.jpeg" alt="Kandy Branch">
                    </div>
                    <div class="branch-info">
                        <h3>Kandy Branch</h3>
                        <div class="branch-meta">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>456 Peradeniya Road, Kandy</span>
                        </div>
                        <div class="branch-meta">
                            <i class="fas fa-phone-alt"></i>
                            <span>+94 812 345 678</span>
                        </div>
                        <div class="branch-meta">
                            <i class="fas fa-clock"></i>
                            <span>Mon-Fri: 8:30 AM - 5:00 PM</span>
                        </div>
                    </div>
                </div>
                
                <div class="branch-card">
                    <div class="branch-image">
                        <img src="assets/images/mathara_branch.jpg" alt="Matara Branch">
                    </div>
                    <div class="branch-info">
                        <h3>Matara Branch</h3>
                        <div class="branch-meta">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>789 Matara Road, Matara</span>
                        </div>
                        <div class="branch-meta">
                            <i class="fas fa-phone-alt"></i>
                            <span>+94 412 345 678</span>
                        </div>
                        <div class="branch-meta">
                            <i class="fas fa-clock"></i>
                            <span>Mon-Fri: 8:30 AM - 5:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Team Section -->
    <section class="team-section">
        <div class="about-container">
            <div class="section-header">
                <h2>Our Leadership</h2>
                <p>Meet the dedicated professionals guiding SkillPro Institute</p>
            </div>
            
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/director.jpg" alt="Institute Director">
                    </div>
                    <div class="member-info">
                        <h3>Dr. Anil Perera</h3>
                        <div class="member-role">Director</div>
                        <p>PhD in Vocational Education, 20+ years experience in skills development</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/academic.jpg" alt="Academic Head">
                    </div>
                    <div class="member-info">
                        <h3>Ms. Nirmala Silva</h3>
                        <div class="member-role">Academic Head</div>
                        <p>MSc in Education Management, 15 years in vocational training</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/operations.jpg" alt="Operations Manager">
                    </div>
                    <div class="member-info">
                        <h3>Mr. Sanjaya Bandara</h3>
                        <div class="member-role">Operations Manager</div>
                        <p>MBA, 12 years in educational administration</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/industry.jpg" alt="Industry Relations">
                    </div>
                    <div class="member-info">
                        <h3>Mr. Ranil Fernando</h3>
                        <div class="member-role">Industry Relations</div>
                        <p>Former HR Director, 18 years corporate experience</p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="section-header" style="margin-top: 80px;">
                <h2>Featured Instructors</h2>
                <p>Our industry-expert faculty members</p>
            </div>
            
            <div class="team-grid">
                <?php foreach($featured_instructors as $instructor): ?>
                <div class="team-member">
                    <div class="member-image">
                        <img src="admin/assets/images/instructors/<?= $instructor['profile_image'] ?: 'default-instructor.jpg' ?>" alt="<?= $instructor['full_name'] ?>">
                    </div>
                    <div class="member-info">
                        <h3><?= $instructor['full_name'] ?></h3>
                        <div class="member-role"><?= $instructor['specialization'] ?></div>
                        <p><?= $instructor['qualifications'] ?></p>
                        <div class="member-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="about-container">
            <div class="section-header">
                <h2>Student Testimonials</h2>
                <p>Hear what our graduates say about their experience at SkillPro</p>
            </div>
            
            <div class="testimonials-container">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        "The practical skills I gained at SkillPro Institute completely transformed my career prospects. Within a month of completing my ICT course, I received three job offers. The instructors were industry experts who genuinely cared about our success."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="assets/images/student1.jpeg" alt="Student">
                        </div>
                        <div class="author-info">
                            <h4>Kamal Perera</h4>
                            <div class="author-role">ICT Graduate, 2024</div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        "I chose SkillPro for their excellent reputation in vocational training, and they exceeded my expectations. The hands-on approach to learning gave me the confidence to start my own welding business immediately after graduation."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="assets/images/student2.jpeg" alt="Student">
                        </div>
                        <div class="author-info">
                            <h4>Sunil Fernando</h4>
                            <div class="author-role">Welding Graduate, 2023</div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        "The Hotel Management course at SkillPro provided me with both the theoretical knowledge and practical skills needed to excel in the hospitality industry. The industry connections I made during my training helped me secure a management position at a 5-star hotel."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="assets/images/student3.jpg" alt="Student">
                        </div>
                        <div class="author-info">
                            <h4>Nayomi Silva</h4>
                            <div class="author-role">Hotel Management Graduate, 2024</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="about-cta">
        <div class="about-container">
            <h2>Ready to Transform Your Career?</h2>
            <p>Join thousands of students who have gained practical skills and advanced their careers with SkillPro Institute</p>
            <div class="cta-buttons">
                <a href="courses.php" class="btn">Browse Courses</a>
                <a href="contact.php" class="btn secondary">Contact Us</a>
            </div>
        </div>
    </section>
    
    <?php include 'components/footer.php'; ?>
</body>
</html>