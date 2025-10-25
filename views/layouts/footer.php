<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Mototaxis Huanta</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        
        footer {
            background-color: #1a365d; /* Azul oscuro */
            color: white;
            padding: 12px 0;
            width: 100%;
            border-top: 3px solid #2d3748;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .footer-developer {
            color: #ffffff;
            font-weight: bold;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }
        }
        
        h1 {
            color: #2d3748;
            margin-bottom: 15px;
        }
        
        p {
            color: #4a5568;
            line-height: 1.6;
        }
    </style>
</head>

    <footer>
        <div class="footer-content">
            <div>&copy; 2025 Sistema Mototaxis Huanta. Municipalidad Provincial de Huanta.</div>
            <div>Desarrollado por <span class="footer-developer">Vegeta Code</span></div>
        </div>
    </footer>
</body>
</html>