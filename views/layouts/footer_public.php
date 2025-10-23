    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5" id="contacto">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="text-primary mb-4">
                        <i class="bi bi-motorcycle me-2"></i>Mototaxis Huanta
                    </h5>
                    <p class="opacity-75">
                        Sistema oficial de consulta de mototaxis registrados en la Municipalidad Provincial de Huanta.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white opacity-75 hover-primary">
                            <i class="bi bi-facebook fs-5"></i>
                        </a>
                        <a href="#" class="text-white opacity-75 hover-primary">
                            <i class="bi bi-twitter fs-5"></i>
                        </a>
                        <a href="#" class="text-white opacity-75 hover-primary">
                            <i class="bi bi-instagram fs-5"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-primary mb-3">Enlaces Rápidos</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="index.php?controller=api_public&action=index" class="text-white opacity-75 text-decoration-none hover-primary">
                                Buscar Mototaxi
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#contacto" class="text-white opacity-75 text-decoration-none hover-primary">
                                Contacto
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-primary mb-3">Contacto</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            <span class="opacity-75">Municipalidad Provincial de Huanta</span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2 text-primary"></i>
                            <span class="opacity-75">(066) 123456</span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2 text-primary"></i>
                            <span class="opacity-75">info@huanta.gob.pe</span>
                        </li>
                    </ul>
                </div>
                
                <div class="col-lg-3">
                    <h6 class="text-primary mb-3">Soporte Técnico</h6>
                    <p class="opacity-75 mb-3">
                        Para problemas técnicos con la API, contactar al área de sistemas.
                    </p>
                    <a href="mailto:soporte@huanta.gob.pe" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-headset me-2"></i>Soporte
                    </a>
                </div>
            </div>
            
            <hr class="my-4 opacity-25">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 opacity-75">
                        &copy; 2025 Municipalidad Provincial de Huanta. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 opacity-75">
                        Desarrollado por <span class="text-primary">Vegeta Code</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/alert-system.js"></script>
    <script src="../assets/js/effects.js"></script>
    
    <script>
        // Efecto hover para enlaces
        document.querySelectorAll('.hover-primary').forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.classList.add('text-primary');
                this.classList.remove('opacity-75');
            });
            
            link.addEventListener('mouseleave', function() {
                this.classList.remove('text-primary');
                this.classList.add('opacity-75');
            });
        });
        
        // Smooth scroll para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Animación de contadores (si hay)
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            const updateCounter = () => {
                start += increment;
                if (start < target) {
                    element.textContent = Math.floor(start);
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target;
                }
            };
            updateCounter();
        }
        
        // Inicializar animaciones cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Ejemplo: animar contadores si existen
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                animateCounter(counter, target);
            });
        });
    </script>
</body>
</html>