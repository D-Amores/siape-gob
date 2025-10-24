const dataTables = {}; // 🔹 Almacena todas las instancias por ID

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
            document.querySelectorAll(tooltips).forEach((el) => new bootstrap.Tooltip(el));
        };

        dataTables[tableId].on('draw', activateTooltips);
        activateTooltips();
    }

    return dataTables[tableId];
}


function bottomTableConfig(tableId = 'dataUsersTable', data = [], columns = [], tooltips = null) {
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
        pagingType: 'simple_numbers',
        destroy: true,
        responsive: true,
        pageLength: 30,
        lengthChange: false,
        info: false,
        language: { url: languageDataTable },
        layout: {
            topStart: { buttons: ['copy', 'csv', 'excel', 'pdf', 'print'] },
            topEnd: {
                search: { placeholder: 'Buscar...' }
            },
            bottomStart: null,
            bottomEnd: 'paging'
        }
    });

    if (tooltips) {
        const activateTooltips = () => {
            document.querySelectorAll(tooltips).forEach((el) => new bootstrap.Tooltip(el));
        };

        dataTables[tableId].on('draw', activateTooltips);
        activateTooltips();
    }

    return dataTables[tableId];
}