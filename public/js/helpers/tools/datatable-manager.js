let dataTables = {};

function basicTableConfig(tableId = 'dataUsersTable', data = [], columns = [], tooltips = null) {
    const tableSelector = `#${tableId}`;

    // Si ya existe una instancia para este ID, solo actualiza
    if (dataTables[tableId]) {
        dataTables[tableId].clear().rows.add(data).draw();
        return dataTables[tableId];
    }

    // Si no existe, crea una nueva
    dataTables[tableId] = new DataTable(tableSelector, {
        data: data,
        columns: columns,
        ordering: false, 
        searching: false,
        pagingType: 'simple_numbers',
        destroy: true,
        responsive: true,
        pageLength: 30,
        lengthChange: false,
        info: false,
        language: { url: languageDataTable }
    });

    if (tooltips) {

        const activateTooltips = () => {
            document.querySelectorAll(tooltips).forEach((el) => {
                // 🔥 Si ya existe un tooltip, destrúyelo
                const existing = bootstrap.Tooltip.getInstance(el);
                if (existing) existing.dispose();

                // 🔥 Crea uno nuevo limpio
                new bootstrap.Tooltip(el);
            });
        };

        // Activar tooltips en cada redibujado
        dataTables[tableId].on('draw', activateTooltips);

        // Activar en la primera carga
        activateTooltips();
    }

    return dataTables[tableId];
}

function basicTableConfigWithoutPaging(tableId = 'dataUsersTable', data = [], columns = [], tooltips = null) {
    const tableSelector = `#${tableId}`;

    // Si ya existe una instancia para este ID, solo actualiza
    if (dataTables[tableId]) {
        dataTables[tableId].clear().rows.add(data).draw();
        return dataTables[tableId];
    }

    // Si no existe, crea una nueva
    dataTables[tableId] = new DataTable(tableSelector, {
        data: data,
        columns: columns,
        ordering: false, 
        searching: false,
        paging: false,
        destroy: true,
        responsive: true,
        lengthChange: false,
        info: false,
        language: { url: languageDataTable }
    });

    if (tooltips) {

        const activateTooltips = () => {
            document.querySelectorAll(tooltips).forEach((el) => {
                // 🔥 Si ya existe un tooltip, destrúyelo
                const existing = bootstrap.Tooltip.getInstance(el);
                if (existing) existing.dispose();

                // 🔥 Crea uno nuevo limpio
                new bootstrap.Tooltip(el);
            });
        };

        // Activar tooltips en cada redibujado
        dataTables[tableId].on('draw', activateTooltips);

        // Activar en la primera carga
        activateTooltips();
    }

    return dataTables[tableId];
}


// Lo que debe recibir filtros
// {
//     ajaxUrl: urlFiltered,
//     csrfToken: csrfToken,
//     ajaxDataFn: (d) => {
//         d.option = 'table';
//         d.filtroCondicion = $('#filtroCondicion').val();
//         d.filtroEstado = $('#filtroEstado').val();
//         d.filtroCategoria = $('#filtroCategoria').val();
//         d.filtroMarca = $('#filtroMarca').val();
//         return JSON.stringify(d);
//     }
// }

function bottomTableConfig(tableId = 'dataUsersTable', data = [], columns = [], tooltips = null, filtros = null) {
    const tableSelector = `#${tableId}`;

    if (dataTables[tableId] && filtros) {
        dataTables[tableId].destroy();
    } else if (dataTables[tableId]) {
        dataTables[tableId].clear().rows.add(data).draw();
        return dataTables[tableId];
    }

    const topEndContent = filtros ? null : 'search';

    let config = {
        columns: columns,
        pagingType: 'simple_numbers',
        responsive: true,
        pageLength: 30,
        lengthChange: false,
        info: true,
        searching: !filtros,
        language: { url: languageDataTable },
        layout: {
            topStart: { buttons: ['copy', 'csv', 'excel', 'pdf', 'print'] },
            topEnd: topEndContent,
            bottomStart: 'info',
            bottomEnd: 'paging'
        },
    };

    if (data && data.length > 0) {
        config.data = data;
    }

    if (filtros) {
        config.serverSide = true;
        config.processing = true;
        config.ajax = {
            url: filtros.ajaxUrl,
            type: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': filtros.csrfToken
            },
            data: filtros.ajaxDataFn,
            contentType: 'application/json',
        };
    }

    dataTables[tableId] = new DataTable(tableSelector, config);

    if (tooltips) {
        const activateTooltips = () => {
            const tableEl = document.querySelector(tableSelector);
            const scope = tableEl || document;
            scope.querySelectorAll(tooltips).forEach((el) => {
                if (!bootstrap.Tooltip.getInstance(el)) {
                    new bootstrap.Tooltip(el);
                }
            });
        };
        dataTables[tableId].on('draw', activateTooltips);
        activateTooltips();
    }

    return dataTables[tableId];
}
