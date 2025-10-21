function loadPersonnelTable(data) {
    const tableSelector = "dataPersonnelTable";
    columns = [
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
                        <div class="ms-3">
                            <h6 class="fs-4 fw-semibold mb-0 text-truncate"
                                style="max-width: 140px;">
                                ${capitalizeWords(row.name ?? '')}
                            </h6>
                            <span class="fw-normal text-truncate"
                                  style="display: inline-block; max-width: 140px; cursor: pointer;"
                                  title="${capitalizeWords(row.last_name ?? '') + ' ' + capitalizeWords(row.middle_name ?? '')}"
                                  data-bs-toggle="tooltip"
                                  data-bs-placement="top">
                                ${capitalizeWords(row.last_name ?? '') + ' ' + capitalizeWords(row.middle_name ?? '')}
                            </span>
                        </div>
                    </div>
                `
            },
            { data: "phone", defaultContent: "—", title: "Teléfono" },

            // 📨 E-mail con truncado + tooltip
            {
                data: "email",
                defaultContent: "—",
                title: "E-mail",
                render: (email) => `
                    <div class="text-truncate"
                         style="max-width: 150px; cursor: pointer;"
                         title="${email ?? '—'}"
                         data-bs-toggle="tooltip"
                         data-bs-placement="top">
                        ${email ?? '—'}
                    </div>
                `
            },

            // 🏢 Área con truncado + tooltip
            {
                data: "area.name",
                defaultContent: "Sin área",
                title: "Área",
                render: (area) => `
                    <div class="text-truncate"
                         style="max-width: 120px; cursor: pointer;"
                         title="${capitalizeWords(area ?? 'Sin área')}"
                         data-bs-toggle="tooltip"
                         data-bs-placement="top">
                        ${capitalizeWords(area ?? 'Sin área')}
                    </div>
                `
            },

            {
                data: "is_active",
                title: "Estado",
                render: (active) =>
                    `<span class="badge ${active ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger'}">
                        ${active ? 'Activo' : 'Inactivo'}
                     </span>`
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
