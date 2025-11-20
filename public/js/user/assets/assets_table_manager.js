// assets_table_manager.js - Maneja la configuración y carga de la tabla de bienes
const AssetsTableManager = (function() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Definir columnas para la tabla
    const columns = [
        { 
            data: 'inventory_number', 
            className: 'text-center',
            render: function(data, type, row) {
                return data; 
            }
        },
        { 
            data: 'model',
            render: function(data, type, row) {
                return data; 
            }
        },
        { 
            data: 'serial_number',
            render: function(data, type, row) {
                return data; 
            }
        },
        { 
            data: 'brand',
            render: function(data, type, row) {
                return data; 
            }
        },
        { 
            data: 'category',
            render: function(data, type, row) {
                return data; 
            }
        },
        { 
            data: 'status', 
            className: 'text-center',
            render: function(data, type, row) {
                const badgeClass = data === 'Activo' ? 'bg-success' : 'bg-secondary';
                return `<span class="badge ${badgeClass}">${data}</span>`;
            }
        },
        {
            data: 'id',
            className: 'text-center',
            render: function(data, type, row) {
                return `
                    <button class="btn btn-info btn-sm detalles-btn me-1" data-id="${data}">
                        <i class="fas fa-eye me-1"></i> Ver
                    </button>
                    <button class="btn btn-warning btn-sm reportar-btn" 
                        data-id="${data}" 
                        data-inventory="${row.inventory_number}">
                        <i class="fas fa-flag me-1"></i> Reportar
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

    return {
        init: loadAssetsUniqueUser
    };
})();