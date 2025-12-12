
document.addEventListener('DOMContentLoaded', function () {

    const modalBien = document.getElementById('modalBien');
    const formBien = document.getElementById('formNuevoBien');
    const modalTitle = document.getElementById('modalBienTitulo');
    const btnSubmit = modalBien ? modalBien.querySelector('button[type="submit"]') : null;
    const camposDinamicos = document.getElementById('camposDinamicos');

    if (!modalBien) {
        return;
    }

    closeModal('btnCerrarModalBien', 'modalBien', 'focusAfterSave');
    closeModal('btnCerrarFooter', 'modalBien', 'focusAfterSave');

    document.addEventListener('click', async e => {
        const btn = e.target.closest('.btn-modal-bien');
        if (!btn) return;

        const mode = btn.dataset.mode;
        const id = btn.dataset.id || null;

        if (formBien) formBien.reset();

        if (mode === 'create') {
            modalTitle.textContent = 'Nuevo Bien';
            btnSubmit.innerHTML = '<i class="fas fa-plus me-1"></i> Guardar';
            btnSubmit.classList.remove('btn-warning');
            btnSubmit.classList.add('btn-primary');
            formBien.dataset.mode = 'create';
            delete formBien.dataset.id;

            try {
                await Promise.all([
                    cargarCategorias(),
                    cargarMarcas(),
                    loadStatuses()
                ]);
                openModalForEdit('modalBien');
            } catch (error) {
                console.error('Error en Promise.all:', error);
                showAlert('Error al cargar los datos del formulario', 'red', 'Error');
                return;
            }
        }

        if (mode === 'edit') {

            modalTitle.textContent = 'Editar Bien';
            btnSubmit.innerHTML = '<i class="fas fa-save me-1"></i> Actualizar';
            btnSubmit.classList.remove('btn-primary');
            btnSubmit.classList.add('btn-warning');
            formBien.dataset.mode = 'edit';
            formBien.dataset.id = id;

            try {
                const response = await fetch(vURIAssetsTableApi, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        option: 'details',
                        id: id
                    })
                });

                const result = await response.json();

                if (!result.ok) {
                    console.error('Error en respuesta de details');
                    return;
                }
                const asset = Array.isArray(result.data)
                    ? result.data.find(a => a.id == id)
                    : result.data;

                if (!asset) {
                    console.error('Asset no encontrado');
                    return;
                }

                // PRIMERO cargar todos los datos asíncronos
                await Promise.all([
                    cargarCategorias(asset.category_id),
                    cargarMarcas(asset.brand_id),
                    loadStatuses(asset.status_id) // Esperar a que termine
                ]);

                // LUEGO establecer los valores
                document.getElementById('numeroInventario').value = asset.inventory_number ?? '';
                document.getElementById('marca').value = asset.brand_id ?? '';
                document.getElementById('modelo').value = asset.model ?? '';
                document.getElementById('serie').value = asset.serial_number ?? '';
                document.getElementById('is_active').value = asset.is_active ? '1' : '0';
                document.getElementById('status_id').value = asset.status_id ?? ''; // Esto ahora funcionará
                document.getElementById('categoria').value = asset.category_id ?? '';
                document.getElementById('descripcion').value = asset.description ?? '';
                document.getElementById('tipo').value = asset.type ?? '';
                document.getElementById('model_year').value = asset.model_year ?? '';
                document.getElementById('acquisition_date').value = asset.acquisition_date ? asset.acquisition_date.split('T')[0] : '';

                // Campos dinámicos
                try {
                    await Promise.all([
                        cargarCategorias(asset.category_id),
                        cargarMarcas(asset.brand_id)
                    ]);
                } catch (error) {
                    console.error('Error cargando datos:', error);
                    showAlert('Error al cargar los datos del formulario', 'red', 'Error');
                    return;
                }

                const categoriaSeleccionada = categoriasGlobales.find(cat => cat.id === asset.category_id);
                if (categoriaSeleccionada && categoriaSeleccionada.special_specifications) {
                    camposDinamicos.innerHTML = camposGenericos;

                    // Establecer valores de campos dinámicos después de crearlos
                    setTimeout(() => {
                        if (document.getElementById('procesador')) document.getElementById('procesador').value = asset.cpu ?? '';
                        if (document.getElementById('velocidad')) document.getElementById('velocidad').value = asset.speed ?? '';
                        if (document.getElementById('memoria')) document.getElementById('memoria').value = asset.memory ?? '';
                        if (document.getElementById('almacenamiento')) document.getElementById('almacenamiento').value = asset.storage ?? '';
                    }, 0);
                } else {
                    camposDinamicos.innerHTML = '';
                }

                // FINALMENTE abrir el modal
                openModalForEdit('modalBien');

            } catch (err) {
                console.error('Error en fetch details:', err);
                showAlert(
                    "Error al obtener datos del fetch.",
                    "red",
                    "Error Fetch",
                    () => console.log("Error al cargar los datos"),
                    3000
                );
            }
        }
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
            const response = await fetch(vURIAssetsTableApi, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    option: 'details',
                    id: id
                })
            });
            const result = await response.json();
            if (!result.ok) {
                showAlert(result.message || 'Error al cargar detalles del activo.', "red", "Error");
                return;
            }

            const asset = result.data;
            if (!asset) {
                showAlert('No se encontró la información del activo.', "orange", "Advertencia");
                return;
            }

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
            const detalleStatus = modal.querySelector('#detalle-status');
            detalleStatus.textContent = asset.status?.name ?? '—';
            detalleStatus.className = 'badge rounded-pill px-3 py-2 bg-secondary';

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
            modal.querySelector('#detalle-tipo').textContent = asset.type ?? '—';
            modal.querySelector('#detalle-model_year').textContent = asset.model_year ?? '—';
            modal.querySelector('#detalle-acquisition_date').textContent = asset.acquisition_date_formatted ?? '—';

            openModalForEdit('modalDetallesBien');
        } catch (error) {
            showAlert('Error al cargar los detalles: ' + error.message, "red", "Error");
        }
    });

    // ------------------------------
    // Eliminar bien
    // ------------------------------
    document.addEventListener('click', (e) => {
    const deleteButton = e.target.closest('.btn-delete-asset');
    if (!deleteButton) return;

    const assetIdToDelete = deleteButton.dataset.id;
    if (!assetIdToDelete) return;

    confirmDestroy(async function() {
        try {
            const response = await fetch(`${vURIAssetsApi}/${assetIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const { success, data, status } = await handleHttpResponse(response);

            if (success) {
                showAlert(
                    data.message || 'Activo eliminado correctamente.',
                    "green",
                    "Éxito",
                    () => {
                        if (tableApi) {
                            tableApi.draw(false);
                        }
                    },
                    3000
                );
            } else {
                // Manejar diferentes tipos de errores
                let errorMessage = data.message || 'No se pudo eliminar el activo.';
                let errorTitle = "Error";

                if (status === 400) {
                    errorTitle = "No se puede eliminar";
                } else if (status === 404) {
                    errorMessage = 'El activo no fue encontrado.';
                } else if (status >= 500) {
                    errorTitle = "Error del servidor";
                    errorMessage = 'Error interno del servidor. Por favor, contacte al administrador.';
                }

                showAlert(
                    errorMessage,
                    "red",
                    errorTitle,
                    null,
                    5000
                );
            }

        } catch (error) {
            console.error('Error en eliminación:', error);

            // Mensajes de error amigables según el tipo de error
            let userMessage = 'Ocurrió un error inesperado. Por favor, intente nuevamente.';
            let userTitle = 'Error';

            if (error.message.includes('inválida')) {
                userMessage = 'Error de comunicación con el servidor. Verifique su conexión.';
                userTitle = 'Error de conexión';
            } else if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
                userMessage = 'No se pudo conectar con el servidor. Verifique su conexión a internet.';
                userTitle = 'Error de red';
            }

            showAlert(
                userMessage,
                "red",
                userTitle,
                null,
                5000
            );
        }
    }, "¿Está seguro de que desea eliminar este bien? Esta acción no se puede deshacer.");
});

    /**
     * Maneja respuestas HTTP de manera uniforme
     */
    async function handleHttpResponse(response) {
        const contentType = response.headers.get('content-type');

        // Verificar si la respuesta es JSON válido
        if (!contentType || !contentType.includes('application/json')) {
            const textResponse = await response.text();
            console.warn('Respuesta no JSON:', textResponse.substring(0, 200));
            throw new Error('Respuesta del servidor inválida');
        }

        const result = await response.json();

        return {
            success: response.ok && result.ok,
            data: result,
            status: response.status,
            statusText: response.statusText
        };
    }
});
