<!-- instructor/dashboard.php -->
<?php include 'header.php'; ?>

<h1 class="page-title">Dashboard</h1>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(12, 75, 101, 0.1); color: var(--primary);">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">5</div>
            <div class="stat-title">Courses Teaching</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(243, 156, 18, 0.1); color: var(--secondary);">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">120</div>
            <div class="stat-title">Students</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(39, 174, 96, 0.1); color: #27ae60;">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">12</div>
            <div class="stat-title">Upcoming Classes</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(155, 89, 182, 0.1); color: #9b59b6;">
            <i class="fas fa-comments"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">8</div>
            <div class="stat-title">Unread Messages</div>
        </div>
    </div>
</div>

<div class="dashboard-section">
    <h2 class="section-title">Today's Schedule</h2>
    <div class="schedule-list">
        <div class="schedule-item">
            <div class="schedule-time">9:00 AM - 11:00 AM</div>
            <div class="schedule-details">
                <h3>Web Development - Batch 23</h3>
                <p>Colombo Branch - Room 302</p>
            </div>
        </div>
        <div class="schedule-item">
            <div class="schedule-time">1:00 PM - 3:00 PM</div>
            <div class="schedule-details">
                <h3>Advanced JavaScript</h3>
                <p>Online - Zoom Meeting</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-section">
    <h2 class="section-title">Recent Announcements</h2>
    <div class="announcements-list">
        <div class="announcement-item">
            <h3>Workshop on ReactJS</h3>
            <p>We're organizing a special workshop on ReactJS fundamentals next Friday. All web development students are encouraged to attend.</p>
            <small>Posted: August 15, 2025</small>
        </div>
        <div class="announcement-item">
            <h3>Mid-term Exam Schedule</h3>
            <p>The mid-term exams for all courses will be conducted from September 1-5. Please prepare accordingly.</p>
            <small>Posted: August 10, 2025</small>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>