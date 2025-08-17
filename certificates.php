<?php
include 'components/connection.php';
session_start();

$student_id = $_SESSION['user_id'];

// Get certificates for this student
$sql = "SELECT c.*, cr.name AS course_name, i.full_name AS instructor_name
        FROM certificates c
        JOIN courses cr ON c.course_id = cr.id
        JOIN instructors i ON c.instructor_id = i.id
        WHERE c.student_id = ?
        ORDER BY c.issue_date DESC";
        
$stmt = $conn->prepare($sql);
$stmt->execute([$student_id]);
$certificates = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Certificates | SkillPro Institute</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .certificates-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .certificates-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .certificates-header h1 {
            color: var(--primary);
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .certificates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .certificate-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
            border: 1px solid #eee;
        }
        
        .certificate-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .certificate-header {
            background: linear-gradient(120deg, #0c4b65, #1a6d8f);
            padding: 20px;
            text-align: center;
            color: white;
        }
        
        .certificate-title {
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        
        .certificate-number {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .certificate-body {
            padding: 20px;
        }
        
        .certificate-info {
            margin-bottom: 15px;
        }
        
        .certificate-info div {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        
        .certificate-info span:first-child {
            font-weight: 600;
            color: #555;
        }
        
        .certificate-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 15px;
            border-top: 1px solid #eee;
        }
        
        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-view {
            background: var(--primary);
            color: white;
        }
        
        .btn-view:hover {
            background: #1a6d8f;
        }
        
        .btn-download {
            background: var(--secondary);
            color: white;
        }
        
        .btn-download:hover {
            background: #e6a323;
        }
        
        .no-certificates {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }
        
        .no-certificates i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .no-certificates h2 {
            color: #555;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="student-panel">
    <?php include 'components/header.php'; ?>
    
    <div class="certificates-container">
        <div class="certificates-header">
            <h1>My Certificates</h1>
            <p>View and download your earned certificates from SkillPro Institute</p>
        </div>
        
        <div class="certificates-grid">
            <?php if (count($certificates) > 0): ?>
                <?php foreach ($certificates as $certificate): ?>
                <div class="certificate-card">
                    <div class="certificate-header">
                        <div class="certificate-title">Certificate of Completion</div>
                        <div class="certificate-number"><?= $certificate['certificate_number'] ?></div>
                    </div>
                    
                    <div class="certificate-body">
                        <div class="certificate-info">
                            <div>
                                <span>Course:</span>
                                <span><?= $certificate['course_name'] ?></span>
                            </div>
                            <div>
                                <span>Instructor:</span>
                                <span><?= $certificate['instructor_name'] ?></span>
                            </div>
                            <div>
                                <span>Issued On:</span>
                                <span><?= date('M d, Y', strtotime($certificate['issue_date'])) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="certificate-actions">
                        <a href="admin/view_certificate.php?id=<?= $certificate['id'] ?>" target="_blank" class="btn-icon btn-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="download_certificate.php?id=<?= $certificate['id'] ?>" class="btn-icon btn-download">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-certificates">
                    <i class="fas fa-award"></i>
                    <h2>No Certificates Yet</h2>
                    <p>You haven't earned any certificates yet. Complete a course to receive your certificate!</p>
                    <a href="courses.php" class="btn">Browse Courses</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'components/footer.php'; ?>
</body>
</html>