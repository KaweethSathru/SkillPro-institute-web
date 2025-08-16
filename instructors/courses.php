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

<style>
    .courses-container {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .courses-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .total-courses {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
        color: var(--primary);
        font-weight: 600;
    }
    
    .total-courses i {
        font-size: 1.5rem;
    }
    
    .view-toggle {
        display: flex;
        gap: 10px;
    }
    
    .view-toggle .btn {
        padding: 8px 15px;
        font-size: 0.9rem;
    }
    
    .view-toggle .btn.active {
        background: var(--primary);
        color: white;
    }
    
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }
    
    .course-card {
        border: 1px solid #eee;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border-color: rgba(12, 75, 101, 0.2);
    }
    
    .course-image {
        height: 180px;
        overflow: hidden;
        border-bottom: 1px solid #eee;
    }
    
    .course-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    
    .course-card:hover .course-image img {
        transform: scale(1.05);
    }
    
    .course-info {
        padding: 20px;
    }
    
    .course-category {
        display: inline-block;
        background: rgba(12, 75, 101, 0.1);
        color: var(--primary);
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .course-info h3 {
        margin: 0 0 15px 0;
        color: var(--primary);
    }
    
    .course-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 15px;
    }
    
    .course-meta div {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #555;
        font-size: 0.9rem;
    }
    
    .course-description {
        color: #666;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    
    .course-actions {
        display: flex;
        gap: 15px;
    }
    
    .no-courses {
        text-align: center;
        padding: 50px 20px;
    }
    
    .no-courses-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(12, 75, 101, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: var(--primary);
        font-size: 2.5rem;
    }
    
    .no-courses h2 {
        color: var(--primary);
        margin-bottom: 15px;
    }
    
    .no-courses p {
        color: #666;
        margin-bottom: 25px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .upcoming-events {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 25px;
        margin-top: 40px;
    }
    
    .upcoming-events h2 {
        margin-top: 0;
        margin-bottom: 25px;
        color: var(--primary);
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .events-list {
        display: grid;
        gap: 20px;
    }
    
    .event-item {
        display: flex;
        gap: 20px;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }
    
    .event-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .event-date {
        min-width: 80px;
        text-align: center;
        padding: 15px 10px;
        background: rgba(12, 75, 101, 0.1);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .event-day {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 5px;
    }
    
    .event-time {
        font-size: 0.9rem;
        color: #555;
    }
    
    .event-info {
        flex-grow: 1;
    }
    
    .event-info h3 {
        margin: 0 0 10px 0;
        color: var(--primary);
    }
    
    .event-info p {
        margin-bottom: 10px;
        color: #666;
        font-size: 0.95rem;
    }
    
    .event-location {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #555;
        font-size: 0.9rem;
    }
    
    .event-actions {
        display: flex;
        align-items: flex-start;
    }
    
    .btn-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-icon:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    @media (max-width: 768px) {
        .courses-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .view-toggle {
            width: 100%;
        }
        
        .courses-grid {
            grid-template-columns: 1fr;
        }
        
        .event-item {
            flex-direction: column;
        }
    }
</style>

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