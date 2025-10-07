<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Mototaxis Huanta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0c1a27;
            --secondary-color: #0d2b3a;
            --accent-color: #0f3a4a;
            --highlight-color: #40E0D0;
            --highlight-hover: #20C6B0;
            --text-light: #f1f1f1;
            --text-muted: #b8b8b8;
            --gradient-primary: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            --gradient-secondary: linear-gradient(135deg, #0c1a27 0%, #0d2b3a 50%, #0f3a4a 100%);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Header moderno con tema turquesa */
        .navbar {
            background: var(--gradient-secondary);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            padding: 0.7rem 0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-primary);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--text-light) !important;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0;
        }
        
        .navbar-brand::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--highlight-color);
            transition: width 0.3s ease;
        }
        
        .navbar-brand:hover::after {
            width: 100%;
        }
        
        .navbar-brand i {
            color: var(--highlight-color);
            margin-right: 12px;
            font-size: 1.9rem;
            transition: all 0.3s ease;
            text-shadow: 0 0 10px rgba(64, 224, 208, 0.5);
        }
        
        .navbar-brand:hover i {
            transform: rotate(-15deg) scale(1.1);
            text-shadow: 0 0 15px rgba(64, 224, 208, 0.8);
        }
        
        .nav-link {
            color: var(--text-light) !important;
            font-weight: 600;
            padding: 0.8rem 1.2rem !important;
            margin: 0 3px;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid transparent;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(64, 224, 208, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-link:hover::before {
            left: 100%;
        }
        
        .nav-link:hover {
            background: rgba(64, 224, 208, 0.1);
            transform: translateY(-3px);
            border-color: rgba(64, 224, 208, 0.3);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .nav-link.active {
            background: rgba(64, 224, 208, 0.15);
            border-color: rgba(64, 224, 208, 0.5);
            box-shadow: 0 0 15px rgba(64, 224, 208, 0.3);
        }
        
        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }
        
        .nav-link:hover i {
            transform: scale(1.2);
        }
        
        .navbar-toggler {
            border: 1px solid rgba(64, 224, 208, 0.3);
            padding: 0.4rem 0.7rem;
            transition: all 0.3s ease;
        }
        
        .navbar-toggler:hover {
            border-color: var(--highlight-color);
            box-shadow: 0 0 10px rgba(64, 224, 208, 0.5);
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(64, 224, 208, 0.25);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2864, 224, 208, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            transition: transform 0.3s ease;
        }
        
        .navbar-toggler:hover .navbar-toggler-icon {
            transform: scale(1.1);
        }
        
        /* Logo styles mejorados */
        .logo-container {
            display: flex;
            align-items: center;
            margin-right: 20px;
            position: relative;
        }
        
        .logo-img {
            height: 45px;
            width: auto;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        .logo-img:hover {
            transform: scale(1.08) rotate(2deg);
            border-color: var(--highlight-color);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3), 0 0 15px rgba(64, 224, 208, 0.4);
        }
        
        /* Badge para indicador de sistema activo */
        .system-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--gradient-primary);
            color: white;
            border-radius: 50%;
            width: 12px;
            height: 12px;
            font-size: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse-badge 2s infinite;
            box-shadow: 0 0 10px rgba(64, 224, 208, 0.7);
        }
        
        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.8; }
        }
        
        /* Efectos para dispositivos móviles */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: var(--gradient-secondary);
                border-radius: 0 0 12px 12px;
                padding: 15px;
                margin-top: 10px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
                border-top: 1px solid rgba(64, 224, 208, 0.2);
            }
            
            .nav-link {
                margin: 8px 0;
                text-align: center;
                padding: 1rem !important;
                border-radius: 10px;
            }
            
            .logo-container {
                margin-right: auto;
            }
            
            .logo-img {
                height: 50px;
            }
            
            .navbar-brand {
                font-size: 1.4rem;
            }
        }
        
        /* Efectos de partículas sutiles */
        .particle {
            position: absolute;
            background: rgba(64, 224, 208, 0.3);
            border-radius: 50%;
            pointer-events: none;
        }
        
        /* Contenedor principal */
        .container.mt-4 {
            margin-top: 2rem !important;
        }
        
        /* Efecto de brillo en hover para elementos principales */
        .glow-on-hover {
            position: relative;
            overflow: hidden;
        }
        
        .glow-on-hover::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(64, 224, 208, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .glow-on-hover:hover::after {
            opacity: 1;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <div class="logo-container">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSGxPXJ3rHcxwz-FWTO6nqLKSvMwogHlgdZIQ&s" 
                     alt="Logo Mototaxis Huanta" class="logo-img">
                <div class="system-badge"></div>
            </div>
            <a class="navbar-brand glow-on-hover" href="index.php">
                <i class="fas fa-motorcycle"></i> Mototaxis Huanta
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link glow-on-hover" href="index.php?controller=empresas&action=index">
                            <i class="fas fa-building"></i> Empresas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link glow-on-hover" href="index.php?controller=mototaxis&action=index">
                            <i class="fas fa-motorcycle"></i> Mototaxis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link glow-on-hover" href="index.php?controller=client_api&action=index">
                            <i class="fas fa-users"></i> Clientes API
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link glow-on-hover" href="index.php?controller=tokens_api&action=index">
                            <i class="fas fa-key"></i> Tokens API
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link glow-on-hover" href="index.php?controller=count_request&action=index">
                            <i class="fas fa-chart-bar"></i> Requests
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto de partículas sutiles
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');
            const createParticle = () => {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                const size = Math.random() * 4 + 1;
                const posX = Math.random() * window.innerWidth;
                const duration = Math.random() * 3 + 2;
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${posX}px`;
                particle.style.top = '-10px';
                particle.style.animation = `fall ${duration}s linear forwards`;
                
                navbar.appendChild(particle);
                
                setTimeout(() => {
                    particle.remove();
                }, duration * 1000);
            };
            
            // Crear partículas periódicamente
            setInterval(createParticle, 300);
            
            // Agregar estilo para la animación de caída
            const style = document.createElement('style');
            style.textContent = `
                @keyframes fall {
                    to {
                        transform: translateY(${navbar.offsetHeight + 20}px);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Resaltar elemento activo en el menú
            const currentPage = window.location.href;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.href === currentPage) {
                    link.classList.add('active');
                }
                
                // Efecto de ripple al hacer clic
                link.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(64, 224, 208, 0.6);
                        transform: scale(0);
                        animation: ripple-animation 0.6s linear;
                        pointer-events: none;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                    `;
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Agregar estilo para el efecto ripple
            const rippleStyle = document.createElement('style');
            rippleStyle.textContent = `
                @keyframes ripple-animation {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(rippleStyle);
        });
    </script>
</body>
</html>