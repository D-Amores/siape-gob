// ------------------------------
// Modal dinámico por categoría
// ------------------------------
const categoriaSelect = document.getElementById('categoria');
const camposDinamicos = document.getElementById('camposDinamicos');
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let editingAssetId = null;
let assetIdToDelete = null;

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
            camposDinamicos.innerHTML = '';
        }
    });
}

// ------------------------------
// Cargar categorías dinámicamente
// ------------------------------
async function cargarCategorias(selectedId = null) {
    const categoriaSelect = document.getElementById('categoria');
    if (!categoriaSelect) return;

    try {
        // Hacemos fetch a tu API de categorías
        const res = await fetch('/categories/api', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const json = await res.json();

        if (!json.ok) {
            console.error('Error al cargar categorías:', json.message);
            return;
        }

        // Limpiar select antes de insertar
        categoriaSelect.innerHTML = `<option value="" selected>Seleccione categoría</option>`;

        // Insertar opciones dinámicamente
        json.data.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.name;
            if (selectedId && selectedId == cat.id) {
                option.selected = true;
            }
            categoriaSelect.appendChild(option);
        });

    } catch (error) {
        console.error('Error en fetch categorías:', error);
    }
}

// ------------------------------
// Cargar marcas dinámicamente
// ------------------------------
async function cargarMarcas(selectedId = null) {
    try {
        const res = await fetch('/brands/api', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        const json = await res.json();
        if (!json.ok) return;

        const marcaSelect = document.getElementById('marca');
        marcaSelect.innerHTML = '<option value="">Seleccione marca</option>';
        json.data.forEach(m => {
            const option = document.createElement('option');
            option.value = m.id;
            option.textContent = m.name;
            if (selectedId && selectedId == m.id) option.selected = true;
            marcaSelect.appendChild(option);
        });

    } catch (error) {
        console.error('Error cargando marcas:', error);
    }
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
                        <button class="btn btn-outline-primary btn-sm mx-1 btn-modal-bien"
                            data-bs-toggle="modal"
                            data-bs-target="#modalBien"
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
            ],
            dom: 'Bfrtip', // Posición de los botones
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: '<i></i> Copiar',
                    className: 'btn btn-secondary btn-sm'
                },
                {
                    extend: 'excelHtml5',
                    text: '<i></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'csvHtml5',
                    text: '<i></i> CSV',
                    className: 'btn btn-info btn-sm'
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    orientation: 'landscape',
                    pageSize: 'A4'
                },
                {
                    extend: 'print',
                    text: '<i></i> Imprimir',
                    className: 'btn btn-primary btn-sm'
                }
            ]
        });
    })
        .catch(error => {
            console.error('Error en la petición de assets:', error);
            alert('Error al cargar los activos. Revisa la consola.');
        });
});

// ------------------------------
// Evento delegado: Ver detalles
// ------------------------------
document.querySelector('#file_export tbody').addEventListener('click', async (event) => {
    const button = event.target.closest('.btn-ver');
    if (!button) return;

    const id = button.dataset.id;
    if (!id) return;

    try {
        const response = await fetch('/assets/api?option=details');
        const result = await response.json();
        if (!result.ok) return;

        const asset = result.data.find(a => a.id == id);
        if (!asset) return;

        const modal = document.getElementById('modalDetallesBien');

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

        modal.querySelectorAll('span[id^="detalle-"]').forEach(span => span.textContent = '...');
        modal.querySelector('#detalle-descripcion').textContent = '';

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

        openModalForEdit('modalDetallesBien');

    } catch (error) {
        console.error('Error al cargar detalles:', error);
    }
});


// ------------------------------
// Eliminar bien
// ------------------------------
document.addEventListener('click', (e) => {
    const deleteButton = e.target.closest('.btn-delete-asset');
    if (!deleteButton) return;

    assetIdToDelete = deleteButton.dataset.id;
    if (!assetIdToDelete) return;

    // Abrir modal
    const modalDelete = new bootstrap.Modal(document.getElementById('modalConfirmDelete'));
    modalDelete.show();
});

// Botón Confirmar Eliminación
document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
    if (!assetIdToDelete) return;

    try {
        const response = await fetch(`/assets/${assetIdToDelete}`, {
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

        // Cerrar modal
        closeModalDirect('modalConfirmDelete');

        // Eliminar fila de DataTable
        const table = $('#file_export').DataTable();
        const row = document.querySelector(`.btn-delete-asset[data-id="${assetIdToDelete}"]`).closest('tr');
        table.row(row).remove().draw();

        assetIdToDelete = null;

    } catch (error) {
        console.error('Error al eliminar el activo:', error);
        alert('Ocurrió un error inesperado. Revisa la consola.');
    }
});


// ------------------------------
// Modal para crear/editar bien
// ------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const modalBien = document.getElementById('modalBien');
    const modalTitle = document.getElementById('modalBienTitulo');
    const formBien = document.getElementById('formNuevoBien');
    const btnSubmit = modalBien.querySelector('button[type="submit"]');
    const tableBody = document.querySelector('#file_export tbody');

    function loadAssets() {
        if ($.fn.DataTable.isDataTable('#file_export')) {
            const table = $('#file_export').DataTable();

            fetch('/assets/api?option=table')
                .then(res => res.json())
                .then(json => {
                    if (!json.ok) return;

                    table.clear();
                    table.rows.add(json.data);
                    table.draw();
                })
                .catch(err => console.error('Error al refrescar la tabla:', err));
        }
    }

    // Carga inicial de la tabla
    loadAssets();

    document.addEventListener('click', async e => {
        const btn = e.target.closest('.btn-modal-bien');
        if (!btn) return;

        const mode = btn.dataset.mode;
        const id = btn.dataset.id || null;

        formBien.reset();

        if (mode === 'create') {
            modalTitle.textContent = 'Nuevo Bien';
            btnSubmit.innerHTML = '<i class="fas fa-plus me-1"></i> Guardar';
            btnSubmit.classList.remove('btn-warning');
            btnSubmit.classList.add('btn-primary');
            formBien.dataset.mode = 'create';
            delete formBien.dataset.id;

            // Cargar categorías y marcas dinámicamente
            await Promise.all([
                cargarCategorias(),
                cargarMarcas()
            ]);
        }

        if (mode === 'edit') {
            modalTitle.textContent = 'Editar Bien';
            btnSubmit.innerHTML = '<i class="fas fa-save me-1"></i> Actualizar';
            btnSubmit.classList.remove('btn-primary');
            btnSubmit.classList.add('btn-warning');
            formBien.dataset.mode = 'edit';
            formBien.dataset.id = id;

            // Cargar datos del bien
            fetch('/assets/api?option=details')
            .then(res => res.json())
            .then(async result => {
                if (!result.ok) return;
                const asset = result.data.find(a => a.id == id);
                if (!asset) return;

                document.getElementById('numeroInventario').value = asset.inventory_number ?? '';
                document.getElementById('marca').value = asset.brand_id ?? '';
                document.getElementById('modelo').value = asset.model ?? '';
                document.getElementById('serie').value = asset.serial_number ?? '';
                document.getElementById('estado').value = asset.is_active ? '1' : '0';
                document.getElementById('categoria').value = asset.category_id ?? '';
                document.getElementById('descripcion').value = asset.description ?? '';

                await Promise.all([
                    cargarCategorias(asset.category_id),
                    cargarMarcas(asset.brand_id)
                ]);

                if (categoriasConCamposGenericos.includes(asset.category_id?.toString())) {
                    camposDinamicos.innerHTML = camposGenericos;
                } else {
                    camposDinamicos.innerHTML = '';
                }

                if (document.getElementById('procesador')) document.getElementById('procesador').value = asset.cpu ?? '';
                if (document.getElementById('velocidad')) document.getElementById('velocidad').value = asset.speed ?? '';
                if (document.getElementById('memoria')) document.getElementById('memoria').value = asset.memory ?? '';
                if (document.getElementById('almacenamiento')) document.getElementById('almacenamiento').value = asset.storage ?? '';
            })
            .catch(err => console.error('Error al cargar el bien:', err));
        }
    });

    // Control del envío del formulario
    formBien.addEventListener('submit', async e => {
        e.preventDefault();
        const mode = formBien.dataset.mode;

        const formData = {
            inventory_number: document.getElementById('numeroInventario').value,
            brand_id: document.getElementById('marca').value,
            model: document.getElementById('modelo').value,
            serial_number: document.getElementById('serie').value,
            is_active: document.getElementById('estado').value === '1',
            category_id: document.getElementById('categoria').value,
            description: document.getElementById('descripcion').value,
            cpu: document.getElementById('procesador')?.value || null,
            speed: document.getElementById('velocidad')?.value || null,
            memory: document.getElementById('memoria')?.value || null,
            storage: document.getElementById('almacenamiento')?.value || null
        };

        try {
            if (mode === 'create') {
                const res = await fetch('/assets', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(formData)
                });
                result = await res.json();
                if (!res.ok) throw new Error(result.message || 'Error al crear bien');
            } else if (mode === 'edit') {
                const id = formBien.dataset.id;
                const res = await fetch(`/assets/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(formData)
                });
                result = await res.json();
                if (!res.ok) throw new Error('Error al actualizar bien');
            }

            const modalInstance = bootstrap.Modal.getInstance(modalBien);
            modalInstance.hide();

            loadAssets();
        } catch (error) {
            console.error('Error al enviar formulario:', error);
        }
    });
});

// ------------------------------
// Funciones auxiliares
// ------------------------------
function openModalForEdit(modalID){
    const modalEl = document.getElementById(modalID);
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}

function closeModal(btnCloseID, modalID, focusID){
    document.getElementById(btnCloseID).addEventListener('click', () => {
        const modalEl = document.getElementById(modalID);
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.hide();
        if(focusID) moveFocus(`#${focusID}`);
    });
};

function closeModalDirect(modalID){
    const modalEl = document.getElementById(modalID);
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.hide();
}
