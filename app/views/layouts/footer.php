    </main>
    
    <!-- Footer -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <footer class="bg-light py-3 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
        </div>
    </footer>
    <?php endif; ?>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= ASSET_URL ?>js/app.js"></script>
    
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
                }
            });
            
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
</body>
</html>