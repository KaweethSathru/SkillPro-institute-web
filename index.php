<?php
include 'components/connection.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Skillpro Institute</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include 'components/header.php'; ?>
    
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Empowering Your Future with Vocational Excellence</h1>
                <p>TVEC-registered courses with industry-recognized certifications</p>
                <div class="hero-btns">
                    <a href="courses.php" class="btn">Explore Courses</a>
                    <a href="register.php" class="btn secondary">Apply Now</a>
                </div>
            </div>
        </div>
    </section>
    
    <section class="features">
        <div class="container">
            <div class="feature-box">
                <i class="fas fa-graduation-cap"></i>
                <h3>TVEC Certified</h3>
                <p>Government recognized qualifications</p>
            </div>
            <div class="feature-box">
                <i class="fas fa-briefcase"></i>
                <h3>Industry Partnerships</h3>
                <p>Direct employment opportunities</p>
            </div>
            <div class="feature-box">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>Expert Instructors</h3>
                <p>Experienced industry professionals</p>
            </div>
            <div class="feature-box">
                <i class="fas fa-laptop-house"></i>
                <h3>Flexible Learning</h3>
                <p>Online & On-site options available</p>
            </div>
        </div>
    </section>
    
    <section class="courses">
        <div class="container">
            <h2 class="section-title">Popular Courses</h2>
            <div class="course-grid">
                <div class="course-card">
                    <div class="course-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3>ICT & Computer Science</h3>
                    <ul>
                        <li>Web Development</li>
                        <li>Graphic Design</li>
                        <li>Network Engineering</li>
                    </ul>
                    <a href="#" class="btn">View Details</a>
                </div>
                <div class="course-card">
                    <div class="course-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Technical Trades</h3>
                    <ul>
                        <li>Plumbing</li>
                        <li>Welding & Metalwork</li>
                        <li>Electrical Installation</li>
                    </ul>
                    <a href="#" class="btn">View Details</a>
                </div>
                <div class="course-card">
                    <div class="course-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h3>Hotel Management</h3>
                    <ul>
                        <li>Culinary Arts</li>
                        <li>Hospitality Operations</li>
                        <li>Tourism Management</li>
                    </ul>
                    <a href="#" class="btn">View Details</a>
                </div>
            </div>
            <div class="center-btn">
                <a href="courses.php" class="btn secondary">View All Courses</a>
            </div>
        </div>
    </section>
    
    <section class="events">
        <div class="container">
            <h2 class="section-title">Upcoming Events</h2>
            <div class="event-calendar">
                <div class="event-card">
                    <div class="event-date">
                        <span>15</span>
                        <span>AUG</span>
                    </div>
                    <div class="event-info">
                        <h3>New Batch Orientation</h3>
                        <p><i class="far fa-clock"></i> 9:00 AM - 12:00 PM</p>
                        <p><i class="fas fa-map-marker-alt"></i> Colombo Branch</p>
                    </div>
                </div>
                <div class="event-card">
                    <div class="event-date">
                        <span>22</span>
                        <span>AUG</span>
                    </div>
                    <div class="event-info">
                        <h3>Industry Workshop: Plumbing</h3>
                        <p><i class="far fa-clock"></i> 1:00 PM - 4:00 PM</p>
                        <p><i class="fas fa-map-marker-alt"></i> Kandy Branch</p>
                    </div>
                </div>
                <div class="event-card">
                    <div class="event-date">
                        <span>05</span>
                        <span>SEP</span>
                    </div>
                    <div class="event-info">
                        <h3>Job Fair 2025</h3>
                        <p><i class="far fa-clock"></i> 8:30 AM - 5:00 PM</p>
                        <p><i class="fas fa-map-marker-alt"></i> Matara Branch</p>
                    </div>
                </div>
            </div>
            <div class="center-btn">
                <a href="events.php" class="btn secondary">View Calendar</a>
            </div>
        </div>
    </section>
    
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">Student Success Stories</h2>
            <div class="swiper testimonialSwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="testimonial">
                            <p>"The welding course at SkillPro gave me practical skills that helped me start my own workshop. Highly recommended!"</p>
                            <div class="student-info">
                                <h4>Kamal Perera</h4>
                                <p>Welding Graduate, 2024</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial">
                            <p>"I completed the web development course and got hired before graduation. The instructors are industry experts."</p>
                            <div class="student-info">
                                <h4>Nayana Silva</h4>
                                <p>ICT Graduate, 2023</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial">
                            <p>"Hotel management course transformed my career. Now I'm working at a 5-star resort with great benefits."</p>
                            <div class="student-info">
                                <h4>Saman Bandara</h4>
                                <p>Hospitality Graduate, 2024</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Start Your Career Journey?</h2>
                <p>Join thousands of successful graduates who transformed their lives with SkillPro Institute</p>
                <div class="cta-btns">
                    <a href="register.php" class="btn">Apply Now</a>
                    <a href="contact.php" class="btn secondary">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'components/footer.php'; ?>
    
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper for testimonials
        var swiper = new Swiper(".testimonialSwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                }
            }
        });
    </script>
    <script src="js/script.js"></script>
</body>
</html>