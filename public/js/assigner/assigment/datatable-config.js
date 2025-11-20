function loadAssetsPending(assetPendings) {
    const tableId = "file_export";

    // Definimos las columnas que van a recibir los datos
    const columns = [
        {
            data: null,
            title: "#",
            className: "text-center",
            render: (d, t, r, meta) => meta.row + 1
        },
        {
            data: "receiver_name",
            title: "Recibe",
            className: "text-center",
            render: (name) => `
                <div class="text-center text-truncate" style="max-width:140px;" title="${name ?? '—'}" data-bs-toggle="tooltip">
                    ${name ?? '—'}
                </div>
            `
        },
        {
            data: "asset_name",
            title: "Bien",
            className: "text-center",
        },
        {
            data: "assigner_name",
            title: "Asigna",
            className: "text-center",
            render: (name) => `
                <div class="text-center text-truncate" style="max-width:140px;" title="${name ?? '—'}" data-bs-toggle="tooltip">
                    ${name ?? '—'}
                </div>
            `
        },
        {
            data: null,
            title: "Acciones",
            className: "text-center",
            render: (row) => `
                <div class="d-flex justify-content-center gap-2">
                    <button type="button"
                            class="btn btn-outline-danger border-0 btn-delete"
                            data-id="${row.id}"
                            title="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `
        }
    ];

    // Llamada a tu función moderna
    bottomTableConfig(tableId, assetPendings, columns, '[data-bs-toggle="tooltip"]');
}
