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
            data: 'asset.asset_name', 
            title: 'Bien', 
            className: 'text-center',
        },
        { 
            data: 'asset.status', 
            title: 'Estado', 
            className: 'text-center'
        },
        // { 
        //     data: 'asset.brand', 
        //     title: 'Marca', 
        //     className: 'text-center'
        // },
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
    bottomTableConfig(tableId, assetPendings, columns, '[data-bs-toggle="tooltip"]');
}
