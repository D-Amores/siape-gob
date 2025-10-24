function loadAssetsAccepted(assetPendings) {
    const tableId = "accepted_assignments";

    const columns = [
        { 
            data: 'id', 
            title: '#', 
            className: 'text-center', 
            render: (data, type, row, meta) => meta.row + 1 // contador visual
        },
        { 
            data: 'asset.inventory_number', 
            title: 'Activo', 
            className: 'text-center',
            render: (data, type, row) => {
                const model = row.asset?.model ?? '—';
                return `<strong>${data} - </strong><br><small>${model}</small>`;
            }
        },
        { 
            data: 'asset.category', 
            title: 'Categoría', 
            className: 'text-center'
        },
        { 
            data: 'asset.brand', 
            title: 'Marca', 
            className: 'text-center'
        },
        { 
            data: 'assigner', 
            title: 'Asignado por', 
            className: 'text-center'
        },
        { 
            data: 'receiver.name', 
            title: 'Asignado a', 
            className: 'text-center',
        },
        { 
            data: 'receiver.area', 
            title: 'Con Área', 
            className: 'text-center',
        },
        { 
            data: 'assignment_date', 
            title: 'Fecha Asignación', 
            className: 'text-center'
        },
        { 
            data: 'confirmation_date', 
            title: 'Fecha Aceptación', 
            className: 'text-center'
        },
        { 
            data: 'path_acceptance_doc', 
            title: 'Documento', 
            className: 'text-center',
            render: (data) => {
                if (!data) return '<span class="text-muted">Sin documento</span>';
                return `
                    <a href="${data}" target="_blank" 
                       class="btn btn-sm btn-primary" 
                       data-bs-toggle="tooltip" 
                       data-bs-title="Ver documento">
                        <i class="fas fa-file-pdf"></i>
                    </a>`;
            }
        }
    ];

    // Inicializa la DataTable
    bottomTableConfig(tableId, assetPendings, columns, '[data-bs-toggle="tooltip"]');
}
