let usersTable = null;

/**
 * Inicializa o actualiza la tabla de usuarios.
 * @param {Array} data - Datos de usuarios obtenidos del backend.
 */
function loadUsersTable(data) {
    const tableSelector = "#dataUsersTable";

    // Si ya existe la tabla, actualizamos los datos
    if (usersTable) {
        usersTable.clear().rows.add(data).draw();
        return;
    }

    // Inicializamos la DataTable sobre tu estructura existente
    usersTable = new DataTable(tableSelector, {
        data: data,
        columns: [
            {
                data: null,
                render: (d, t, r, meta) => meta.row + 1,
                title: "#"
            },
            {
                data: null,
                title: "Usuario",
                render: (row) => `
                    <div class="d-flex align-items-center">
                        <img src="${row.avatar_url}"
                             class="rounded-circle" width="40" height="40"
                             alt="Avatar de ${row.username}">
                        <div class="ms-3">
                            <h6 class="fs-4 fw-semibold mb-0 text-truncate"
                                style="max-width: 140px;">${row.username ?? ''}</h6>
                        </div>
                    </div>
                `
            },
            {
                data: null,
                title: "Persona Asignada",
                render: (row) => {
                    const fullName = `${row.personnel?.last_name ?? ''} ${row.personnel?.middle_name ?? ''}`.trim();
                    const displayName = `${row.personnel?.name ?? ''}`.trim();

                    return `
                        <div class="d-flex align-items-center">
                            <div class="ms-3">
                                <h6 class="fs-4 fw-semibold mb-0 text-truncate" style="max-width: 140px;">
                                    ${capitalizeWords(displayName) || '—'}
                                </h6>
                                <span class="fw-normal text-truncate"
                                      style="display: inline-block; max-width: 140px; cursor: pointer;"
                                      title="${capitalizeWords(fullName)}"
                                      data-bs-toggle="tooltip"
                                      data-bs-placement="top">
                                    ${capitalizeWords(fullName) || '—'}
                                </span>
                            </div>
                        </div>
                    `;
                }
            },
            {
                data: "area.name",
                defaultContent: "Sin área",
                title: "Área",
                render: (area) => `
                    <div class="text-truncate"
                         style="max-width: 140px; cursor: pointer;"
                         title="${capitalizeWords(area ?? 'Sin área')}"
                         data-bs-toggle="tooltip"
                         data-bs-placement="top">
                        ${capitalizeWords(area ?? 'Sin área')}
                    </div>
                `
            },
            {
                data: null,
                title: "Rol",
                render: (row) => {
                    // Verifica si tiene roles asignados
                    if (row.roles && row.roles.length > 0) {
                        return row.roles.map(r => capitalizeWords(r.name)).join(', ');
                    }
                    return '—'; // Si no tiene roles
                }
            }
            ,
            {
                data: "is_active",
                title: "Estado",
                render: (active) => `
                    <span class="badge ${active ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger'}">
                        ${active ? 'Activo' : 'Inactivo'}
                    </span>
                `
            },
            {
                data: null,
                title: "Acciones",
                render: (row) => `
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-success btn-edit" data-id="${row.id}">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${row.id}">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                `
            }
        ],
        destroy: true,
        responsive: true,
        pageLength: 10,
        language: { url: languageDataTable }
    });

    // ⚡ Reactivar tooltips en cada renderizado
    const activateTooltips = () => {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
            new bootstrap.Tooltip(el);
        });
    };

    usersTable.on('draw', activateTooltips);
    activateTooltips();
}

/**
 * Convierte la primera letra en mayúscula y el resto en minúsculas.
 * @param {string} text
 * @returns {string}
 */
