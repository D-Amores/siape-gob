document.addEventListener('DOMContentLoaded', async function () {
    const table = $('#table_pendings_assigments_users').DataTable({
        data: [], // datos iniciales vacíos
        columns: [
            { data: 'assigner_name', className: 'text-center' },  // Asigna
            { data: 'receiver_name', className: 'text-center' },  // Recibe
            { data: 'asset_name', className: 'text-center' },     // Bien
            { data: 'assignment_date', className: 'text-center' },// Fecha de asignación
            {
                data: 'id',
                className: 'text-center',
                render: function (id) {
                    return `
                        <button class="btn btn-success btn-sm accept-btn" data-id="${id}">
                            <i class="fas fa-check me-1"></i> Aceptar
                        </button>
                    `;
                }
            }
        ],
        language: { url: language },
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Función para cargar asignaciones pendientes
    async function loadPendingAssignments() {
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

    // Cargar datos al inicio
    await loadPendingAssignments();

});
