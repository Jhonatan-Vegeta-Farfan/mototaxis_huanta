</div>

<footer class="bg-dark text-white mt-auto py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p>&copy; 2025 Sistema Mototaxis Huanta. Municipalidad Provincial de Huanta.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
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
    });
    
    // Ajustar el padding del body según la altura del navbar
    function adjustBodyPadding() {
        const navbarHeight = document.querySelector('.navbar').offsetHeight;
        document.body.style.paddingTop = navbarHeight + 'px';
    }
    
    // Ejecutar al cargar y al redimensionar
    window.addEventListener('load', adjustBodyPadding);
    window.addEventListener('resize', adjustBodyPadding);
</script>
</body>
</html>