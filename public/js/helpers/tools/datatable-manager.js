let dataTable = null;


/**
 * Inicializa o actualiza la tabla de usuarios.
 * @param {Array} data - Datos obtenidos del backend.
 */
function basicTableConfig(tableId = 'dataUsersTable', data = [], columns = [], tooltips = null) {
    const tableSelector = `#${tableId}`;

    if (dataTable) {
        dataTable.clear().rows.add(data).draw();
        return;
    }

    dataTable = new DataTable(tableSelector, {
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
        // ⚡ Reactivar tooltips en cada renderizado
        const activateTooltips = () => {
            document.querySelectorAll(tooltips).forEach((el) => {
                new bootstrap.Tooltip(el);
            });
        };
    
        dataTable.on('draw', activateTooltips);
        activateTooltips();
    }

}
