<footer>
    <div class="footer-content">
        <div>&copy; 2025 Mototaxis Huanta. Todos los derechos reservados.</div>
        <div>Desarrollado por <span class="footer-developer">VegeTA</span></div>
    </div>
</footer>

<style>
    footer {
        background-color: #1a365d; /* Azul oscuro */
        color: white;
        padding: 12px 0;
        width: 100%;
        border-top: 3px solid #2d3748;
        position: relative;
        margin-top: auto;
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
</style>