<!-- admin/dashboard.php -->
<?php include 'header.php'; ?>
<h1 class="page-title">Dashboard</h1>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(12, 75, 101, 0.1); color: var(--primary);">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">1,250</div>
            <div class="stat-title">Total Students</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(243, 156, 18, 0.1); color: var(--secondary);">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">48</div>
            <div class="stat-title">Instructors</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(39, 174, 96, 0.1); color: #27ae60;">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">15</div>
            <div class="stat-title">Courses</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(155, 89, 182, 0.1); color: #9b59b6;">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">LKR 2.5M</div>
            <div class="stat-title">Monthly Revenue</div>
        </div>
    </div>
</div>

<div class="dashboard-charts">
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Student Enrollment Trends</h3>
        </div>
        <canvas id="enrollmentChart"></canvas>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Course Distribution</h3>
        </div>
        <canvas id="courseChart"></canvas>
    </div>
</div>

<div class="chart-card">
    <div class="chart-header">
        <h3 class="chart-title">Recent Activities</h3>
        <a href="#" class="btn">View All</a>
    </div>
    <div class="recent-activities">
        <div class="activity-item">
            <div class="activity-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="activity-content">
                <h4>New Student Registration</h4>
                <p>Kamal Perera registered for Web Development course</p>
                <small>2 hours ago</small>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-icon">
                <i class="fas fa-money-check-alt"></i>
            </div>
            <div class="activity-content">
                <h4>Payment Received</h4>
                <p>Nayana Silva made payment of LKR 25,000</p>
                <small>5 hours ago</small>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="activity-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="activity-content">
                <h4>New Batch Started</h4>
                <p>New Plumbing batch started at Colombo branch</p>
                <small>1 day ago</small>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>