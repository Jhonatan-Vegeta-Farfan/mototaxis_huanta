<?php
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php");
    exit;
}

// Procesar login
$error = '';
if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Credenciales hardcodeadas
    $valid_username = 'vegeta';
    $valid_password = '123456789';
    
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Mototaxis Huanta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-black: #1a1a1a;
            --dark-black: #0d0d0d;
            --primary-turquoise: #40e0d0;
            --primary-yellow: #f59e0b;
        }
        
        body {
            background: url('https://i.ytimg.com/vi/TW9A3iusLV8/maxresdefault.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }
        
        .login-container {
            background: linear-gradient(145deg, rgba(45, 45, 45, 0.9), rgba(26, 26, 26, 0.9));
            border-radius: 20px;
            border: 2px solid var(--primary-turquoise);
            box-shadow: 0 15px 35px rgba(64, 224, 208, 0.3);
            overflow: hidden;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(5px);
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary-turquoise), #2a9d8f);
            padding: 2rem;
            text-align: center;
            border-bottom: 3px solid var(--primary-yellow);
            position: relative;
        }
        
        .login-header h1 {
            color: white;
            font-weight: 800;
            margin: 0;
            font-size: 2.5rem;
        }
        
        .login-header p {
            color: rgba(255, 255, 255, 0.8);
            margin: 0.5rem 0 0 0;
        }
        
        .logo-container {
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--primary-turquoise);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .login-body {
            padding: 2.5rem;
        }
        
        .form-control {
            background: #2d2d2d;
            border: 2px solid #6b7280;
            border-radius: 10px;
            color: white;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            background: #2d2d2d;
            border-color: var(--primary-turquoise);
            box-shadow: 0 0 0 0.2rem rgba(64, 224, 208, 0.25);
            color: white;
        }
        
        .form-label {
            color: var(--primary-turquoise);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-turquoise), #2a9d8f);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #2a9d8f, var(--primary-turquoise));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(64, 224, 208, 0.4);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
        }
        
        .password-toggle:hover {
            color: var(--primary-turquoise);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .particle {
            position: absolute;
            background: var(--primary-turquoise);
            border-radius: 50%;
            opacity: 0.3;
            animation: float 6s infinite ease-in-out;
            z-index: 0;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .credential-info {
            background: rgba(64, 224, 208, 0.1);
            border-radius: 10px;
            padding: 10px;
            border-left: 3px solid var(--primary-turquoise);
        }
    </style>
</head>
<body>
    <!-- Partículas de fondo -->
    <div class="bg-particles"></div>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-container">
                    <div class="logo-container">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSGxPXJ3rHcxwz-FWTO6nqLKSvMwogHlgdZIQ&s" alt="Logo Mototaxis Huanta">
                    </div>
                    
                    <div class="login-header">
                        <h1><i class="fas fa-motorcycle me-2"></i>MOTOTAXIS HUANTA</h1>
                        <p>Sistema de Gestión</p>
                    </div>
                    
                    <div class="login-body">
                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-4">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-2"></i>Usuario
                                </label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Ingrese su usuario" required 
                                       value="<?php echo $_POST['username'] ?? ''; ?>">
                            </div>
                            
                            <div class="mb-4 position-relative">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Contraseña
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Ingrese su contraseña" required>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Ingresar al Sistema
                            </button>
                        </form>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efectos de partículas
        function createParticles() {
            const container = document.querySelector('.bg-particles');
            if (!container) return;

            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                const size = Math.random() * 8 + 4;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                particle.style.animationDelay = `${Math.random() * 5}s`;
                particle.style.background = i % 2 === 0 ? 
                    'linear-gradient(45deg, #40e0d0, #2a9d8f)' : 
                    'linear-gradient(45deg, #2a9d8f, #40e0d0)';
                
                container.appendChild(particle);
            }
        }

        // Mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                icon.className = 'fas fa-eye';
            }
        });

        // Auto-focus en el campo de usuario
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            document.getElementById('username').focus();
        });
    </script>
</body>
</html>