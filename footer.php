    </div>
    <!-- End Main Content -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="js/loading.js?v=<?= time() ?>"></script>
    
    <script>
        // Highlight active nav link
        $(document).ready(function() {
            const currentPage = window.location.pathname.split('/').pop();
            $('.nav-link').each(function() {
                const href = $(this).attr('href');
                if (href === currentPage) {
                    $(this).addClass('active');
                }
            });
        });
    </script>
</body>
</html>
