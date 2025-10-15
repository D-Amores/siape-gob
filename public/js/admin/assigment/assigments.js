// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    loadPersonnel();
    loadAssets();
});

$(document).ready(function() {
    // Inicializar segunda tabla
    $('#file_export2').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});

const assignmentForm = document.getElementById('assignmentForm');

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

    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';

    try {

        const formData = new FormData(assignmentForm);
        const data = Object.fromEntries(formData.entries());
        data.assignment_date = new Date().toISOString().slice(0, 10);

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

            // loadAssignments();
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
