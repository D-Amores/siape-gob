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
            d.option = 'table';
            if (window.assetsFilters && typeof window.assetsFilters.getSelectedFilters === 'function') {
                const filtros = window.assetsFilters.getSelectedFilters();
                Object.assign(d, {
                    filtroGeneral: filtros.filtroGeneral,
                    filtroCondicion: filtros.filtroCondicion,
                    filtroEstado: filtros.filtroEstado,
                    filtroCategoria: filtros.filtroCategoria,
                    filtroMarca: filtros.filtroMarca,
                    filtroAnioModelo: filtros.filtroAnioModelo,
                    filtroFechaAdquisicion: filtros.filtroFechaAdquisicion,
                });
            }
            return JSON.stringify(d);
        }
    });

    if (window.assetsFilters && typeof window.assetsFilters.init === 'function') {
        window.assetsFilters.init(tableApi);
    }
});
