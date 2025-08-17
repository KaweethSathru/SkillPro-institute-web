<?php

include '../components/connection.php';

$cert_id = $_GET['id'];

$sql = "SELECT c.*, s.full_name AS student_name, s.email AS student_email,
               cr.name AS course_name, cr.duration, i.full_name AS instructor_name
        FROM certificates c
        JOIN students s ON c.student_id = s.id
        JOIN courses cr ON c.course_id = cr.id
        JOIN instructors i ON c.instructor_id = i.id
        WHERE c.id = ?";
        
$stmt = $conn->prepare($sql);
$stmt->execute([$cert_id]);
$certificate = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$certificate) {
    die("Certificate not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?= $certificate['certificate_number'] ?></title>
    <link rel="stylesheet" href="assets/css/view_certificate.css">
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <div class="watermark">SKILLPRO</div>
            <img src="assets/images/logo.png" alt="Institute Seal" class="institute-seal">
            
            <div class="certificate-header">
                <div class="certificate-title">Certificate of Completion</div>
                <div class="certificate-subtitle">This is to certify that</div>
            </div>
            
            <div class="certificate-body">
                <div class="certificate-text">
                    has successfully completed the course of study in
                </div>
                
                <div class="student-name"><?= $certificate['student_name'] ?></div>
                
                <div class="course-name"><?= $certificate['course_name'] ?></div>
                
                <div class="certificate-details">
                    <p>Completed on <?= date('F j, Y', strtotime($certificate['issue_date'])) ?></p>
                    <p>Course Duration: <?= $certificate['duration'] ?></p>
                    <p>Instructor: <?= $certificate['instructor_name'] ?></p>
                </div>
            </div>
            
            <div class="certificate-footer">
                <div class="signature-box">
                    <div class="signature-name">Dr. Anil Perera</div>
                    <div class="signature-title">Director, SkillPro Institute</div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-name"><?= $certificate['instructor_name'] ?></div>
                    <div class="signature-title">Course Instructor</div>
                </div>
            </div>
            
            <div class="certificate-number">
                Certificate No: <?= $certificate['certificate_number'] ?>
            </div>
        </div>
    </div>
</body>
</html>