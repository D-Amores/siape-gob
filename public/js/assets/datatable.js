// ------------------------------
// DOMContentLoaded
// ------------------------------
document.addEventListener('DOMContentLoaded', function () {
    // ------------------------------
    // Inicialización de DataTable
    // ------------------------------
    fetch(vURIAssetsTableApi, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ option: 'table' })
    })
    .then(response => response.json())
    .then(json => {
        if (!json.ok) {
            showAlert('Error al cargar los activos: ' + (json.message || 'Desconocido'), "red", "Error");
            return;
        }

        const columns = [
            { data: 'inventory_number', className: 'text-center fw-medium', title: 'N° Inventario' },
            { data: 'model', className: 'fw-normal', title: 'Modelo' },
            { data: 'serial_number', className: 'fw-normal', title: 'N° Serie' },
            { data: 'brand.name', className: 'fw-normal', title: 'Marca' },
            { data: 'category.name', className: 'fw-normal', title: 'Categoría' },
            { data: 'status.name', className: 'fw-normal', title: 'Condición' },
            {
                data: 'is_active_label',
                className: 'text-center',
                title: 'Estado',
                render: function (data, type, row) {
                    const badgeClass = row.is_active ? 'bg-success' : 'bg-danger';
                    return `<span class="badge ${badgeClass} rounded-pill px-3 py-1">${data}</span>`;
                }
            },
            {
                data: null,
                className: 'text-center',
                orderable: false,
                title: 'Acciones',
                render: (data, type, row) => `
                    <button
                        class="btn btn-outline-info btn-sm mx-1 btn-ver"
                        title="Ver"
                        data-id="${row.id}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm mx-1 btn-modal-bien"
                        data-mode="edit"
                        data-id="${row.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button
                        class="btn btn-outline-danger btn-sm mx-1 btn-delete-asset"
                        data-id="${row.id}"
                        title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                `
            }
        ];

        bottomTableConfig('file_export', json.data, columns, '[title]');
    })
    .catch(error => {
        showAlert('Error al cargar los activos: ' + error.message, "red", "Error de conexión");
    });
});
