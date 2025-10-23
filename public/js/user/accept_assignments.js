async function loadPendingAssignments(token) {
    try {
        const res = await fetch(vURIAcceptAssignmentsTableApi, {
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
            // Actualiza la tabla con datos vacíos
            basicTableConfig('table_pendings_assigments_users', []);
            return;
        }

        // Inicializa o actualiza la tabla con los datos recibidos
        basicTableConfig('table_pendings_assigments_users', data.data, [
            { data: 'assigner_name', className: 'text-center', title: 'Asignador' },
            { data: 'receiver_name', className: 'text-center', title: 'Receptor' },
            { data: 'asset_name', className: 'text-center', title: 'Bien' },
            { data: 'assignment_date', className: 'text-center', title: 'Fecha' },
            {
                data: 'id',
                className: 'text-center',
                title: 'Acción',
                render: (id) => `
                    <button class="btn btn-success btn-sm accept-btn" data-id="${id}">
                        <i class="fas fa-check me-1"></i> Aceptar
                    </button>`
            }
        ], '[data-bs-toggle="tooltip"]');

    } catch (err) {
        console.error('Error al cargar asignaciones pendientes', err);
        // Vacía la tabla en caso de error
        basicTableConfig('table_pendings_assigments_users', []);// Muestra alerta con el error
        showAlert(
            'Ocurrió un error al cargar las asignaciones pendientes. Intenta nuevamente.',
            'red',            
            'Error',           
            null,             
            5000               
        );
    }
}

document.addEventListener('DOMContentLoaded', async function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    await loadPendingAssignments(token);
});


// Listener de botones
$('#table_pendings_assigments_users').on('click', '.accept-btn', function () {
    const id = $(this).data('id');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    confirmStore(
        async () =>{
            try {
                const res = await fetch(vURIAcceptActionApi, {
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
                    showAlert(data.message || 'Error al aceptar asignación', 'red', 'Error');
                    return;
                }

                showAlert('Asignación aceptada correctamente.', 'green', 'Éxito', async () => {
                    await loadPendingAssignments(token);

                    if (data.pdfUrl) {
                        window.location.href = data.pdfUrl; 
                    }
                });
            } catch (err) {
                console.error('Error al aceptar asignación', err);
                showAlert('Error al aceptar asignación.', 'red', 'Error');
            }
        }, "¿Está seguro de aceptar este bien asignado? Esta acción no podrá ser revertida."
    )
});