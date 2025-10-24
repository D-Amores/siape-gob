document.addEventListener('DOMContentLoaded', async function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Definir columnas para la tabla
    const columns = [
        { data: 'inventory_number', className: 'text-center' },
        { data: 'model' },
        { data: 'serial_number' },
        { data: 'brand' },
        { data: 'category' },
        { 
            data: 'status', 
            className: 'text-center',
            render: function(status) {
                const badgeClass = status === 'Activo' ? 'bg-success' : 'bg-secondary';
                return `<span class="badge ${badgeClass}">${status}</span>`;
            }
        },
        {
            data: 'id',
            className: 'text-center',
            render: function(id) {
                return `
                    <button class="btn btn-info btn-sm detalles-btn" data-id="${id}">
                        <i class="fas fa-eye me-1"></i> Ver
                    </button>
                `;
            }
        }
    ];

    // Cargar bienes del usuario
    async function loadAssetsUniqueUser() {
        try {
            const res = await fetch(vURIUniqueAssetsTableApi, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            });

            const data = await res.json();

            if (!data.ok) {
                showAlert(data.message, "red", "Error");
                bottomTableConfig('assets_unique_user', [], columns);
                return;
            }

            bottomTableConfig('assets_unique_user', data.data, columns, '.tooltipped');

        } catch (err) {
            showAlert('Error al cargar bienes del usuario', "red", "Error");
            bottomTableConfig('assets_unique_user', [], columns);
        }
    }

    // Función para cargar detalles del bien
    async function loadAssetDetails(assignmentId) {
        try {
            const res = await fetch(`${vURIAssetsDetails}/${assignmentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();

            if (!data.ok) {
                showAlert(data.message, "red", "Error");
                return null;
            }

            return data.data;

        } catch (err) {
            showAlert('Error al cargar detalles del bien', "red", "Error");
            return null;
        }
    }

    // Función para mostrar detalles en el modal
    async function showAssetDetails(assignmentId) {
        const assetDetails = await loadAssetDetails(assignmentId);
        
        if (!assetDetails) return;
        
        function formatDate(isoString) {
            if (!isoString) return '—';
            const date = new Date(isoString);
            return date.toLocaleString('es-MX', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
            });
        }

        // Información General
        document.getElementById('detalle-inventario').textContent = assetDetails.inventory_number;
        document.getElementById('detalle-modelo').textContent = assetDetails.model;
        document.getElementById('detalle-serie').textContent = assetDetails.serial_number;
        document.getElementById('detalle-marca').textContent = assetDetails.brand;
        document.getElementById('detalle-categoria').textContent = assetDetails.category;
        document.getElementById('detalle-tipo').textContent = assetDetails.type || '—';
        document.getElementById('detalle-creado').textContent = formatDate(assetDetails.created_at);
        
        // Estado del bien
        const estadoBadge = document.getElementById('detalle-estado');
        estadoBadge.textContent = assetDetails.status;
        estadoBadge.className = `badge rounded-pill px-3 py-2 ${assetDetails.status === 'Activo' ? 'bg-success' : 'bg-secondary'}`;

        // Especificaciones Técnicas
        document.getElementById('detalle-cpu').textContent = assetDetails.cpu;
        document.getElementById('detalle-velocidad').textContent = assetDetails.speed;
        document.getElementById('detalle-memoria').textContent = assetDetails.memory;
        document.getElementById('detalle-almacenamiento').textContent = assetDetails.storage;

        // Descripción
        const descripcionElement = document.getElementById('detalle-descripcion');
        descripcionElement.textContent = assetDetails.description;
        if (assetDetails.description === 'Sin descripción' || !assetDetails.description) {
            descripcionElement.classList.add('text-muted', 'fst-italic');
            descripcionElement.textContent = 'Sin descripción disponible';
        } else {
            descripcionElement.classList.remove('text-muted', 'fst-italic');
        }

        // Información de la Asignación
        document.getElementById('detalle-fecha-asignacion').textContent = assetDetails.assignment_date;
        document.getElementById('detalle-fecha-confirmacion').textContent = assetDetails.confirmation_date;
        document.getElementById('detalle-asignador').textContent = assetDetails.assigner_name;
        document.getElementById('detalle-receptor').textContent = assetDetails.receiver_name;
        
        // Estado de la asignación
        const estadoAsignacion = document.getElementById('detalle-estado-asignacion');
        const isConfirmed = assetDetails.confirmation_date && assetDetails.confirmation_date !== 'Pendiente';
        estadoAsignacion.textContent = isConfirmed ? 'Confirmada' : 'Pendiente';
        estadoAsignacion.className = `badge rounded-pill px-3 py-2 ${isConfirmed ? 'bg-success' : 'bg-warning'}`;

        // Documentación
        const documentoElement = document.getElementById('detalle-documento');
        const btnDescargar = document.getElementById('btn-descargar-documento');
        
        if (assetDetails.path_acceptance_doc && assetDetails.path_acceptance_doc !== 'No disponible') {
            documentoElement.textContent = 'Documento disponible';
            btnDescargar.disabled = false;
            btnDescargar.onclick = function() {
                // Aquí puedes implementar la descarga del documento
                window.open(`${BASE_URL}/download-document/${assignmentId}`, '_blank');
            };
        } else {
            documentoElement.textContent = 'No disponible';
            btnDescargar.disabled = true;
        }

        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('modalDetallesUnicoUsuario'));
        modal.show();
    }

    // Event listener para los botones de detalles
    document.addEventListener('click', function(e) {
        if (e.target.closest('.detalles-btn')) {
            const button = e.target.closest('.detalles-btn');
            const assignmentId = button.getAttribute('data-id');
            showAssetDetails(assignmentId);
        }
    });

    await loadAssetsUniqueUser();
});