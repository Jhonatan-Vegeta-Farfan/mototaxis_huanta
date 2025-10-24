</div> <!-- Cierre de main-content -->

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">&copy; 2025 Sistema Mototaxis Huanta. Municipalidad Provincial de Huanta.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">Desarrollado por <strong class="text-warning">Vegeta Code</strong></p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts personalizados -->
<script src="../assets/js/alert-system.js"></script>
<script src="../assets/js/effects.js"></script>

<script>
    // Resaltar elemento activo en el menú
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.href;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            if (link.href === currentPage) {
                link.classList.add('active');
            }
        });
        
        // Manejar el colapso del menú en móviles al hacer clic en un enlace
        const navLinksMobile = document.querySelectorAll('.navbar-nav .nav-link');
        const navbarCollapse = document.querySelector('.navbar-collapse');
        
        navLinksMobile.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                    bsCollapse.hide();
                }
            });
        });
        
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Inicializar popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    });
    
    // Ajustar el padding del body según la altura del navbar
    function adjustBodyPadding() {
        const navbarHeight = document.querySelector('.navbar')?.offsetHeight || 0;
        document.body.style.paddingTop = navbarHeight + 'px';
    }
    
    // Ejecutar al cargar y al redimensionar
    window.addEventListener('load', adjustBodyPadding);
    window.addEventListener('resize', adjustBodyPadding);
    
    // Función para confirmar eliminaciones
    function confirmDelete(message = '¿Está seguro de eliminar este registro?') {
        return confirm(message);
    }
    
    // Función para mostrar loading en botones
    function showLoading(button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
        button.disabled = true;
        return originalText;
    }
    
    function hideLoading(button, originalText) {
        button.innerHTML = originalText;
        button.disabled = false;
    }
</script>
</body>
</html>