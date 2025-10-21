<?php
$pageTitle = 'API Mototaxis Huanta - Buscar Mototaxis';
include __DIR__ . '/../layouts/header_public.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Sección de Búsqueda -->
            <div id="busqueda" class="card shadow-lg mb-5 fade-in-up">
                <div class="card-header">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-search me-2"></i>Buscar Mototaxi
                    </h4>
                </div>
                <div class="card-body p-4">
                    <!-- Token de Acceso -->
                    <div class="row mb-4">
                        <div class="col-10">
                            <label class="form-label">
                                <i class="bi bi-key me-2"></i>Token de Acceso
                            </label>
                            <input type="text" class="form-control" id="accessToken" 
                                   placeholder="Ingrese su token de acceso API" 
                                   value="bd267203d8f3bd793b969731a03b0c135b8394e36b1fe1047415b7db5c216ed5">
                            <div class="form-text text-light opacity-75">
                                Token requerido para autenticar las consultas a la API.
                            </div>
                        </div>
                        <div class="col-2 d-flex align-items-end">
                            <button class="btn btn-outline-info w-100" onclick="validarToken()" title="Validar Token">
                                <i class="bi bi-check-circle"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Opciones de Búsqueda -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tipo de Búsqueda</label>
                            <select class="form-select" id="searchType">
                                <option value="numero">Por Número Asignado</option>
                                <option value="dni">Por DNI</option>
                                <option value="todos">Listar Todos</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label" id="searchLabel">Número Asignado</label>
                            <input type="text" class="form-control" id="searchTerm" 
                                   placeholder="Ej: 01, 02, 03...">
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-success w-100" onclick="realizarBusqueda()">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                        </div>
                    </div>

                    <!-- Paginación (solo para listar todos) -->
                    <div class="row mt-3" id="paginationSection" style="display: none;">
                        <div class="col-md-3">
                            <label class="form-label">Página</label>
                            <input type="number" class="form-control" id="pageNumber" value="1" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Por Página</label>
                            <select class="form-select" id="perPage">
                                <option value="5">5 registros</option>
                                <option value="10" selected>10 registros</option>
                                <option value="20">20 registros</option>
                                <option value="50">50 registros</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados -->
            <div class="card shadow-lg mb-5">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-list-ul me-2"></i>Resultados
                    </h5>
                    <div>
                        <button class="btn btn-outline-light btn-sm me-2" onclick="copiarJSON()">
                            <i class="bi bi-clipboard me-1"></i>Copiar JSON
                        </button>
                        <button class="btn btn-outline-light btn-sm" onclick="limpiarResultados()">
                            <i class="bi bi-x-circle me-1"></i>Limpiar
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- Loading -->
                    <div id="loading" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Buscando mototaxis...</p>
                    </div>

                    <!-- Error -->
                    <div id="errorMessage" class="alert alert-danger" style="display: none;">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <span id="errorText"></span>
                    </div>

                    <!-- Resultados en JSON -->
                    <div id="jsonResults" style="display: none;">
                        <h6 class="text-primary mb-3">Respuesta JSON:</h6>
                        <pre class="bg-dark text-light border rounded p-3" 
                             style="min-height: 200px; max-height: 400px; overflow-y: auto; font-size: 0.875rem;"
                             id="jsonOutput"></pre>
                    </div>

                    <!-- Vista de Tarjetas -->
                    <div id="cardResults" class="row g-4"></div>

                    <!-- Sin resultados -->
                    <div id="noResults" class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h5 class="text-muted mt-3">No hay resultados</h5>
                        <p class="text-muted">Realice una búsqueda para ver los mototaxis</p>
                    </div>

                    <!-- Paginación Resultados -->
                    <div id="resultsPagination" class="d-flex justify-content-between align-items-center mt-4" style="display: none;">
                        <div class="text-muted" id="paginationInfo"></div>
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" id="prevPage" onclick="cambiarPagina(-1)">
                                <i class="bi bi-chevron-left"></i> Anterior
                            </button>
                            <button class="btn btn-outline-primary btn-sm" id="nextPage" onclick="cambiarPagina(1)">
                                Siguiente <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales para paginación
let currentPage = 1;
let totalPages = 1;
let currentSearchType = '';

// Cambiar tipo de búsqueda
document.getElementById('searchType').addEventListener('change', function() {
    const searchType = this.value;
    const searchLabel = document.getElementById('searchLabel');
    const searchTerm = document.getElementById('searchTerm');
    const paginationSection = document.getElementById('paginationSection');
    
    currentSearchType = searchType;
    
    switch(searchType) {
        case 'numero':
            searchLabel.textContent = 'Número Asignado';
            searchTerm.placeholder = 'Ej: 01, 02, 03...';
            paginationSection.style.display = 'none';
            break;
        case 'dni':
            searchLabel.textContent = 'DNI del Conductor';
            searchTerm.placeholder = 'Ej: 72358506, 28563855...';
            paginationSection.style.display = 'none';
            break;
        case 'todos':
            searchLabel.textContent = 'Listar Todos los Mototaxis';
            searchTerm.placeholder = 'No se requiere término de búsqueda';
            paginationSection.style.display = 'flex';
            break;
    }
});

// Realizar búsqueda
function realizarBusqueda() {
    const token = document.getElementById('accessToken').value.trim();
    const searchType = document.getElementById('searchType').value;
    const searchTerm = document.getElementById('searchTerm').value.trim();
    
    if (!token) {
        mostrarError('Por favor, ingrese un token de acceso válido');
        return;
    }
    
    if (searchType !== 'todos' && !searchTerm) {
        mostrarError('Por favor, ingrese un término de búsqueda');
        return;
    }
    
    currentPage = searchType === 'todos' ? parseInt(document.getElementById('pageNumber').value) : 1;
    const perPage = searchType === 'todos' ? document.getElementById('perPage').value : 10;
    
    mostrarCarga(true);
    ocultarError();
    ocultarResultados();
    
    let url = '';
    let params = '';
    
    switch(searchType) {
        case 'numero':
            params = `numero=${encodeURIComponent(searchTerm)}`;
            url = `api.php?action=buscar&${params}`;
            break;
        case 'dni':
            params = `dni=${encodeURIComponent(searchTerm)}`;
            url = `api.php?action=buscarDni&${params}`;
            break;
        case 'todos':
            params = `pagina=${currentPage}&por_pagina=${perPage}`;
            url = `api.php?action=listar&${params}`;
            break;
    }
    
    console.log('URL:', url); // Para debug
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status); // Para debug
        if (!response.ok) {
            // Si es error 401, mostrar mensaje específico
            if (response.status === 401) {
                throw new Error('Token inválido o no autorizado');
            } else if (response.status === 500) {
                throw new Error('Error interno del servidor');
            } else if (response.status === 404) {
                throw new Error('Recurso no encontrado');
            } else if (response.status === 400) {
                throw new Error('Solicitud incorrecta - verifique los parámetros');
            }
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Data recibida:', data); // Para debug
        mostrarCarga(false);
        if (data.success) {
            mostrarResultadosJSON(data);
            if (searchType === 'todos' && data.paginacion) {
                mostrarPaginacion(data.paginacion);
            }
        } else {
            mostrarError(data.message);
        }
    })
    .catch(error => {
        console.error('Error completo:', error); // Para debug
        mostrarCarga(false);
        mostrarError('Error: ' + error.message);
    });
}

// Función para validar token
function validarToken() {
    const token = document.getElementById('accessToken').value.trim();
    
    if (!token) {
        mostrarError('Por favor, ingrese un token de acceso válido');
        return;
    }
    
    mostrarCarga(true);
    ocultarError();
    ocultarResultados();
    
    fetch('api.php?action=validar', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                throw new Error('Token inválido o no autorizado');
            }
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        mostrarCarga(false);
        if (data.success) {
            mostrarResultadosJSON(data);
            // Mostrar información del cliente en una alerta
            if (data.data && data.data.cliente) {
                const cliente = data.data.cliente;
                mostrarAlertaExito(`✅ Token válido - Cliente: ${cliente.razon_social} (RUC: ${cliente.ruc})`);
            }
        } else {
            mostrarError(data.message);
        }
    })
    .catch(error => {
        mostrarCarga(false);
        mostrarError('Error de conexión: ' + error.message);
    });
}

// Mostrar alerta de éxito
function mostrarAlertaExito(mensaje) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
    
    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Cambiar página
function cambiarPagina(direction) {
    if (currentSearchType === 'todos') {
        const newPage = currentPage + direction;
        if (newPage >= 1 && newPage <= totalPages) {
            document.getElementById('pageNumber').value = newPage;
            currentPage = newPage;
            realizarBusqueda();
        }
    }
}

// Mostrar paginación
function mostrarPaginacion(paginacion) {
    if (paginacion) {
        totalPages = paginacion.total_paginas;
        currentPage = paginacion.pagina_actual;
        
        document.getElementById('paginationInfo').textContent = 
            `Página ${currentPage} de ${totalPages} - ${paginacion.total_registros} registros`;
        
        document.getElementById('prevPage').disabled = currentPage <= 1;
        document.getElementById('nextPage').disabled = currentPage >= totalPages;
        
        document.getElementById('resultsPagination').style.display = 'flex';
    }
}

// Mostrar resultados en JSON
function mostrarResultadosJSON(data) {
    document.getElementById('jsonOutput').textContent = JSON.stringify(data, null, 2);
    document.getElementById('jsonResults').style.display = 'block';
    document.getElementById('noResults').style.display = 'none';
    
    // También mostrar en tarjetas si hay datos
    if (data.data && (Array.isArray(data.data) ? data.data.length > 0 : true)) {
        mostrarTarjetas(data.data);
    }
}

// Mostrar tarjetas de mototaxis
function mostrarTarjetas(mototaxis) {
    const container = document.getElementById('cardResults');
    container.innerHTML = '';
    
    const datos = Array.isArray(mototaxis) ? mototaxis : [mototaxis];
    
    if (datos.length === 0) {
        document.getElementById('noResults').style.display = 'block';
        return;
    }
    
    datos.forEach(mototaxi => {
        const card = document.createElement('div');
        card.className = 'col-md-6 col-lg-4';
        card.innerHTML = `
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-motorcycle me-2"></i>${mototaxi.numero_asignado}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mototaxi-info">
                        <h6 class="text-primary">${mototaxi.nombre_completo}</h6>
                        <p class="mb-1"><strong>DNI:</strong> ${mototaxi.dni}</p>
                        <p class="mb-1"><strong>Placa:</strong> ${mototaxi.placa_rodaje}</p>
                        <p class="mb-1"><strong>Marca:</strong> ${mototaxi.marca || 'N/A'}</p>
                        <p class="mb-1"><strong>Color:</strong> ${mototaxi.color || 'N/A'}</p>
                        <p class="mb-0"><strong>Empresa:</strong> ${mototaxi.empresa || 'N/A'}</p>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(card);
    });
    
    document.getElementById('cardResults').style.display = 'block';
}

// Utilidades de UI
function mostrarCarga(mostrar) {
    document.getElementById('loading').style.display = mostrar ? 'block' : 'none';
}

function ocultarResultados() {
    document.getElementById('jsonResults').style.display = 'none';
    document.getElementById('cardResults').style.display = 'none';
    document.getElementById('noResults').style.display = 'none';
    document.getElementById('resultsPagination').style.display = 'none';
}

function mostrarError(mensaje) {
    const errorDiv = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
    errorText.textContent = mensaje;
    errorDiv.style.display = 'block';
    document.getElementById('noResults').style.display = 'block';
}

function ocultarError() {
    document.getElementById('errorMessage').style.display = 'none';
}

function copiarJSON() {
    const jsonText = document.getElementById('jsonOutput').textContent;
    if (!jsonText) {
        alert('No hay resultados para copiar');
        return;
    }
    
    navigator.clipboard.writeText(jsonText)
        .then(() => {
            // Mostrar notificación de éxito
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>JSON copiado al portapapeles
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 3000);
        })
        .catch(err => {
            alert('❌ Error al copiar: ' + err);
        });
}

function limpiarResultados() {
    document.getElementById('jsonOutput').textContent = '';
    document.getElementById('cardResults').innerHTML = '';
    ocultarResultados();
    document.getElementById('noResults').style.display = 'block';
    ocultarError();
}

// Enter para buscar
document.getElementById('searchTerm').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        realizarBusqueda();
    }
});

// Enter en el campo de página
document.getElementById('pageNumber').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        realizarBusqueda();
    }
});

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    // Trigger change event para inicializar labels
    document.getElementById('searchType').dispatchEvent(new Event('change'));
    
    // Auto-focus en el campo de token
    document.getElementById('accessToken').focus();
});
</script>

<?php include __DIR__ . '/../layouts/footer_public.php'; ?>