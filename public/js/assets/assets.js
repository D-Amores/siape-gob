// ------------------------------
// Modal dinámico por categoría
// ------------------------------
const categoriaSelect = document.getElementById('categoria');
const camposDinamicos = document.getElementById('camposDinamicos');
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const camposGenericos = `
    <h6 class="text-uppercase text-secondary fw-semibold mb-3">
        <i class="fas fa-cogs me-2"></i>Detalles Específicos
    </h6>
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
        <div class="col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" id="almacenamiento" placeholder="Capacidad de almacenamiento">
                <label for="almacenamiento">Capacidad de almacenamiento</label>
            </div>
        </div>
    </div>
`


const categoriasConCamposGenericos = ['1', '2', '5'];

if (categoriaSelect) {
    categoriaSelect.addEventListener('change', () => {
        const categoria = categoriaSelect.value;

        if (categoriasConCamposGenericos.includes(categoria)) {
            camposDinamicos.innerHTML = camposGenericos;
        } else {
            camposDinamicos.innerHTML = camposPorCategoria[categoria] || '';
        }
    });
}

// ------------------------------
// Inicialización de DataTable
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
            data: json.data,
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
                        <button
                            class="btn btn-outline-danger btn-sm mx-1 btn-delete-asset"
                            data-id="${row.id}"
                            title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    `
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

            const asset = result.data.find(a => a.id == id);
            if (!asset) {
                console.warn('No se encontró el activo con ID', id);
                return;
            }

            // --- Función para formatear fechas ---
            function formatDate(isoString) {
                if (!isoString) return '—';
                const date = new Date(isoString);
                return date.toLocaleString('es-MX', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                });
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

            modal.querySelector('#detalle-creado').textContent = formatDate(asset.created_at);
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


// ------------------------------
// Guardar nuevo bien
// ------------------------------
const formNuevoBien = document.getElementById('formNuevoBien');

if (formNuevoBien) {
    formNuevoBien.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Recolectar datos del formulario
        const data = {
            inventory_number: document.getElementById('numeroInventario').value.trim(),
            brand_id: document.getElementById('marca').value,
            model: document.getElementById('modelo').value.trim(),
            serial_number: document.getElementById('serie').value.trim(),
            category_id: document.getElementById('categoria').value,
            description: document.getElementById('descripcion').value.trim(),
            is_active: document.getElementById('estado').value === '1'
        };

        // Campos dinámicos por categoría
        const categoria = document.getElementById('categoria').value;

        // Categorías que tienen campos dinámicos
        const categoriasConCamposGenericos = ['1', '2', '5'];

        if (categoriasConCamposGenericos.includes(categoria)) {
            data.cpu = document.getElementById('procesador')?.value.trim() || null;
            data.speed = document.getElementById('velocidad')?.value.trim() || null;
            data.memory = document.getElementById('memoria')?.value.trim() || null;
            data.storage = document.getElementById('almacenamiento')?.value.trim() || null;
        }

        try {
            const response = await fetch('/assets', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (!result.ok) {
                if (result.errors) {
                    let messages = Object.values(result.errors).flat().join('\n');
                    alert('Errores:\n' + messages);
                } else {
                    alert(result.message || 'Error al crear el activo');
                }
                return;
            }

            // Éxito: cerrar modal, limpiar formulario y refrescar tabla
            const modalNuevo = bootstrap.Modal.getInstance(document.getElementById('modalNuevoBien'));
            modalNuevo.hide();
            formNuevoBien.reset();
            camposDinamicos.innerHTML = '';

            // --- Mostrar modal de éxito ---
            document.getElementById('mensajeExito').textContent = result.message;
            const modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
            modalExito.show();

            // --- Transformar el asset para DataTable ---
            const nuevoAsset = {
                ...result.data,
                brand: result.data.brand,
                category: result.data.category,
                is_active_label: result.data.is_active ? 'Activo' : 'Inactivo'
            };

            // --- Agregar a DataTable ---
            const table = $('#file_export').DataTable();
            const rowNode = table.row.add(nuevoAsset).draw(false).node();

            const estadoTd = rowNode.querySelector('td:nth-child(6)');
            if (estadoTd) {
                estadoTd.innerHTML = `<span class="badge ${nuevoAsset.is_active ? 'bg-success' : 'bg-danger'} rounded-pill px-3 py-1">
                    ${nuevoAsset.is_active_label}
                </span>`;
            }

        } catch (error) {
            console.error('Error al crear el activo:', error);
            alert('Ocurrió un error inesperado. Revisa la consola.');
        }
    });
}


// ------------------------------
// Eliminar bien
// ------------------------------
document.addEventListener('click', async (e) => {
    const deleteButton = e.target.closest('.btn-delete-asset');
    if (!deleteButton) return;

    const id = deleteButton.dataset.id;
    if (!id) return;

    if (!confirm('¿Estás seguro de eliminar este activo? Esta acción no se puede deshacer.')) {
        return;
    }

    try {
        const response = await fetch(`/assets/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const result = await response.json();

        if (!result.ok) {
            alert(result.message || 'Error al eliminar el activo');
            return;
        }

        // Éxito: eliminar fila de DataTable
        const table = $('#file_export').DataTable();
        const row = deleteButton.closest('tr');
        table.row(row).remove().draw();

        // --- Mostrar modal de éxito ---
        document.getElementById('mensajeExito').textContent = result.message;
        const modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
        modalExito.show();

    } catch (error) {
        console.error('Error al eliminar el activo:', error);
        alert('Ocurrió un error inesperado. Revisa la consola.');
    }
});
