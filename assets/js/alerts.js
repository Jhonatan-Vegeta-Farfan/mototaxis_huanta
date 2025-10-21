/**
 * Sistema de Alertas JSON para Mototaxis Huanta
 * Maneja alertas del sistema de forma estética y funcional
 */

class AlertSystem {
    constructor() {
        this.container = null;
        this.initialize();
    }

    initialize() {
        this.createContainer();
        this.bindEvents();
    }

    createContainer() {
        // Crear contenedor si no existe
        if (!document.querySelector('.alerts-container')) {
            this.container = document.createElement('div');
            this.container.className = 'alerts-container';
            this.container.style.cssText = `
                position: fixed;
                top: 80px;
                right: 20px;
                z-index: 1060;
                width: 400px;
                max-width: 90vw;
            `;
            document.body.appendChild(this.container);
        } else {
            this.container = document.querySelector('.alerts-container');
        }
    }

    bindEvents() {
        // Auto-eliminar alertas después de 5 segundos
        setInterval(() => {
            const alerts = this.container.querySelectorAll('.alert-custom');
            alerts.forEach(alert => {
                const timestamp = alert.getAttribute('data-timestamp');
                if (timestamp && Date.now() - parseInt(timestamp) > 5000) {
                    this.removeAlert(alert);
                }
            });
        }, 1000);
    }

    showAlert(type, message, data = null) {
        const alertId = 'alert-' + Date.now();
        const alertHTML = this.createAlertHTML(type, message, data, alertId);
        
        this.container.insertAdjacentHTML('afterbegin', alertHTML);
        
        const alertElement = document.getElementById(alertId);
        this.animateIn(alertElement);
        
        // Auto-remove después de 5 segundos
        setTimeout(() => {
            if (alertElement.parentNode) {
                this.removeAlert(alertElement);
            }
        }, 5000);
    }

    createAlertHTML(type, message, data, id) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-triangle',
            warning: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };

        let dataHTML = '';
        if (data && typeof data === 'object') {
            dataHTML = '<div class="mt-2"><small>';
            for (const [key, value] of Object.entries(data)) {
                if (typeof value === 'object') continue;
                dataHTML += `<div><strong>${this.capitalize(key)}:</strong> ${value}</div>`;
            }
            dataHTML += '</small></div>';
        }

        return `
            <div id="${id}" class="alert-custom ${type}" data-timestamp="${Date.now()}">
                <div class="d-flex align-items-center">
                    <div class="alert-icon">
                        <i class="fas ${icons[type] || 'fa-bell'}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>${message}</strong>
                        ${dataHTML}
                    </div>
                    <button type="button" class="alert-close" onclick="alertSystem.removeAlert(this.parentElement.parentElement)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
    }

    animateIn(element) {
        element.style.transform = 'translateX(100%)';
        element.style.opacity = '0';
        
        requestAnimationFrame(() => {
            element.style.transition = 'all 0.3s ease-out';
            element.style.transform = 'translateX(0)';
            element.style.opacity = '1';
        });
    }

    removeAlert(element) {
        element.style.transform = 'translateX(100%)';
        element.style.opacity = '0';
        
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 300);
    }

    capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Métodos de conveniencia para tipos específicos
    success(message, data = null) {
        this.showAlert('success', message, data);
    }

    error(message, data = null) {
        this.showAlert('error', message, data);
    }

    warning(message, data = null) {
        this.showAlert('warning', message, data);
    }

    info(message, data = null) {
        this.showAlert('info', message, data);
    }

    // Manejar respuestas JSON del servidor
    handleJsonResponse(response) {
        if (response.alert) {
            this.showAlert(response.alert.type, response.alert.message, response.data);
        } else if (response.success) {
            this.success(response.message, response.data);
        } else {
            this.error(response.message, response.data);
        }
    }
}

// Instancia global del sistema de alertas
const alertSystem = new AlertSystem();

// Función para mostrar alertas desde cualquier parte del código
window.showAlert = function(type, message, data = null) {
    alertSystem.showAlert(type, message, data);
};

// Integración con fetch para manejar respuestas automáticamente
const originalFetch = window.fetch;
window.fetch = function(...args) {
    return originalFetch.apply(this, args).then(response => {
        if (response.headers.get('content-type')?.includes('application/json')) {
            return response.clone().json().then(data => {
                if (data.alert || data.success !== undefined) {
                    alertSystem.handleJsonResponse(data);
                }
                return response;
            });
        }
        return response;
    });
};

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AlertSystem;
}