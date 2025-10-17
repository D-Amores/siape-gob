document.addEventListener('DOMContentLoaded', async function () {
    const table = $('#assets_unique_user').DataTable({
        data: [],
        columns: [
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
        ],
        language: { url: language },
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Cargar bienes del usuario
    async function loadAssetsUniqueUser() {
        try {
            const res = await fetch('/assets-unique-user/api', {
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
                console.error(data.message);
                table.clear().draw();
                return;
            }

            table.clear();
            table.rows.add(data.data);
            table.draw();

        } catch (err) {
            console.error('Error al cargar bienes del usuario', err);
            table.clear().draw();
        }
    }

    await loadAssetsUniqueUser();
});
