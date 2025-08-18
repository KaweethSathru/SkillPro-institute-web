<?php 
include 'header.php';

// Get the logged-in instructor ID
$instructor_id = $_SESSION['instructor_id'];

// Get courses taught by this instructor
$sql = "SELECT * FROM courses WHERE instructor_id = :instructor_id ORDER BY start_date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute(['instructor_id' => $instructor_id]);
$courses = $stmt->fetchAll();

// Get upcoming events for these courses
$events_sql = "SELECT * FROM events 
               WHERE branch IN (SELECT branch FROM courses WHERE instructor_id = :instructor_id)
               AND event_date >= CURDATE() 
               ORDER BY event_date ASC 
               LIMIT 3";
$events_stmt = $conn->prepare($events_sql);
$events_stmt->execute(['instructor_id' => $instructor_id]);
$upcoming_events = $events_stmt->fetchAll();
?>

<h1 class="page-title">My Courses</h1>

<div class="courses-container">
    <?php if (count($courses) > 0): ?>
        <div class="courses-header">
            <div class="total-courses">
                <i class="fas fa-book"></i>
                <span><?= count($courses) ?> Courses</span>
            </div>
            <div class="view-toggle">
                <button class="btn active" id="grid-view"><i class="fas fa-th"></i> Grid</button>
                <button class="btn" id="list-view"><i class="fas fa-list"></i> List</button>
            </div>
        </div>
        
        <div class="courses-grid" id="courses-view">
            <?php foreach ($courses as $course): 
                $start_date = date('M d, Y', strtotime($course['start_date']));
                $image_path = $course['image'] ? 
                    "../admin/assets/images/courses/{$course['image']}" : 
                    "assets/images/course-placeholder.jpg";
            ?>
            <div class="course-card">
                <div class="course-image">
                    <img src="<?= $image_path ?>" alt="<?= $course['name'] ?>">
                </div>
                <div class="course-info">
                    <div class="course-category"><?= $course['category'] ?></div>
                    <h3><?= $course['name'] ?></h3>
                    <div class="course-meta">
                        <div>
                            <i class="fas fa-calendar-alt"></i>
                            <span>Starts: <?= $start_date ?></span>
                        </div>
                        <div>
                            <i class="fas fa-clock"></i>
                            <span><?= $course['duration'] ?></span>
                        </div>
                        <div>
                            <i class="fas fa-location-dot"></i>
                            <span><?= $course['branch'] ?></span>
                        </div>
                        <div>
                            <i class="fas fa-users"></i>
                            <span><?= rand(5, 25) ?> Students</span>
                        </div>
                    </div>
                    <p class="course-description"><?= substr($course['description'], 0, 120) ?>...</p>
                    <div class="course-actions">
                        <a href="#" class="btn secondary">Manage Content</a>
                        <a href="#" class="btn">View Students</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-courses">
            <div class="no-courses-icon">
                <i class="fas fa-book"></i>
            </div>
            <h2>No Courses Assigned</h2>
            <p>You haven't been assigned to teach any courses yet.</p>
            <a href="#" class="btn">Contact Admin</a>
        </div>
    <?php endif; ?>
    
    <?php if (count($upcoming_events) > 0): ?>
        <div class="upcoming-events">
            <h2>Upcoming Events</h2>
            <div class="events-list">
                <?php foreach ($upcoming_events as $event): 
                    $date = date('M d', strtotime($event['event_date']));
                    $time = date('h:i A', strtotime($event['start_time']));
                ?>
                <div class="event-item">
                    <div class="event-date">
                        <div class="event-day"><?= $date ?></div>
                        <div class="event-time"><?= $time ?></div>
                    </div>
                    <div class="event-info">
                        <h3><?= $event['title'] ?></h3>
                        <p><?= $event['description'] ?></p>
                        <div class="event-location">
                            <i class="fas fa-location-dot"></i>
                            <?= $event['location'] ?>, <?= $event['branch'] ?>
                        </div>
                    </div>
                    <div class="event-actions">
                        <button class="btn-icon">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    // View toggle functionality
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    const coursesView = document.getElementById('courses-view');
    
    gridViewBtn.addEventListener('click', function() {
        coursesView.className = 'courses-grid';
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
    });
    
    listViewBtn.addEventListener('click', function() {
        coursesView.className = 'courses-list';
        listViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
    });
</script>

<?php include 'footer.php'; ?>