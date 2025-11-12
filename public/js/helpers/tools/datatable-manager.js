let dataTables = {};

function bottomTableConfig(tableId = 'dataUsersTable', columns = [], tooltips = null) {
    const tableSelector = `#${tableId}`;

    if (dataTables[tableId]) {
        dataTables[tableId].destroy();
    }

    dataTables[tableId] = new DataTable(tableSelector, {

        serverSide: true,
        processing: true,
        ajax: {
            url: vURIAssetsTableApi,
            type: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            data: function (d) {
                d.option = 'table';

                d.filtroCondicion = $('#filtroCondicion').val();
                d.filtroEstado = $('#filtroEstado').val();
                d.filtroCategoria = $('#filtroCategoria').val();
                d.filtroMarca = $('#filtroMarca').val();

                return JSON.stringify(d);
            },
            contentType: 'application/json',
        },

        columns: columns,
        pagingType: 'simple_numbers',
        responsive: true,
        pageLength: 30,
        lengthChange: false,
        info: true,
        language: { url: languageDataTable },
        layout: {
            topStart: { buttons: ['copy', 'csv', 'excel', 'pdf', 'print'] },
            topEnd: null,
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
