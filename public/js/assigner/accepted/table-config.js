function loadAssignmentsList(assignments) {
    const tableId = "details-table";

    const columns = [
        { 
            data: 'id', 
            title: '#', 
            className: 'text-center', 
            render: (data, type, row, meta) => meta.row + 1 // contador visual
        },
        { 
            data: 'asset.asset_name', 
            title: 'Bien', 
            className: 'text-center',
        },
        { 
            data: 'asset.status', 
            title: 'Estado', 
            className: 'text-center'
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
            render: (data, type, row) => {
                let botones = '';

                if (!data) {
                    botones += '<span class="text-muted">Sin documento</span>';
                } else {
                    botones += `
                        <a href="${data}" target="_blank" 
                        class="btn btn-sm btn-outline-primary border-0 me-1" 
                        data-bs-toggle="tooltip" 
                        data-bs-title="Ver documento">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    `;
                }

                // Botón de desasignar
                botones += `
                    <button class="btn btn-sm btn-outline-danger border-0 btn-unassign" data-id="${row.id}" 
                            data-bs-toggle="tooltip" 
                            data-bs-title="Desasignar">
                        <i class="fas fa-times"></i>
                    </button>
                `;

                return botones;
            }
        }

    ];

    // Inicializa la DataTable
    basicTableConfigWithoutPaging(tableId, assignments, columns, '[data-bs-toggle="tooltip"]');
}

function loadAssetsAcceptedBasicInfo(data) {
    const tableId = "accepted-assignments-names";
    const columns = [
        { data: 'id', title: '#', className: 'text-center', render: (data, type, row, meta) => meta.row + 1 },
        { data: 'receiver_name', title: 'Asignado a', className: 'text-center' },
        { data: 'receiver_area', title: 'Con Área', className: 'text-center' },
        { data: 'assignments_count', title: 'Asignaciones', className: 'text-center' },
        { data: 'assigner_name', title: 'Asignado por', className: 'text-center' },
        { data: 'id', title: 'Acciones', className: 'text-center', render: (data, type, row) => {
            return `
                <button class="btn btn-sm btn-outline-primary border-0 btn-details" data-id="${data}" 
                        data-bs-toggle="tooltip" 
                        data-bs-title="Detalles">
                    <i class="fas fa-info-circle"></i>
                </button>
            `;
            } 
        }

    ];

    // Inicializa la DataTable
    basicTableConfig(tableId, data, columns, '[data-bs-toggle="tooltip"]');
}
