<!-- admin/footer.php -->
            </div> <!-- End .admin-content -->
        </div> <!-- End .admin-main -->
    </div> <!-- End .admin-dashboard -->
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Toggle sidebar on mobile
        const toggleSidebar = document.querySelector('.toggle-sidebar');
        const adminSidebar = document.querySelector('.admin-sidebar');
        const adminMain = document.querySelector('.admin-main');
        
        toggleSidebar.addEventListener('click', () => {
            adminSidebar.classList.toggle('active');
            adminMain.classList.toggle('active');
        });
        
        // Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Enrollment Chart
            const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
            const enrollmentChart = new Chart(enrollmentCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Student Enrollment',
                        data: [120, 190, 150, 210, 180, 250, 220, 300, 280, 320, 300, 350],
                        backgroundColor: 'rgba(12, 75, 101, 0.7)',
                        borderColor: 'rgba(12, 75, 101, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Course Distribution Chart
            const courseCtx = document.getElementById('courseChart').getContext('2d');
            const courseChart = new Chart(courseCtx, {
                type: 'doughnut',
                data: {
                    labels: ['ICT', 'Plumbing', 'Welding', 'Hotel Management', 'Electrical'],
                    datasets: [{
                        label: 'Enrollments by Course',
                        data: [35, 20, 15, 25, 5],
                        backgroundColor: [
                            'rgba(12, 75, 101, 0.7)',
                            'rgba(243, 156, 18, 0.7)',
                            'rgba(41, 128, 185, 0.7)',
                            'rgba(39, 174, 96, 0.7)',
                            'rgba(142, 68, 173, 0.7)'
                        ],
                        borderColor: [
                            'rgba(12, 75, 101, 1)',
                            'rgba(243, 156, 18, 1)',
                            'rgba(41, 128, 185, 1)',
                            'rgba(39, 174, 96, 1)',
                            'rgba(142, 68, 173, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        });
    </script>
    <script src="assets/js/admin.js"></script>
</body>
</html>