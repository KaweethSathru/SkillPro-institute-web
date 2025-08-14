<!-- instructor/footer.php -->
            </div> <!-- End .instructor-content -->
        </div> <!-- End .instructor-main -->
    </div> <!-- End .instructor-dashboard -->
    
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSidebar = document.querySelector('.toggle-sidebar');
            const instructorSidebar = document.querySelector('.instructor-sidebar');
            const instructorMain = document.querySelector('.instructor-main');
            
            if (toggleSidebar) {
                toggleSidebar.addEventListener('click', () => {
                    instructorSidebar.classList.toggle('active');
                    instructorMain.classList.toggle('active');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    if (!instructorSidebar.contains(e.target) && 
                        !toggleSidebar.contains(e.target) && 
                        instructorSidebar.classList.contains('active')) {
                        instructorSidebar.classList.remove('active');
                        instructorMain.classList.remove('active');
                    }
                }
            });
        });
    </script>
</body>
</html>