// Módulo de filtros para la tabla de bienes
// Depende de jQuery, Select2 y DataTables ya cargados en la página.

window.assetsFilters = (function () {
    // Arrays para almacenar las categorías y marcas seleccionadas
    let categoriasSeleccionadas = [];
    let marcasSeleccionadas = [];
    let tableApi = null;

    function getSelectedFilters() {
        return {
            filtroGeneral: $('#filtroGeneral').val(),
            filtroCondicion: $('#filtroCondicion').val(),
            filtroEstado: $('#filtroEstado').val(),
            filtroCategoria: categoriasSeleccionadas,
            filtroMarca: marcasSeleccionadas,
            filtroAnioModelo: $('#filtroAnioModelo').val(),
            filtroFechaAdquisicion: $('#filtroFechaAdquisicion').val(),
        };
    }

    // Función para actualizar el contador de filtros activos
    function actualizarContadorFiltros() {
        let contador = 0;

        const filtros = getSelectedFilters();

        if (filtros.filtroGeneral) contador++;
        if (filtros.filtroCondicion) contador++;
        if (filtros.filtroEstado) contador++;
        if (filtros.filtroAnioModelo) contador++;
        if (filtros.filtroFechaAdquisicion) contador++;

        contador += categoriasSeleccionadas.length;
        contador += marcasSeleccionadas.length;

        const badgeContador = document.getElementById('contadorFiltros');
        if (!badgeContador) return;

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

        if (!container || !containerPrincipal || !badgesContainer) return;

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

        if (categoriasSeleccionadas.length > 0 || marcasSeleccionadas.length > 0) {
            badgesContainer.style.display = 'block';
        } else {
            badgesContainer.style.display = 'none';
        }

        actualizarContadorFiltros();
    }

    // Función para actualizar los badges de marcas
    function actualizarBadgesMarcas() {
        const container = document.getElementById('marcasSeleccionadas');
        const containerPrincipal = document.getElementById('marcasSeleccionadasContainer');
        const badgesContainer = document.getElementById('badgesContainer');

        if (!container || !containerPrincipal || !badgesContainer) return;

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

        if (categoriasSeleccionadas.length > 0 || marcasSeleccionadas.length > 0) {
            badgesContainer.style.display = 'block';
        } else {
            badgesContainer.style.display = 'none';
        }

        actualizarContadorFiltros();
    }

    function initSelects() {
        if ($('#filtroCategoria').length) {
            $('#filtroCategoria').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: 'Seleccione...'
            });
        }

        if ($('#filtroMarca').length) {
            $('#filtroMarca').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: 'Seleccione...'
            });
        }

        if ($('#filtroCondicion').length) {
            $('#filtroCondicion').select2({
                theme: 'bootstrap-5',
                multiple: false,
                allowClear: true,
                placeholder: 'Todas'
            });
        }

        if ($('#filtroEstado').length) {
            $('#filtroEstado').select2({
                theme: 'bootstrap-5',
                multiple: false,
                allowClear: true,
                placeholder: 'Todas',
                dropdownParent: $('#filtroEstado').parent()
            });
        }
    }

    function bindEvents() {
        // Categoría múltiple
        $('#filtroCategoria').on('change', function () {
            const valorSeleccionado = $(this).val();

            if (valorSeleccionado && !categoriasSeleccionadas.includes(valorSeleccionado)) {
                categoriasSeleccionadas.push(valorSeleccionado);
                actualizarBadgesCategorias();
                if (tableApi) tableApi.draw();
                $(this).val('');
            }
        });

        // Marca múltiple
        $('#filtroMarca').on('change', function () {
            const valorSeleccionado = $(this).val();

            if (valorSeleccionado && !marcasSeleccionadas.includes(valorSeleccionado)) {
                marcasSeleccionadas.push(valorSeleccionado);
                actualizarBadgesMarcas();
                if (tableApi) tableApi.draw();
                $(this).val('');
            }
        });

        // Eliminar badges
        $(document).on('click', '.btn-cerrar-categoria', function () {
            const categoria = $(this).data('categoria');
            categoriasSeleccionadas = categoriasSeleccionadas.filter(c => c !== categoria);
            actualizarBadgesCategorias();
            if (tableApi) tableApi.draw();
        });

        $(document).on('click', '.btn-cerrar-marca', function () {
            const marca = $(this).data('marca');
            marcasSeleccionadas = marcasSeleccionadas.filter(m => m !== marca);
            actualizarBadgesMarcas();
            if (tableApi) tableApi.draw();
        });

        // Filtros simples
        $('#filtroGeneral').on('keyup', function () {
            actualizarContadorFiltros();
            if (tableApi) tableApi.draw();
        });

        $('#filtroCondicion').on('change', function () {
            actualizarContadorFiltros();
            if (tableApi) tableApi.draw();
        });

        $('#filtroEstado').on('change', function () {
            actualizarContadorFiltros();
            if (tableApi) tableApi.draw();
        });

        $('#filtroAnioModelo').on('input', function () {
            actualizarContadorFiltros();
            if (tableApi) tableApi.draw();
        });

        $('#filtroFechaAdquisicion').on('change', function () {
            actualizarContadorFiltros();
            if (tableApi) tableApi.draw();
        });

        // Botón limpiar filtros
        $('#btnLimpiarFiltros').on('click', function () {
            $('#filtroGeneral').val('');
            $('#filtroAnioModelo').val('');
            $('#filtroFechaAdquisicion').val('');

            const $filtroCategoria = $('#filtroCategoria');
            $filtroCategoria.val(null);
            $filtroCategoria.select2('destroy');
            $filtroCategoria.select2({
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: 'Seleccione...'
            });

            const $filtroMarca = $('#filtroMarca');
            $filtroMarca.val(null);
            $filtroMarca.select2('destroy');
            $filtroMarca.select2({
                theme: 'bootstrap-5',
                allowClear: true,
                placeholder: 'Seleccione...'
            });

            $('#filtroCondicion').val(null).trigger('change');
            $('#filtroEstado').val(null).trigger('change');

            categoriasSeleccionadas.length = 0;
            const containerCat = document.getElementById('categoriasSeleccionadas');
            const containerPrincipalCat = document.getElementById('categoriasSeleccionadasContainer');
            if (containerCat) containerCat.innerHTML = '';
            if (containerPrincipalCat) containerPrincipalCat.style.display = 'none';

            marcasSeleccionadas.length = 0;
            const containerMar = document.getElementById('marcasSeleccionadas');
            const containerPrincipalMar = document.getElementById('marcasSeleccionadasContainer');
            if (containerMar) containerMar.innerHTML = '';
            if (containerPrincipalMar) containerPrincipalMar.style.display = 'none';

            const badgesContainer = document.getElementById('badgesContainer');
            if (badgesContainer) badgesContainer.style.display = 'none';

            actualizarContadorFiltros();
            if (tableApi) tableApi.draw();
        });
    }

    function init(dtInstance) {
        tableApi = dtInstance;
        initSelects();
        bindEvents();
        actualizarContadorFiltros();
    }

    return {
        init,
        getSelectedFilters,
    };
})();
