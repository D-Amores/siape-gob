let personnelTable = null;

/**
 * Inicializa o actualiza la tabla de personal.
 * @param {Array} data - Datos del personal obtenidos del backend.
 */
function loadPersonnelTable(data) {
    // Si ya existe la tabla, solo actualizamos los datos
    if (personnelTable) {
        personnelTable.clear().rows.add(data).draw();
        return;
    }

    // Inicializar DataTable
    personnelTable = new DataTable("#dataPersonnelTable", {
        data: data,
        columns: [
            {
                data: null,
                render: (d, t, r, meta) => meta.row + 1,
                title: "#"
            },
            {
                data: null,
                title: "Nombre(s)",
                render: (row) => `
                    <div class="d-flex align-items-center">
                        <img src="/modernize/assets/images/profile/user-6.jpg"
                             class="rounded-circle" width="40" height="40">
                        <div class="ms-3">
                            <h6 class="fs-4 fw-semibold mb-0">${row.name}</h6>
                            <span class="fw-normal">${row.middle_name ?? ''} ${row.last_name ?? ''}</span>
                        </div>
                    </div>
                `
            },
            { data: "phone", defaultContent: "—", title: "Teléfono" },
            { data: "email", defaultContent: "—", title: "E-mail" },
            { data: "area.name", defaultContent: "Sin área", title: "Área" },
            {
                data: "is_active",
                title: "Estado",
                render: (active) =>
                    `<span class="badge ${active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'}">
                        ${active ? 'Activo' : 'Inactivo'}
                     </span>`
            },
            {
                data: null,
                title: "Acciones",
                render: (row) => `
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${row.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${row.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `
            }
        ],
        destroy: true,
        responsive: true,
        pageLength: 10,
        language: {
            url: languageDataTable, // tu archivo de idioma
        }
    });
}
