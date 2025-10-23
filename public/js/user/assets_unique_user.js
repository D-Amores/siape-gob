document.addEventListener('DOMContentLoaded', async function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Definir columnas para la tabla
    const columns = [
        { data: 'inventory_number', className: 'text-center' },
        { data: 'model' },
        { data: 'serial_number' },
        { data: 'brand' },
        { data: 'category' },
        { data: 'status', className: 'text-center' },
        {
            data: 'id',
            className: 'text-center',
            render: function(id) {
                return `
                    <button class="btn btn-info btn-sm detalles-btn" data-id="${id}">
                        <i class="fas fa-eye me-1"></i> Ver
                    </button>
                `;
            }
        }
    ];

    // Cargar bienes del usuario
    async function loadAssetsUniqueUser() {
        try {
            const res = await fetch(vURIUniqueAssetsTableApi, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            });

            const data = await res.json();

            if (!data.ok) {
                showAlert(data.message, "red", "Error");
                bottomTableConfig('assets_unique_user', [], columns);
                return;
            }

            bottomTableConfig('assets_unique_user', data.data, columns, '.tooltipped');

        } catch (err) {
            showAlert('Error al cargar bienes del usuario', "red", "Error");
            bottomTableConfig('assets_unique_user', [], columns);
        }
    }

    await loadAssetsUniqueUser();
});
