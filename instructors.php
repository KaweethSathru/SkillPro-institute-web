<?php
include 'components/connection.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Instructors | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/instructors.css">
</head>
<body>
    <?php include 'components/header.php'; ?>
    
    <section class="instructors-header">
        <div class="container">
            <h1>Meet Our Expert Instructors</h1>
            <p>Learn from industry professionals with years of hands-on experience and passion for teaching</p>
        </div>
    </section>
    
    <section class="instructors-container">
        <div class="instructors-filter">
            <div class="filter-search">
                <i class="fas fa-search"></i>
                <input type="text" id="instructor-search" placeholder="Search instructors by name or specialization...">
            </div>
            <div class="filter-specialization">
                <select id="specialization-filter">
                    <option value="">All Specializations</option>
                    <option value="ICT">ICT</option>
                    <option value="Plumbing">Plumbing</option>
                    <option value="Welding">Welding</option>
                    <option value="Hotel Management">Hotel Management</option>
                    <option value="Electrical">Electrical</option>
                    <option value="Automotive">Automotive</option>
                    <option value="Culinary Arts">Culinary Arts</option>
                </select>
            </div>
        </div>
        
        <div class="instructors-grid" id="instructors-grid">
            <?php
            // Fetch all active instructors
            $sql = "SELECT * FROM instructors WHERE status = 'Active' ORDER BY full_name ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $instructors = $stmt->fetchAll();
            
            if(count($instructors) > 0):
                foreach($instructors as $instructor):
                    $qualifications = explode(',', $instructor['qualifications']);
            ?>
            <div class="instructor-card" data-specialization="<?= $instructor['specialization'] ?>">
                <div class="instructor-image">
                    <img src="admin/assets/images/instructors/<?= $instructor['profile_image'] ?: 'default-instructor.jpg' ?>" alt="<?= $instructor['full_name'] ?>">
                    <div class="instructor-specialization"><?= $instructor['specialization'] ?></div>
                </div>
                <div class="instructor-info">
                    <h3 class="instructor-name"><?= $instructor['full_name'] ?></h3>
                    <div class="instructor-qualifications">
                        <?php foreach($qualifications as $qualification): ?>
                            <span class="qualification"><?= trim($qualification) ?></span><br>
                        <?php endforeach; ?>
                    </div>
                    <div class="instructor-experience">
                        <i class="fas fa-briefcase"></i>
                        <?= $instructor['experience'] ?> Experience
                    </div>
                    <p class="instructor-bio"><?= $instructor['bio'] ?></p>
                    <div class="instructor-contact">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <?= $instructor['email'] ?>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <?= $instructor['phone'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="no-instructors">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>No Instructors Found</h3>
                <p>Please check back later for our instructor profiles.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>
    
    <?php include 'components/footer.php'; ?>
    
    <script>
        // Instructor filtering and search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('instructor-search');
            const specializationFilter = document.getElementById('specialization-filter');
            const instructorCards = document.querySelectorAll('.instructor-card');
            
            function filterInstructors() {
                const searchTerm = searchInput.value.toLowerCase();
                const specialization = specializationFilter.value;
                
                instructorCards.forEach(card => {
                    const name = card.querySelector('.instructor-name').textContent.toLowerCase();
                    const specializationValue = card.dataset.specialization;
                    const qualifications = card.querySelector('.instructor-qualifications').textContent.toLowerCase();
                    
                    const matchesSearch = name.includes(searchTerm) || 
                                        specializationValue.toLowerCase().includes(searchTerm) ||
                                        qualifications.includes(searchTerm);
                                        
                    const matchesSpecialization = specialization === '' || specializationValue === specialization;
                    
                    if (matchesSearch && matchesSpecialization) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
            
            searchInput.addEventListener('input', filterInstructors);
            specializationFilter.addEventListener('change', filterInstructors);
        });
    </script>
</body>
</html>