// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    loadPersonnel();
    loadAssets();

    $('#table_acepted_assigments').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    loadAssetPending();
});

const assignmentForm = document.getElementById('assignmentForm');
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Manejar el envío del formulario de asignación
assignmentForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const personnelSelect = document.getElementById('assignedUser');
    const assetSelect = document.getElementById('assignedAsset');

    if (!personnelSelect.value || !assetSelect.value) {
        $.alert({
            title: 'Error',
            content: 'Debes seleccionar personal y un bien para la asignación.',
            type: 'red',
            theme: 'material',
            buttons: { ok: { text: 'Aceptar', btnClass: 'btn-red' } }
        });
        return;
    }

    // Mostrar indicador de carga en el botón de envío
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';

    // Enviar datos al servidor
    try {

        const formData = new FormData(assignmentForm);
        formData.set('assignment_date', new Date().toISOString().slice(0, 10));
        const data = Object.fromEntries(formData.entries());

        const response = await fetch('/personnel-asset-pending', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.ok) {
            $.alert({
                title: 'Éxito',
                content: 'La asignación se ha guardado correctamente.',
                type: 'green',
                theme: 'material',
                backgroundDismiss: true,
                buttons: { ok: { text: 'Aceptar', btnClass: 'btn-green' } }
            });

            const modal = bootstrap.Modal.getInstance(document.getElementById('addAssignmentModal'));
            modal.hide();
            assignmentForm.reset();

            loadAssetPending();
        } else {
            $.alert({
                title: 'Error',
                content: result.message || 'Ocurrió un error al guardar la asignación.',
                type: 'red',
                theme: 'material',
                buttons: { ok: { text: 'Aceptar', btnClass: 'btn-red' } }
            });
        }

    } catch (error) {
        console.error('❌ Error al guardar la asignación:', error);
        $.alert({
            title: 'Error',
            content: 'Ocurrió un error al guardar la asignación. Revisa la consola para más detalles.',
            type: 'red',
            theme: 'material',
            buttons: { ok: { text: 'Aceptar', btnClass: 'btn-red' } }
        });
    } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    }
});

// Función para cargar las asignaciones pendientes
const loadAssetPending = async () => {

    try {
        const response = await fetch('/personnel-asset-pending/api', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.ok) {
            updateAssetPendingTable(data.data);
        } else {
            console.error('❌ Error al cargar categorías:', data.message);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar las categorías. Revisa la consola.',
                confirmButtonColor: '#d33'
            });
        }

    } catch (error) {
        console.error('❌ Error al cargar categorías:', error);
        const errorMessage = error.message ? `Detalles: ${error.message}` : 'Revisa la consola para más detalles.';

        $.alert({
            title: 'Error',
            content: errorMessage,
            type: 'red',
            theme: 'material',
            backgroundDismiss: true,
            buttons: {
                ok: {
                    text: 'Aceptar',
                    btnClass: 'btn-red'
                }
            }
        });

    }
}

// Función para actualizar la tabla de asignaciones pendientes
const updateAssetPendingTable = (assetPendings) => {
    const table = document.getElementById('table_pendings_assigments');

    // Destruir instancia previa de DataTable si existe
    if ($.fn.DataTable.isDataTable(table)) {
        $(table).DataTable().destroy();
    }

    // Limpiar el cuerpo de la tabla
    const tbody = table.querySelector('tbody');
    tbody.innerHTML = '';

    if (assetPendings.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    <i class="fas fa-plus"></i><br>
                    Agrega la primera categoría
                </td>
                <td class="text-center text-muted py-4">
                    <p>No hay categorías disponibles</p>
                </td>
                <td class="text-center text-muted py-4">
                    <p>No hay categorías disponibles</p>
                </td>
                <td class="text-center text-muted py-4">
                    <p>No hay categorías disponibles</p>
                </td>
            </tr>
        `;
    } else {
        assetPendings.forEach(assetPending => {
            const row = document.createElement('tr');
            row.setAttribute('data-assetPending-id', assetPending.id);
            row.innerHTML = `
                    <td class="ps-4 text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-user text-muted me-2"></i>
                            <span>${assetPending.receiver_name || '—'}</span>
                        </div>
                    </td>
                    <td class="ps-4 text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-box text-muted me-2"></i>
                            <span>${assetPending.asset_id || '—'}</span>
                        </div>
                    </td>
                    <td class="ps-4 text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-user text-muted me-2"></i>
                            <span>${assetPending.assigner_name || '—'}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button"
                                    class="btn btn-outline-primary border-0 btn-edit"
                                    data-assignment-id="${assetPending.id}"
                                    title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button"
                                    class="btn btn-outline-danger border-0 btn-delete"
                                    data-assignment-id="${assetPending.id}"
                                    title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                `;

            tbody.appendChild(row);
        });

    }

    $(table).DataTable({
        language: {
            url: language
        },
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        columnDefs: [
            { orderable: false, targets: 1 }
        ]
    });

};
