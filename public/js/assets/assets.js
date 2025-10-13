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

if (categoriaSelect) {
    categoriaSelect.addEventListener('change', () => {
        const categoria = categoriaSelect.value;
        camposDinamicos.innerHTML = camposPorCategoria[categoria] || '';
    });
}

// ------------------------------
// Inicialización profesional de DataTable
// ------------------------------
document.addEventListener('DOMContentLoaded', function () {

    // Destruir instancia previa si existe
    if ($.fn.DataTable.isDataTable('#file_export')) {
        $('#file_export').DataTable().clear().destroy();
    }

    // Cargar datos con Fetch API
    fetch('/assets/api?option=table')
    .then(response => response.json())
    .then(json => {
        if (!json.ok) {
            alert('Error al cargar los activos: ' + (json.message || 'Desconocido'));
            return;
        }

        // Inicializar DataTable con los datos obtenidos
        const table = $('#file_export').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'asc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            data: json.data, // Datos cargados via Fetch
            columns: [
                { data: 'inventory_number', className: 'text-center fw-medium' },
                { data: 'model', className: 'fw-normal' },
                { data: 'serial_number', className: 'fw-normal' },
                { data: 'brand.name', className: 'fw-normal' },
                { data: 'category.name', className: 'fw-normal' },
                {
                    data: 'is_active_label',
                    className: 'text-center',
                    render: function(data, type, row) {
                        // Determinar la clase del badge según el estado
                        const badgeClass = row.is_active ? 'bg-success' : 'bg-danger';
                        return `<span class="badge ${badgeClass} rounded-pill px-3 py-1">${data}</span>`;
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    orderable: false,
                    render: (data, type, row) => `
                    <button
                        class="btn btn-outline-info btn-sm mx-1 btn-ver"
                        title="Ver"
                        data-id="${row.id}"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDetallesBien">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm mx-1" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm mx-1" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>`
                }
            ]
        });
    })
    .catch(error => {
        console.error('Error en la petición de assets:', error);
        alert('Error al cargar los activos. Revisa la consola.');
    });

    // ------------------------------
    // Evento delegado: Ver detalles
    // ------------------------------
    const tableBody = document.querySelector('#file_export tbody');
    const modal = document.getElementById('modalDetallesBien');

    tableBody.addEventListener('click', async (event) => {
        const button = event.target.closest('.btn-ver');
        if (!button) return;

        const id = button.dataset.id;
        if (!id) return;

        // Limpia el modal antes de llenarlo
        modal.querySelectorAll('span[id^="detalle-"]').forEach(span => span.textContent = '...');
        modal.querySelector('#detalle-descripcion').textContent = '';

        try {
            const response = await fetch('/assets/api?option=details');
            const result = await response.json();

            if (!result.ok) {
                console.error('Error:', result.message);
                return;
            }

            // Busca el activo por ID
            const asset = result.data.find(a => a.id == id);
            if (!asset) {
                console.warn('No se encontró el activo con ID', id);
                return;
            }

            // Rellena los campos del modal
            modal.querySelector('#detalle-inventario').textContent = asset.inventory_number ?? '—';
            modal.querySelector('#detalle-modelo').textContent = asset.model ?? '—';
            modal.querySelector('#detalle-serie').textContent = asset.serial_number ?? '—';
            modal.querySelector('#detalle-marca').textContent = asset.brand?.name ?? '—';
            modal.querySelector('#detalle-categoria').textContent = asset.category?.name ?? '—';

            const estadoSpan = modal.querySelector('#detalle-estado');
            estadoSpan.textContent = asset.is_active_label;
            estadoSpan.classList.remove('bg-success', 'bg-danger');
            estadoSpan.classList.add(asset.is_active ? 'bg-success' : 'bg-danger');

            modal.querySelector('#detalle-cpu').textContent = asset.cpu ?? '—';
            modal.querySelector('#detalle-velocidad').textContent = asset.speed ?? '—';
            modal.querySelector('#detalle-memoria').textContent = asset.memory ?? '—';
            modal.querySelector('#detalle-almacenamiento').textContent = asset.storage ?? '—';
            modal.querySelector('#detalle-descripcion').textContent = asset.description ?? '—';

        } catch (error) {
            console.error('Error al cargar detalles:', error);
        }
    });
});
