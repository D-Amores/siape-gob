// Variable global para almacenar la instancia de DataTable
let reportsTable = null;
// Variable global para almacenar los reportes actuales
let currentReports = [];

// Función para cargar y mostrar los reportes
async function loadUserReports() {
    try {
        const response = await fetch(URIUserReports, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        });

        const result = await response.json();

        if (result.success) {
            currentReports = result.data;
            renderReportsTable(result.data);
        } else {
            showAlert('Error al cargar los reportes: ' + result.message, 'red', 'Error');
        }
    } catch (error) {
        showAlert('Error de conexión al cargar los reportes', 'red', 'Error');
    }
}

// Función para formatear fechas
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-MX', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
}

// Función para truncar texto largo
function truncateText(text, maxLength = 50) {
    if (!text) return '';
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

// Función para renderizar la tabla con DataTable
function renderReportsTable(reportsData) {
    const tableData = reportsData.map(report => {
        const assetName = report.asset && report.asset.asset_name ? report.asset.asset_name : 'N/A';
        const statusName = report.status && report.status.name ? report.status.name : 'N/A';
    
    return {
        folio: report.folio || 'N/A',
        asset: assetName,          
        status: statusName,        
        description: report.description || 'Sin descripción',
        reported_at: formatDate(report.reported_at),
        closed_at: formatDate(report.closed_at),
        actions: generateActionButtons(report)
    };
});

    const columns = [
        { 
            data: 'folio',
            className: 'text-center'
        },
        { data: 'asset' },
        {  
            data: 'status',
            render: function(data, type, row) {
                // Usar siempre bg-secondary para todos los estados
                return `<span class="badge bg-secondary">${data}</span>`;
            }
        },
        { 
            data: 'description',
            render: function(data) {
                return `<span title="${data}">${truncateText(data)}</span>`;
            }
        },
        { data: 'reported_at' },
        { data: 'closed_at' },
        { 
            data: 'actions',
            className: 'text-center',
            orderable: false,
            searchable: false
        }
    ];

    try {
        reportsTable = bottomTableConfig('reportsTable', tableData, columns, '[data-bs-toggle="tooltip"]');
    } catch (error) {
        
        // Intentar inicializar DataTable directamente como fallback
        try {
            reportsTable = $('#reportsTable').DataTable({
                data: tableData,
                columns: columns,
                language: languageDataTable || {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                }
            });
        } catch (fallbackError) {
        }
    }
}

// Función para generar las clases CSS según el estado
function getStatusClass(status) {
    console.log('getStatusClass recibió:', status);
    
    // Mapeo completo de estados con sus clases correspondientes
    const statusMap = {
        'Nuevo': 'bg-success text-white',
        'Pendiente': 'bg-warning text-dark',
        'En proceso': 'bg-info text-white',
        'En Proceso': 'bg-info text-white', // Por si viene con mayúscula
        'Completado': 'bg-primary text-white',
        'Finalizado': 'bg-success text-white',
        'Cancelado': 'bg-danger text-white',
        'Cerrado': 'bg-secondary text-white',
        'Disponible': 'bg-success text-white',
        'En mantenimiento': 'bg-warning text-dark',
        'Dañado': 'bg-danger text-white',
        'Fuera de servicio': 'bg-dark text-white'
    };
    
    // Buscar coincidencia exacta primero
    if (statusMap[status]) {
        return statusMap[status];
    }
    
    // Buscar coincidencia insensible a mayúsculas/minúsculas
    const normalizedStatus = Object.keys(statusMap).find(key => 
        key.toLowerCase() === status.toLowerCase()
    );
    
    return normalizedStatus ? statusMap[normalizedStatus] : 'bg-light text-dark';
}

// Función para generar los botones de acción
function generateActionButtons(report) {
    return `
        <div class="btn-group" role="group">
            <button type="button" 
                    class="btn btn-sm btn-outline-primary view-report-btn"
                    data-bs-toggle="tooltip"
                    data-bs-title="Ver detalles"
                    data-report-id="${report.id}">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    `;
}

function getAssetStatusClass(isActive) {
    const statusClass = isActive ? 'bg-success text-white' : 'bg-danger text-white';
    return statusClass;
}

function getStatusClass(status) {
    const statusMap = {
        'Nuevo': 'bg-success text-white',
        'Pendiente': 'bg-warning text-dark',
        'En Proceso': 'bg-info text-white',
        'Completado': 'bg-success text-white',
        'Cancelado': 'bg-danger text-white',
        'Cerrado': 'bg-secondary text-white'
    };
    
    const result = statusMap[status] || 'bg-light text-dark';
    return result;
}

function populateLogsModal(report) {
    // Información del Reporte
    document.getElementById('log-folio').textContent = report.folio || '—';
    document.getElementById('log-status').textContent = report.status?.name || '—';
    document.getElementById('log-status').className = `badge rounded-pill px-3 py-2 bg-secondary`;
    document.getElementById('log-reported-at').textContent = formatDate(report.reported_at) || '—';
    document.getElementById('log-closed-at').textContent = formatDate(report.closed_at) || '—';
    document.getElementById('log-description').textContent = report.description || '—';

    // Información del Bien
    const asset = report.asset;
    if (asset) {
        document.getElementById('log-asset-inventory').textContent = asset.inventory_number || '—';
        document.getElementById('log-asset-model').textContent = asset.model || '—';
        document.getElementById('log-asset-serial').textContent = asset.serial_number || '—';
        document.getElementById('log-asset-brand').textContent = asset.brand_name || '—';
        document.getElementById('log-asset-category').textContent = asset.category_name || '—';
        
        // Mejor manejo del estado del activo
        const assetStatus = asset.status_name || (asset.is_active ? 'Activo' : 'Inactivo');
        document.getElementById('log-asset-status').textContent = assetStatus;
        document.getElementById('log-asset-status').className = `badge rounded-pill px-3 py-2 ${getAssetStatusClass(assetStatus)}`;
    } else {
        // Si no hay asset, limpiar los campos
        document.getElementById('log-asset-inventory').textContent = '—';
        document.getElementById('log-asset-model').textContent = '—';
        document.getElementById('log-asset-serial').textContent = '—';
        document.getElementById('log-asset-brand').textContent = '—';
        document.getElementById('log-asset-category').textContent = '—';
        document.getElementById('log-asset-status').textContent = '—';
        document.getElementById('log-asset-status').className = 'badge rounded-pill px-3 py-2 bg-secondary';
    }

    // Historial de Logs
    const logsContainer = document.getElementById('logs-container');
    
    if (report.logs && report.logs.length > 0) {
        logsContainer.innerHTML = generateLogsHTML(report.logs);
    } else {
        logsContainer.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="fas fa-history fa-2x mb-2"></i>
                <p>No hay actividades registradas</p>
            </div>
        `;
    }
}

// Función para generar el HTML de los logs
function generateLogsHTML(logs) {
    
    // Ordenar logs por fecha de creación (más reciente primero)
    const sortedLogs = [...logs].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    
    let logsHTML = '';
    sortedLogs.forEach((log, index) => {
        
        const personnelName = log.personnel ? 
            `${log.personnel.name} ${log.personnel.last_name}` : 'Usuario desconocido';
        const actionText = getActionText(log.action);
        const comment = log.comment || 'Sin comentario';

        logsHTML += `
            <div class="timeline-item mb-3">
                <div class="d-flex">
                    <div class="timeline-marker flex-shrink-0 me-3">
                        <i class="fas fa-circle text-primary"></i>
                    </div>
                    <div class="timeline-content flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-">${actionText}</h6>
                            <small class="text-muted">${formatDate(log.created_at)}</small>
                        </div>
                        <p class="mb-1"><strong>Por:</strong> ${personnelName}</p>
                        <p class="text-muted small mb-0"><strong>Comentario:</strong> ${comment}</p>
                    </div>
                </div>
            </div>
        `;
    });

    return logsHTML;
}

// Función para obtener el texto legible de la acción
function getActionText(action) {
    
    const actionMap = {
        'created': 'Reporte creado',
        'Creación de reporte': 'Reporte creado',
        'updated': 'Reporte actualizado',
        'assigned': 'Reporte asignado',
        'in_progress': 'En progreso',
        'resolved': 'Resuelto',
        'closed': 'Reporte cerrado',
        'Nuevo': 'Reporte creado'
    };
    
    const result = actionMap[action] || action;
    return result;
}

// Función para manejar el evento de ver detalles
function handleViewReport(reportId) {
    
    const report = currentReports.find(r => r.id == reportId);
    
    if (report) {
        populateLogsModal(report);
        openModalForEdit('logsModal');
    } else {
        showAlert('No se pudo encontrar el reporte seleccionado', 'red', 'Error');
    }
}

// Event listeners para los botones de acción
document.addEventListener('click', function(e) {
    if (e.target.closest('.view-report-btn')) {
        const reportId = e.target.closest('.view-report-btn').getAttribute('data-report-id');
        handleViewReport(reportId);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    loadUserReports();
});

// Función para recargar los reportes 
function reloadReports() {
    if (reportsTable) {
        reportsTable.destroy();
    }
    loadUserReports();
}