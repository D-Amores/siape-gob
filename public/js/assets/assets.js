// ------------------------------
// Modal dinámico por categoría
// ------------------------------
const categoriaSelect = document.getElementById('categoria');
const camposDinamicos = document.getElementById('camposDinamicos');

const camposPorCategoria = {
    computadora: `
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" class="form-control" id="procesador" placeholder="Procesador">
                    <label for="procesador">Procesador</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="velocidad" placeholder="Velocidad">
                    <label for="velocidad">Velocidad</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="memoria" placeholder="Memoria">
                    <label for="memoria">Memoria</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" class="form-control" id="almacenamiento" placeholder="Capacidad de almacenamiento">
                    <label for="almacenamiento">Capacidad de almacenamiento</label>
                </div>
            </div>
        </div>
    `,
    periferico: `
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" class="form-control" id="tipoPeriferico" placeholder="Tipo de periférico">
                    <label for="tipoPeriferico">Tipo de periférico</label>
                </div>
            </div>
        </div>
    `,
    mobiliario: `
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" class="form-control" id="material" placeholder="Material">
                    <label for="material">Material</label>
                </div>
            </div>
        </div>
    `
};

categoriaSelect.addEventListener('change', () => {
    const categoria = categoriaSelect.value;
    camposDinamicos.innerHTML = camposPorCategoria[categoria] || '';
});

// ------------------------------
// Inicialización profesional de DataTable
// ------------------------------
document.addEventListener('DOMContentLoaded', function () {
    // Destruir instancia previa si existe
    if ($.fn.DataTable.isDataTable('#file_export')) {
        $('#file_export').DataTable().clear().destroy();
    }

    const table = $('#file_export').DataTable({
        processing: true,
        serverSide: false, // Paginación en frontend
        responsive: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [[0, 'asc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        ajax: function (data, callback, settings) {
            fetch('/assets/api?option=table')
            .then(response => response.json())
            .then(json => {
                if (json.ok) callback({ data: json.data });
                else alert('Error al cargar los activos: ' + (json.message || 'Desconocido'));
            })
            .catch(error => {
                console.error('Error en la petición de assets:', error);
                alert('Error al cargar los activos. Revisa la consola.');
            });
        },
        columns: [
            { data: 'inventory_number', className: 'text-center' },
            { data: 'model' },
            { data: 'serial_number' },
            { data: 'brand.name' },
            { data: 'category.name' },
            {
                data: null,
                className: 'text-center',
                render: (data, type, row) => {
                    return row.personal_assets && row.personal_assets.length > 0
                    ? '<span class="badge bg-success">Asignado</span>'
                    : '<span class="badge bg-secondary">Disponible</span>';
                }
            },
            {
                data: null,
                className: 'text-center',
                orderable: false,
                render: (data, type, row) => `
                <!-- Ver (modal) -->
                <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalDetallesBien" data-bs-toggle="tooltip" title="Ver">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>`
            }
        ]
    });
});
