function loadUsersTable(data) {
    const tableSelector = "dataUsersTable";
    const columns = [
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
                data: "personnel.area_name",
                defaultContent: "Sin área",
                title: "Área",
                render: (area_name) => `
                    <div class="text-truncate"
                         style="max-width: 140px; cursor: pointer;"
                         title="${capitalizeWords(area_name ?? 'Sin área')}"
                         data-bs-toggle="tooltip"
                         data-bs-placement="top">
                        ${capitalizeWords(area_name ?? 'Sin área')}
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
                    <div class="d-flex justify-content-center gap-2" role="group">
                        <button class="btn btn-sm btn-outline-primary border-0 btn-edit" data-id="${row.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger border-0 btn-delete" data-id="${row.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `
            }
        ];
    const tooltips = '[data-bs-toggle="tooltip"]';

    basicTableConfig(tableSelector, data, columns, tooltips);
}
