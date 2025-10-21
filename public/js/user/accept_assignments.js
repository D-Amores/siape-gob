async function loadPendingAssignments(token) {
    const table = $('#table_pendings_assigments_users').DataTable();

    try {
        const res = await fetch('/accept-assignments/api', {
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
        console.error('Error al cargar asignaciones pendientes', err);
        table.clear().draw();
    }
}

document.addEventListener('DOMContentLoaded', async function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $('#table_pendings_assigments_users').DataTable({
        data: [],
        columns: [
            { data: 'assigner_name', className: 'text-center' },
            { data: 'receiver_name', className: 'text-center' },
            { data: 'asset_name', className: 'text-center' },
            { data: 'assignment_date', className: 'text-center' },
            {
                data: 'id',
                className: 'text-center',
                render: function (id) {
                    return `<button class="btn btn-success btn-sm accept-btn" data-id="${id}">
                                <i class="fas fa-check me-1"></i> Aceptar
                            </button>`;
                }
            }
        ],
        language: { url: language },
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });

    // Cargar datos al inicio
    await loadPendingAssignments(token);
});

// Listener de botones
$('#table_pendings_assigments_users').on('click', '.accept-btn', async function () {
    const id = $(this).data('id');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!confirm('¿Seguro que deseas aceptar este bien?')) return;

    try {
        const res = await fetch('/accept-assignments/accept', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id })
        });

        const data = await res.json();

        if (!data.ok) {
            alert(data.message || 'Error al aceptar asignación');
            return;
        }

        alert('Asignación aceptada correctamente.');
        await loadPendingAssignments(token);
    } catch (err) {
        console.error('Error al aceptar asignación', err);
        alert('Error al aceptar asignación.');
    }
});
