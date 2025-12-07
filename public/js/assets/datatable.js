let tableApi = null;

document.addEventListener('DOMContentLoaded', function () {

    const columns = [
        { data: 'inventory_number', className: 'text-center fw-medium', title: 'N° Inventario' },
        { data: 'model', className: 'fw-normal', title: 'Modelo' },
        { data: 'serial_number', className: 'fw-normal', title: 'N° Serie' },
        { data: 'brand.name', className: 'fw-normal', title: 'Marca', orderable: false }, // Deshabilitar orden en columnas de relación (más simple por ahora)
        { data: 'category.name', className: 'fw-normal', title: 'Categoría', orderable: false },
        {
            data: 'status.name',
            className: 'fw-normal',
            title: 'Disponibilidad',
            orderable: false,
            render: function (data) {
                return `<span class="badge bg-secondary rounded-pill px-3 py-1">${data}</span>`;
            }
        },
        {
            data: 'is_active_label',
            className: 'text-center',
            title: 'Estado',
            render: function (data, type, row) {
                const badgeClass = row.is_active ? 'bg-success' : 'bg-danger';
                return `<span class="badge ${badgeClass} rounded-pill px-3 py-1">${data}</span>`;
            }
        },
        {
            data: 'acquisition_date',
            className: 'text-center',
            title: 'Fecha Adquisición',
            render: function (data) {
                if (!data) return '';
                return data.split('T')[0].split('-').reverse().join('/');
            }
        },
        {
            data: 'model_year',
            className: 'text-center',
            title: 'Año Modelo',
            render: function (data) {
                return data || '';
            }
        },
        {
            data: null,
            className: 'text-center',
            orderable: false,
            title: 'Acciones',
            render: (data, type, row) => `
                <button
                    class="btn btn-outline-info btn-sm mx-1 btn-ver"
                    title="Ver"
                    data-id="${row.id}">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-outline-primary btn-sm mx-1 btn-modal-bien"
                    data-mode="edit"
                    data-id="${row.id}">
                    <i class="fas fa-edit"></i>
                </button>
                <button
                    class="btn btn-outline-danger btn-sm mx-1 btn-delete-asset"
                    data-id="${row.id}"
                    title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            `
        }
    ];

    tableApi = bottomTableConfig('file_export', [], columns, '[title]', {
        ajaxUrl: vURIAssetsTableApi,
        csrfToken: csrfToken,
        ajaxDataFn: (d) => {
            // Agregar parámetros personalizados
            d.option = 'table';
            d.filtroGeneral = $('#filtroGeneral').val();
            d.filtroCondicion = $('#filtroCondicion').val();
            d.filtroEstado = $('#filtroEstado').val();
            d.filtroCategoria = $('#filtroCategoria').val();
            d.filtroMarca = $('#filtroMarca').val();
            d.filtroAnioModelo = $('#filtroAnioModelo').val();
            d.filtroFechaAdquisicion = $('#filtroFechaAdquisicion').val();
            return JSON.stringify(d);
        }
    });

    $('#filtroCategoria').select2({
        theme: 'bootstrap-5',
        multiple: false,
        allowClear: true,
        placeholder: "Todas",
    });

    $('#filtroMarca').select2({
        theme: 'bootstrap-5',
        multiple: false,
        allowClear: true,
        placeholder: "Todas",
    });

    $('#filtroCondicion').select2({
        theme: 'bootstrap-5',
        multiple: false,
        allowClear: true,
        placeholder: "Todas",
    });

    $('#filtroEstado').select2({
        theme: 'bootstrap-5',
        multiple: false,
        allowClear: true,
        placeholder: "Todas",
        dropdownParent: $('#filtroEstado').parent()
    });

    $('#filtroGeneral').on('keyup', function () {
        tableApi.draw();
    });

    $('#filtroCondicion').on('change', function () {
        tableApi.draw();
    });

    $('#filtroEstado').on('change', function () {
        tableApi.draw();
    });

    $('#filtroCategoria').on('change', function () {
        tableApi.draw();
    });

    $('#filtroMarca').on('change', function () {
        tableApi.draw();
    });

    $('#filtroAnioModelo').on('input', function () {
        tableApi.draw();
    });

    $('#filtroFechaAdquisicion').on('change', function () {
        tableApi.draw();
    });

    $('#btnLimpiarFiltros').on('click', function () {

        $('#filtroGeneral').val('');
        $('#filtroCondicion').val('').trigger('change');
        $('#filtroEstado').val('').trigger('change');
        $('#filtroCategoria').val('').trigger('change');
        $('#filtroMarca').val('').trigger('change');
        $('#filtroAnioModelo').val('');
        $('#filtroFechaAdquisicion').val('');

        tableApi.draw();
    });
});
