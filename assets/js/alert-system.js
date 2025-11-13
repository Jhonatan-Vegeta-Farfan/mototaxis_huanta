/**
 * Sistema de Notificaciones Mejorado para Mototaxis Huanta
 * Maneja notificaciones del sistema de forma estética y funcional
 */

class NotificationSystem {
    constructor() {
        this.container = null;
        this.notificationCount = 0;
        this.defaultDuration = 5000;
        this.initialize();
    }

    initialize() {
        this.createContainer();
        this.bindEvents();
        this.setupGlobalHandlers();
    }

    createContainer() {
        // Crear contenedor si no existe
        if (!document.querySelector('.notification-system')) {
            this.container = document.createElement('div');
            this.container.className = 'notification-system';
            document.body.appendChild(this.container);
        } else {
            this.container = document.querySelector('.notification-system');
        }
    }

    bindEvents() {
        // Auto-eliminar notificaciones después del tiempo especificado
        setInterval(() => {
            const notifications = this.container.querySelectorAll('.notification');
            notifications.forEach(notification => {
                const timestamp = notification.getAttribute('data-timestamp');
                const duration = notification.getAttribute('data-duration') || this.defaultDuration;
                
                if (timestamp && Date.now() - parseInt(timestamp) > parseInt(duration)) {
                    this.removeNotification(notification);
                }
            });
        }, 1000);
    }

    setupGlobalHandlers() {
        // Manejar clics en botones de cerrar
        document.addEventListener('click', (e) => {
            if (e.target.closest('.notification-close')) {
                const notification = e.target.closest('.notification');
                if (notification) {
                    this.removeNotification(notification);
                }
            }
        });

        // Integración con window.onerror
        window.onerror = (msg, url, lineNo, columnNo, error) => {
            this.error('Error de JavaScript', `Error: ${msg}\nArchivo: ${url}\nLínea: ${lineNo}`);
            return false;
        };

        // Manejar promesas no capturadas
        window.addEventListener('unhandledrejection', (event) => {
            this.error('Error de Promise', event.reason?.message || 'Error no especificado');
        });
    }

    showNotification(type, title, message, options = {}) {
        const notificationId = 'notification-' + Date.now() + '-' + (++this.notificationCount);
        const notificationHTML = this.createNotificationHTML(type, title, message, notificationId, options);
        
        this.container.insertAdjacentHTML('beforeend', notificationHTML);
        
        const notificationElement = document.getElementById(notificationId);
        this.animateIn(notificationElement);
        
        // Auto-remove después del tiempo especificado
        const duration = options.duration || this.defaultDuration;
        if (duration > 0) {
            setTimeout(() => {
                if (notificationElement && notificationElement.parentNode) {
                    this.removeNotification(notificationElement);
                }
            }, duration);
        }

        // Ejecutar callback onShow si está definido
        if (options.onShow && typeof options.onShow === 'function') {
            options.onShow(notificationElement);
        }

        return notificationId;
    }

    createNotificationHTML(type, title, message, id, options) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-triangle',
            warning: 'fa-exclamation-circle',
            info: 'fa-info-circle'
        };

        const actionsHTML = options.actions ? this.createActionsHTML(options.actions, id) : '';
        const progressBarHTML = options.showProgress ? this.createProgressBarHTML(options.duration || this.defaultDuration) : '';

        return `
            <div id="${id}" class="notification ${type}" 
                 data-timestamp="${Date.now()}" 
                 data-duration="${options.duration || this.defaultDuration}">
                <div class="notification-header">
                    <div class="notification-icon">
                        <i class="fas ${icons[type] || 'fa-bell'}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${this.escapeHtml(title)}</div>
                        <div class="notification-message">${this.escapeHtml(message)}</div>
                    </div>
                    <button type="button" class="notification-close" aria-label="Cerrar notificación">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                ${progressBarHTML}
                ${actionsHTML}
            </div>
        `;
    }

    createActionsHTML(actions, notificationId) {
        let actionsHTML = '<div class="notification-actions">';
        actions.forEach(action => {
            const clickHandler = action.onclick ? action.onclick.replace('{id}', notificationId) : '';
            actionsHTML += `
                <button type="button" class="btn btn-sm ${action.class || 'btn-outline-secondary'}" 
                        onclick="${clickHandler}">
                    ${this.escapeHtml(action.text)}
                </button>
            `;
        });
        actionsHTML += '</div>';
        return actionsHTML;
    }

    createProgressBarHTML(duration) {
        return `
            <div class="notification-progress">
                <div class="notification-progress-bar" style="animation-duration: ${duration}ms"></div>
            </div>
        `;
    }

    animateIn(element) {
        if (!element) return;
        
        element.style.transform = 'translateX(100%)';
        element.style.opacity = '0';
        
        requestAnimationFrame(() => {
            element.style.transition = 'all 0.3s ease-out';
            element.style.transform = 'translateX(0)';
            element.style.opacity = '1';
        });
    }

    removeNotification(element) {
        if (!element || !element.parentNode) return;
        
        // Marcar como saliendo y animar
        element.classList.add('exiting');
        
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 300);
    }

    removeNotificationById(id) {
        const element = document.getElementById(id);
        if (element) {
            this.removeNotification(element);
        }
    }

    removeAllNotifications() {
        const notifications = this.container.querySelectorAll('.notification');
        notifications.forEach(notification => {
            this.removeNotification(notification);
        });
    }

    // Métodos de conveniencia para tipos específicos
    success(title, message, options = {}) {
        return this.showNotification('success', title, message, options);
    }

    error(title, message, options = {}) {
        return this.showNotification('error', title, message, options);
    }

    warning(title, message, options = {}) {
        return this.showNotification('warning', title, message, options);
    }

    info(title, message, options = {}) {
        return this.showNotification('info', title, message, options);
    }

    // Mostrar notificación desde respuesta AJAX
    showFromAjaxResponse(response, options = {}) {
        if (response.success) {
            return this.success(response.title || 'Éxito', response.message, options);
        } else {
            return this.error(response.title || 'Error', response.message, options);
        }
    }

    // Mostrar notificación de error de red
    showNetworkError() {
        return this.error(
            'Error de Conexión', 
            'No se pudo conectar con el servidor. Verifique su conexión a internet.',
            {
                duration: 8000,
                actions: [
                    {
                        text: 'Reintentar',
                        class: 'btn-primary',
                        onclick: 'window.location.reload()'
                    }
                ]
            }
        );
    }

    // Mostrar notificación de carga
    showLoading(title = 'Cargando...', message = 'Por favor espere') {
        return this.info(title, message, {
            duration: 0, // No auto-remover
            showProgress: false
        });
    }

    // Mostrar notificación de éxito con acciones
    showSuccessWithActions(title, message, actions) {
        return this.success(title, message, {
            duration: 10000,
            actions: actions
        });
    }

    // Mostrar notificación de confirmación
    showConfirmation(title, message, confirmAction, cancelAction) {
        return this.info(title, message, {
            duration: 15000,
            actions: [
                {
                    text: 'Confirmar',
                    class: 'btn-success',
                    onclick: confirmAction
                },
                {
                    text: 'Cancelar',
                    class: 'btn-secondary',
                    onclick: cancelAction || 'notificationSystem.removeNotificationById(\'{id}\')'
                }
            ]
        });
    }

    // Utilidad para escapar HTML
    escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') return unsafe;
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Obtener estadísticas de notificaciones
    getStats() {
        const notifications = this.container.querySelectorAll('.notification');
        const stats = {
            total: notifications.length,
            byType: {
                success: 0,
                error: 0,
                warning: 0,
                info: 0
            }
        };

        notifications.forEach(notification => {
            const type = notification.className.match(/success|error|warning|info/);
            if (type) {
                stats.byType[type[0]]++;
            }
        });

        return stats;
    }
}

// Instancia global del sistema de notificaciones
const notificationSystem = new NotificationSystem();

// Función global para mostrar notificaciones
window.showNotification = function(type, title, message, options = {}) {
    return notificationSystem.showNotification(type, title, message, options);
};

// Funciones globales de conveniencia
window.showSuccess = function(title, message, options = {}) {
    return notificationSystem.success(title, message, options);
};

window.showError = function(title, message, options = {}) {
    return notificationSystem.error(title, message, options);
};

window.showWarning = function(title, message, options = {}) {
    return notificationSystem.warning(title, message, options);
};

window.showInfo = function(title, message, options = {}) {
    return notificationSystem.info(title, message, options);
};

// Integración con fetch para manejar respuestas automáticamente
const originalFetch = window.fetch;
window.fetch = function(...args) {
    // Mostrar notificación de carga para requests largos
    let loadingNotificationId = null;
    const loadingTimer = setTimeout(() => {
        loadingNotificationId = notificationSystem.showLoading('Cargando', 'Procesando su solicitud...');
    }, 1000);

    return originalFetch.apply(this, args)
        .then(response => {
            clearTimeout(loadingTimer);
            if (loadingNotificationId) {
                notificationSystem.removeNotificationById(loadingNotificationId);
            }

            if (response.headers.get('content-type')?.includes('application/json')) {
                return response.clone().json().then(data => {
                    if (data.success !== undefined) {
                        notificationSystem.showFromAjaxResponse(data);
                    }
                    return response;
                }).catch(() => response);
            }
            return response;
        })
        .catch(error => {
            clearTimeout(loadingTimer);
            if (loadingNotificationId) {
                notificationSystem.removeNotificationById(loadingNotificationId);
            }

            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                notificationSystem.showNetworkError();
            } else {
                notificationSystem.error('Error', error.message || 'Ha ocurrido un error inesperado');
            }
            throw error;
        });
};

// Integración con jQuery Ajax si está disponible
if (window.jQuery) {
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (xhr.responseJSON && xhr.responseJSON.success !== undefined) {
            notificationSystem.showFromAjaxResponse(xhr.responseJSON);
        }
    });

    $(document).ajaxError(function(event, xhr, settings, error) {
        if (xhr.status === 0) {
            notificationSystem.showNetworkError();
        } else {
            notificationSystem.error('Error de Servidor', `Error ${xhr.status}: ${error}`);
        }
    });
}

// Manejar mensajes desde PHP/alertas del servidor
document.addEventListener('DOMContentLoaded', function() {
    // Buscar alertas del servidor en el DOM
    const serverAlerts = document.querySelectorAll('.alert-custom, .alert');
    serverAlerts.forEach(alert => {
        const type = alert.className.match(/success|error|danger|warning|info/);
        if (type) {
            const notificationType = type[0] === 'danger' ? 'error' : type[0];
            const title = alert.querySelector('strong')?.textContent || 'Notificación';
            const message = alert.textContent.replace(title, '').trim();
            
            notificationSystem[notificationType](title, message);
            
            // Remover la alerta original después de un tiempo
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }
    });

    // Manejar alertas desde sessionStorage (para redirecciones)
    const storedAlert = sessionStorage.getItem('systemAlert');
    if (storedAlert) {
        try {
            const alertData = JSON.parse(storedAlert);
            notificationSystem.showNotification(alertData.type, alertData.title, alertData.message, alertData.options);
            sessionStorage.removeItem('systemAlert');
        } catch (e) {
            console.error('Error parsing stored alert:', e);
        }
    }
});

// Función para almacenar alertas para redirecciones
window.storeAlertForRedirect = function(type, title, message, options = {}) {
    sessionStorage.setItem('systemAlert', JSON.stringify({
        type: type,
        title: title,
        message: message,
        options: options
    }));
};

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = NotificationSystem;
}

// Estilos adicionales para la barra de progreso
const additionalStyles = `
.notification-progress {
    height: 3px;
    background: rgba(0,0,0,0.1);
    border-radius: 0 0 12px 12px;
    overflow: hidden;
    margin-top: 10px;
}

.notification-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-blue), var(--secondary-blue));
    animation: progressShrink linear forwards;
    transform-origin: left;
}

@keyframes progressShrink {
    from { transform: scaleX(1); }
    to { transform: scaleX(0); }
}

.notification.success .notification-progress-bar {
    background: linear-gradient(90deg, var(--success-green), #157347);
}

.notification.error .notification-progress-bar {
    background: linear-gradient(90deg, var(--danger-red), #c82333);
}

.notification.warning .notification-progress-bar {
    background: linear-gradient(90deg, var(--warning-orange), #e0a800);
}

.notification.info .notification-progress-bar {
    background: linear-gradient(90deg, var(--info-cyan), #0ba8d8);
}
`;

// Injectar estilos adicionales
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);