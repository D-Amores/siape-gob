function loadReportsTable(data) {
    const tableSelector = "tracking-table";

    const columns = [
        { 
            data: 'id', 
            title: '#', 
            className: 'text-center', 
            render: (data, type, row, meta) => meta.row + 1 // contador visual
        },
        { 
            data: 'folio', 
            title: 'Folio', 
            className: 'text-center fw-semibold'
        },
        { 
            data: 'asset', 
            title: 'Bien', 
            className: 'text-center',
            render: (data) => data ?? '—'
        },
        { 
            data: 'description', 
            title: 'Descripción', 
            className: 'text-center text-wrap',
            render: (data) => data ?? 'Sin descripción'
        },
        { 
            data: 'status', 
            title: 'Estado', 
            className: 'text-center',
            render: (data) => {                
                let badgeClass = 'secondary';
                return `<span class="badge bg-${badgeClass}">${data ?? '—'}</span>`;
            }
        },
        { 
            data: 'reported_by', 
            title: 'Reportado por', 
            className: 'text-center',
            render: (data) => data ?? '—'
        },
        { 
            data: 'reported_at', 
            title: 'Fecha de reporte', 
            className: 'text-center',
            render: (data) => data ?? '—'
        },
        { 
            data: 'id', 
            title: 'Acciones', 
            className: 'text-center',
            render: (data, type, row) => {
                return `
                    <button class="btn btn-edit btn-sm btn-outline-secondary border-0 me-1 d-inline-flex align-items-center" 
                        data-id="${data}" 
                        data-bs-toggle="tooltip" 
                        data-bs-title="Actualizar seguimiento">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-chk btn-sm btn-outline-primary border-0 me-1 d-inline-flex align-items-center" 
                        data-id="${data}" 
                        data-bs-toggle="tooltip" 
                        data-bs-title="Finalizar seguimiento">
                        <i class="fas fa-check"></i>
                    </button>

                `;
            }
        }
    ];
    const tooltips = '[data-bs-toggle="tooltip"]';

    basicTableConfig(tableSelector, data, columns, tooltips);
}
