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
            d.filtroCategoria = categoriasSeleccionadas;
            d.filtroMarca = marcasSeleccionadas;
            d.filtroAnioModelo = $('#filtroAnioModelo').val();
            d.filtroFechaAdquisicion = $('#filtroFechaAdquisicion').val();
            return JSON.stringify(d);
        }
    });

    // Arrays para almacenar las categorías y marcas seleccionadas
    let categoriasSeleccionadas = [];
    let marcasSeleccionadas = [];

    $('#filtroCategoria').select2({
        theme: 'bootstrap-5',
        allowClear: true,
        placeholder: "Seleccione..."
    });

    // Función para actualizar el contador de filtros activos
    function actualizarContadorFiltros() {
        let contador = 0;

        // Contar filtros simples activos
        if ($('#filtroGeneral').val()) contador++;
        if ($('#filtroCondicion').val()) contador++;
        if ($('#filtroEstado').val()) contador++;
        if ($('#filtroAnioModelo').val()) contador++;
        if ($('#filtroFechaAdquisicion').val()) contador++;

        // Contar categorías y marcas seleccionadas
        contador += categoriasSeleccionadas.length;
        contador += marcasSeleccionadas.length;

        // Actualizar el badge contador
        const badgeContador = document.getElementById('contadorFiltros');
        if (contador > 0) {
            badgeContador.textContent = contador;
            badgeContador.style.display = 'inline-block';
        } else {
            badgeContador.style.display = 'none';
        }
    }

    // Función para actualizar los badges de categorías
    function actualizarBadgesCategorias() {
        const container = document.getElementById('categoriasSeleccionadas');
        const containerPrincipal = document.getElementById('categoriasSeleccionadasContainer');
        const badgesContainer = document.getElementById('badgesContainer');

        // Limpiar completamente el contenedor
        container.innerHTML = '';

        if (categoriasSeleccionadas.length === 0) {
            containerPrincipal.style.display = 'none';
        } else {
            containerPrincipal.style.display = 'block';

            categoriasSeleccionadas.forEach(categoria => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-info text-white d-inline-flex align-items-center gap-2 pe-2';
                badge.style.fontSize = '0.875rem';

                const texto = document.createElement('span');
                texto.textContent = categoria;

                const botonCerrar = document.createElement('span');
                botonCerrar.className = 'btn-cerrar-categoria';
                botonCerrar.style.cursor = 'pointer';
                botonCerrar.style.fontWeight = 'bold';
                botonCerrar.style.fontSize = '1.1rem';
                botonCerrar.dataset.categoria = categoria;
                botonCerrar.innerHTML = '&times;';

                badge.appendChild(texto);
                badge.appendChild(botonCerrar);
                container.appendChild(badge);
            });
        }

        // Mostrar u ocultar el contenedor de badges
        if (categoriasSeleccionadas.length > 0 || marcasSeleccionadas.length > 0) {
            badgesContainer.style.display = 'block';
        } else {
            badgesContainer.style.display = 'none';
        }

        // Actualizar contador
        actualizarContadorFiltros();
    }

    // Evento cuando se selecciona una categoría
    $('#filtroCategoria').on('change', function(e) {
        const valorSeleccionado = $(this).val();

        if (valorSeleccionado && !categoriasSeleccionadas.includes(valorSeleccionado)) {
            categoriasSeleccionadas.push(valorSeleccionado);
            actualizarBadgesCategorias();
            tableApi.draw();

            // Resetear el select sin disparar eventos (evita bucle infinito)
            $(this).val('');
        }
    });

    // Función para actualizar los badges de marcas
    function actualizarBadgesMarcas() {
        const container = document.getElementById('marcasSeleccionadas');
        const containerPrincipal = document.getElementById('marcasSeleccionadasContainer');
        const badgesContainer = document.getElementById('badgesContainer');

        // Limpiar completamente el contenedor
        container.innerHTML = '';

        if (marcasSeleccionadas.length === 0) {
            containerPrincipal.style.display = 'none';
        } else {
            containerPrincipal.style.display = 'block';

            marcasSeleccionadas.forEach(marca => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-success text-white d-inline-flex align-items-center gap-2 pe-2';
                badge.style.fontSize = '0.875rem';

                const texto = document.createElement('span');
                texto.textContent = marca;

                const botonCerrar = document.createElement('span');
                botonCerrar.className = 'btn-cerrar-marca';
                botonCerrar.style.cursor = 'pointer';
                botonCerrar.style.fontWeight = 'bold';
                botonCerrar.style.fontSize = '1.1rem';
                botonCerrar.dataset.marca = marca;
                botonCerrar.innerHTML = '&times;';

                badge.appendChild(texto);
                badge.appendChild(botonCerrar);
                container.appendChild(badge);
            });
        }

        // Mostrar u ocultar el contenedor de badges
        if (categoriasSeleccionadas.length > 0 || marcasSeleccionadas.length > 0) {
            badgesContainer.style.display = 'block';
        } else {
            badgesContainer.style.display = 'none';
        }

        // Actualizar contador
        actualizarContadorFiltros();
    }

    // Evento para eliminar una categoría desde el badge
    $(document).on('click', '.btn-cerrar-categoria', function() {
        const categoria = $(this).data('categoria');
        categoriasSeleccionadas = categoriasSeleccionadas.filter(c => c !== categoria);
        actualizarBadgesCategorias();
        tableApi.draw();
    });

    // Evento para eliminar una marca desde el badge
    $(document).on('click', '.btn-cerrar-marca', function() {
        const marca = $(this).data('marca');
        marcasSeleccionadas = marcasSeleccionadas.filter(m => m !== marca);
        actualizarBadgesMarcas();
        tableApi.draw();
    });

    $('#filtroMarca').select2({
        theme: 'bootstrap-5',
        allowClear: true,
        placeholder: "Seleccione..."
    });

    // Evento cuando se selecciona una marca
    $('#filtroMarca').on('change', function(e) {
        const valorSeleccionado = $(this).val();

        if (valorSeleccionado && !marcasSeleccionadas.includes(valorSeleccionado)) {
            marcasSeleccionadas.push(valorSeleccionado);
            actualizarBadgesMarcas();
            tableApi.draw();

            // Resetear el select sin disparar eventos
            $(this).val('');
        }
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
        actualizarContadorFiltros();
        tableApi.draw();
    });

    $('#filtroCondicion').on('change', function () {
        actualizarContadorFiltros();
        tableApi.draw();
    });

    $('#filtroEstado').on('change', function () {
        actualizarContadorFiltros();
        tableApi.draw();
    });

    $('#filtroAnioModelo').on('input', function () {
        actualizarContadorFiltros();
        tableApi.draw();
    });

    $('#filtroFechaAdquisicion').on('change', function () {
        actualizarContadorFiltros();
        tableApi.draw();
    });

    $('#btnLimpiarFiltros').on('click', function () {
        // Limpiar campos de texto simples
        $('#filtroGeneral').val('');
        $('#filtroAnioModelo').val('');
        $('#filtroFechaAdquisicion').val('');

        // Limpiar Select2 de categoría de forma especial (para evitar el bucle)
        const $filtroCategoria = $('#filtroCategoria');
        $filtroCategoria.val(null);
        $filtroCategoria.select2('destroy');
        $filtroCategoria.select2({
            theme: 'bootstrap-5',
            allowClear: true,
            placeholder: "Seleccione..."
        });

        // Limpiar Select2 de marca de forma especial
        const $filtroMarca = $('#filtroMarca');
        $filtroMarca.val(null);
        $filtroMarca.select2('destroy');
        $filtroMarca.select2({
            theme: 'bootstrap-5',
            allowClear: true,
            placeholder: "Seleccione..."
        });

        // Limpiar otros selects con Select2
        $('#filtroCondicion').val(null).trigger('change');
        $('#filtroEstado').val(null).trigger('change');

        // Limpiar categorías seleccionadas
        categoriasSeleccionadas.length = 0;
        const containerCat = document.getElementById('categoriasSeleccionadas');
        const containerPrincipalCat = document.getElementById('categoriasSeleccionadasContainer');
        if (containerCat) {
            containerCat.innerHTML = '';
        }
        if (containerPrincipalCat) {
            containerPrincipalCat.style.display = 'none';
        }

        // Limpiar marcas seleccionadas
        marcasSeleccionadas.length = 0;
        const containerMar = document.getElementById('marcasSeleccionadas');
        const containerPrincipalMar = document.getElementById('marcasSeleccionadasContainer');
        if (containerMar) {
            containerMar.innerHTML = '';
        }
        if (containerPrincipalMar) {
            containerPrincipalMar.style.display = 'none';
        }

        // Ocultar el contenedor principal de badges si no hay ninguno
        const badgesContainer = document.getElementById('badgesContainer');
        if (badgesContainer) {
            badgesContainer.style.display = 'none';
        }

        // Actualizar contador
        actualizarContadorFiltros();

        // Redibujar la tabla (esto recargará con valores vacíos)
        tableApi.draw();
    });
});
