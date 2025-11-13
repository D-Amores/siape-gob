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
                const statusClass = getStatusClass(data);
                return `<span class="badge ${statusClass}">${data}</span>`;
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
    const statusMap = {
        'Pendiente': 'bg-warning text-dark',
        'En Proceso': 'bg-info text-white',
        'Completado': 'bg-success text-white',
        'Cancelado': 'bg-danger text-white',
        'Cerrado': 'bg-secondary text-white'
    };
    
    return statusMap[status] || 'bg-light text-dark';
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

// Función para manejar el evento de ver detalles
function handleViewReport(reportId) {
    openModalForEdit('logsModal');
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