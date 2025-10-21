// Efectos de partículas para el fondo
function createParticles() {
    const container = document.createElement('div');
    container.className = 'bg-particles';
    document.body.appendChild(container);

    for (let i = 0; i < 20; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        const size = Math.random() * 10 + 5;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.top = `${Math.random() * 100}%`;
        particle.style.animationDelay = `${Math.random() * 5}s`;
        particle.style.background = i % 2 === 0 ? 
            'linear-gradient(45deg, #dc2626, #f59e0b)' : 
            'linear-gradient(45deg, #f59e0b, #dc2626)';
        
        container.appendChild(particle);
    }
}

// Efectos de entrada para las cards
function animateCards() {
    const cards = document.querySelectorAll('.card, .dashboard-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Contadores animados para el dashboard
function animateCounters() {
    const counters = document.querySelectorAll('.dashboard-number');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        let current = 0;
        const increment = target / 50;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current);
                setTimeout(updateCounter, 30);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    });
}

// Inicializar efectos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    createParticles();
    animateCards();
    
    // Solo animar contadores si estamos en la página principal
    if (window.location.search === '' || window.location.search === '?controller=empresas&action=index') {
        animateCounters();
    }
    
    // Efecto de typing para títulos
    const titles = document.querySelectorAll('.card-header h4, h2');
    titles.forEach(title => {
        const text = title.textContent;
        title.textContent = '';
        let i = 0;
        
        const typeWriter = () => {
            if (i < text.length) {
                title.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        };
        
        typeWriter();
    });
});

