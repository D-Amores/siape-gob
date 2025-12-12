function loadReportsTable(data) {
    const tableSelector = "reports-table";

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
                    <button class="btn btn-create btn-sm ${row.is_closed ? 'btn-outline-secondary' : 'btn-outline-primary'} border-0 me-1 d-inline-flex align-items-center" 
                        data-id="${data}" 
                        data-bs-toggle="tooltip"
                        ${row.is_closed ? 'disabled' : ''} 
                        data-bs-title="Dar seguimiento">
                        <span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                `;
            }
        }
    ];
    const tooltips = '[data-bs-toggle="tooltip"]';

    basicTableConfig(tableSelector, data, columns, tooltips);
}
