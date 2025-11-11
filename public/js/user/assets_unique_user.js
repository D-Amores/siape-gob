document.addEventListener('DOMContentLoaded', async function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Definir columnas para la tabla
    const columns = [
        { 
            data: 'inventory_number', 
            className: 'text-center',
            render: function(data, type, row) {
                return data; 
            }
        },
        { 
            data: 'model',
            render: function(data, type, row) {
                return data; 
            }
        },
        { 
            data: 'serial_number',
            render: function(data, type, row) {
                return data; 
            }
        },
        { 
            data: 'brand',
            render: function(data, type, row) {
                return data; 
            }
        },
        { 
            data: 'category',
            render: function(data, type, row) {
                return data; 
            }
        },
        { 
            data: 'status', 
            className: 'text-center',
            render: function(data, type, row) {
                const badgeClass = data === 'Activo' ? 'bg-success' : 'bg-secondary';
                return `<span class="badge ${badgeClass}">${data}</span>`;
            }
        },
        {
            data: 'id',
            className: 'text-center',
            render: function(data, type, row) {
                return `
                    <button class="btn btn-info btn-sm detalles-btn me-1" data-id="${data}">
                        <i class="fas fa-eye me-1"></i> Ver
                    </button>
                    <button class="btn btn-warning btn-sm reportar-btn" 
                        data-id="${data}" 
                        data-inventory="${row.inventory_number}">
                        <i class="fas fa-flag me-1"></i> Reportar
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
        const btnDescargarRespaldo = document.getElementById('btn-descargar-respaldo');
        
        // Ocultar ambos botones inicialmente
        btnDescargar.style.display = 'none';
        btnDescargarRespaldo.style.display = 'none';
        
        if (assetDetails.path_acceptance_doc && 
            assetDetails.path_acceptance_doc !== 'No disponible' && 
            assetDetails.path_acceptance_doc.toLowerCase() !== 'pending') {
            
            documentoElement.textContent = 'Documento de aceptación disponible';

            // Mostrar solo botón de descargar documento de aceptación
            btnDescargar.style.display = 'inline-block';
            btnDescargar.disabled = false;
            btnDescargar.innerHTML = '<i class="fas fa-download me-1"></i> Descargar Documento de Aceptación';
            btnDescargar.onclick = function() {
                window.open(`${vURIDownloadDocument}/${assignmentId}`, '_blank');
            };

        } 
        // Caso: Pendiente -> Subir documento
        else if (assetDetails.path_acceptance_doc && 
            assetDetails.path_acceptance_doc.toLowerCase() === 'pending') {
            
            documentoElement.textContent = 'Documento pendiente de carga';
            
            // Botón para subir documento
            btnDescargar.style.display = 'inline-block';
            btnDescargar.disabled = false;
            btnDescargar.innerHTML = '<i class="fas fa-upload me-1"></i> Subir documento de aceptación';
            btnDescargar.onclick = function() {
                openUploadModal(assignmentId);
            };
            
            // Botón para descargar documento de respaldo (si existe)
            if (assetDetails.path_respaldo_acceptance && 
                assetDetails.path_respaldo_acceptance !== 'No disponible' &&
                assetDetails.path_respaldo_acceptance !== 'Firmado') {
                
                btnDescargarRespaldo.style.display = 'inline-block';
                btnDescargarRespaldo.disabled = false;
                btnDescargarRespaldo.innerHTML = '<i class="fas fa-file-pdf me-1"></i> Descargar Documento Generado';
                btnDescargarRespaldo.onclick = function() {
                    const url = `${vURIDownloadRespaldoDocument}/${assignmentId}`;
                    window.open(url, '_blank');
                };
            }
        } 
        // Caso: No disponible
        else {
            documentoElement.textContent = 'No disponible';
            btnDescargar.style.display = 'inline-block';
            btnDescargar.disabled = true;
            btnDescargar.innerHTML = '<i class="fas fa-download me-1"></i> Descargar Documento';
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

    // Función para abrir el selector de archivos
    function openUploadModal(assignmentId) {
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = '.pdf,.doc,.docx,.jpg,.jpeg,.png';
        fileInput.style.display = 'none';
        
        fileInput.onchange = async (e) => {
            const file = e.target.files[0];
            if (file) {
                await handleFileUpload(assignmentId, file);
            }
        };
        
        document.body.appendChild(fileInput);
        fileInput.click();
        document.body.removeChild(fileInput);
    }

    // Función para manejar la subida del archivo
    async function handleFileUpload(assignmentId, file) {
        const formData = new FormData();
        formData.append('acceptance_document', file);
        
        try {
            // Mostrar loading
            const btnDescargar = document.getElementById('btn-descargar-documento');
            const btnDescargarRespaldo = document.getElementById('btn-descargar-respaldo');
            const originalText = btnDescargar.innerHTML;
            btnDescargar.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Subiendo...';
            btnDescargar.disabled = true;

            const response = await fetch(`${vURIAssetsDetails}/${assignmentId}/upload-document`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                body: formData
            });

            const data = await response.json();

            if (data.ok) {
                showAlert('Documento subido correctamente', 'green', 'Éxito');
                
                // Actualizar la interfaz
                document.getElementById('detalle-documento').textContent = 'Documento de aceptación disponible';
                document.getElementById('detalle-fecha-confirmacion').textContent = new Date().toLocaleDateString('es-MX');
                
                // Cambiar el botón a "Descargar Documento de Aceptación"
                btnDescargar.innerHTML = '<i class="fas fa-download me-1"></i> Descargar Documento de Aceptación';
                btnDescargar.disabled = false;
                btnDescargar.onclick = function() {
                    window.open(`${vURIDownloadDocument}/${assignmentId}`, '_blank');
                };
                
                // Ocultar el botón de descargar respaldo
                btnDescargarRespaldo.style.display = 'none';
                
                // Actualizar el estado de la asignación
                const estadoAsignacion = document.getElementById('detalle-estado-asignacion');
                estadoAsignacion.textContent = 'Confirmada';
                estadoAsignacion.className = 'badge rounded-pill px-3 py-2 bg-success';
                
            } else {
                showAlert(data.message || 'Error al subir el documento', 'red', 'Error');
                btnDescargar.innerHTML = originalText;
                btnDescargar.disabled = false;
            }

        } catch (error) {
            console.error('Error:', error);
            showAlert('Error al subir el documento', 'red', 'Error');
            
            const btnDescargar = document.getElementById('btn-descargar-documento');
            btnDescargar.innerHTML = '<i class="fas fa-upload me-1"></i> Subir documento de aceptación';
            btnDescargar.disabled = false;
        }
    }

    // --------------------------
    // Reporte de Bienes
    // --------------------------
    
    // Función para abrir el modal de reporte
    async function openReportModal(assignmentId, assetInventoryNumber) {
        try {
            // Cargar los detalles completos del bien para obtener la información del personal
            const assetDetails = await loadAssetDetails(assignmentId);
            
            if (!assetDetails) {
                showAlert('Error al cargar los detalles del bien', "red", "Error");
                return;
            }

            // Verificar que tenemos la información del receptor
            if (!assetDetails.receiver_id || !assetDetails.receiver_name) {
                showAlert('No se pudo obtener la información del personal asignado', "red", "Error");
                return;
            }

            // Obtener el asset_id real de la tabla
            const assetId = await getAssetIdFromTable(assignmentId);
        
            if (!assetId) {
                showAlert('No se pudo obtener el ID del activo', "red", "Error");
                return;
            }

            // Llenar los campos del modal con los datos del registro
            document.getElementById('selectBien').value = assetInventoryNumber;
            document.getElementById('selectPersonal').value = assetDetails.receiver_name;
            
            // Guardar los IDs en data attributes para usarlos en el envío
            const modal = document.getElementById('modalReportesUnicoUsuario');
            modal.setAttribute('data-asset-id', assetId);
            modal.setAttribute('data-personal-id', assetDetails.receiver_id);

            
            // Abrir el modal usando el helper
            openModalForEdit('modalReportesUnicoUsuario');
            
        } catch (error) {
            console.error('Error al abrir modal de reporte:', error);
            showAlert('Error al preparar el reporte', "red", "Error");
        }
    }
    

    async function getAssetIdFromTable(assignmentId) {
        try {
            // Obtener la tabla DataTable
            const table = $('#assets_unique_user').DataTable();
            if (!table) return null;
            
            // Buscar la fila que corresponde al assignmentId
            const data = table.rows().data();
            for (let i = 0; i < data.length; i++) {
                const row = data[i];
                if (row.id == assignmentId) {
                    // Ahora que la API devuelve asset_id, lo podemos usar
                    return row.asset_id || null;
                }
            }
            return null;
        } catch (error) {
            console.error('Error al obtener asset_id de la tabla:', error);
            return null;
        }
    }

    // Función para limpiar el modal cuando se cierre
    function clearReportModal() {
        document.getElementById('selectBien').value = '';
        document.getElementById('selectPersonal').value = '';
        document.getElementById('descripcionReporte').value = '';
        
        const modal = document.getElementById('modalReportesUnicoUsuario');
        modal.removeAttribute('data-asset-id');
        modal.removeAttribute('data-personal-id');
    }

    // Agregar event listener para los botones de reportar - CORREGIDO
    document.addEventListener('click', function(e) {
        if (e.target.closest('.reportar-btn')) {
            const button = e.target.closest('.reportar-btn');
            const assetId = button.getAttribute('data-id');
            const inventoryNumber = button.getAttribute('data-inventory');
            
            openReportModal(assetId, inventoryNumber);
        }
    });

    // Configurar los event listeners para cerrar el modal
    closeModal('btnCerrarModalReporte', 'modalReportesUnicoUsuario', 'btnCerrarFooterReporte');
    closeModal('btnCerrarFooterReporte', 'modalReportesUnicoUsuario', 'btnCerrarFooterReporte');
    
    // Limpiar el modal cuando se cierre
    document.getElementById('modalReportesUnicoUsuario').addEventListener('hidden.bs.modal', function() {
        clearReportModal();
    });

    // Event listener para el envío del formulario
    document.getElementById('formReportarBien').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const modal = document.getElementById('modalReportesUnicoUsuario');
        const assetId = modal.getAttribute('data-asset-id');
        const personalId = modal.getAttribute('data-personal-id');
        const description = document.getElementById('descripcionReporte').value;
        
        // Validaciones básicas
        if (!assetId || !personalId) {
            showAlert('Error: No se encontró la información del bien o personal', "red", "Error");
            return;
        }
        
        if (!description.trim()) {
            showAlert('Por favor ingresa una descripción del reporte', "red", "Error");
            document.getElementById('descripcionReporte').focus();
            return;
        }
        
        try {
            // Mostrar loading - SELECTOR CORREGIDO
            const submitBtn = document.querySelector('button[form="formReportarBien"]');
            if (!submitBtn) {
                showAlert('Error: No se pudo encontrar el botón de envío', "red", "Error");
                return;
            }
            
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enviando...';
            submitBtn.disabled = true;
            
            // Preparar datos para enviar
            const requestData = {
                asset_id: assetId,
                reported_by: personalId,
                description: description
            };
            
            const response = await fetch(URIReport, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            });
            
            const data = await response.json();
            
            if (data.ok) {
                showAlert('Reporte creado exitosamente', "green", "Éxito");
                clearReportModal();
                closeModalForSuccess('modalReportesUnicoUsuario', 'btnCerrarFooterReporte');
            } else {
                showAlert(data.message || 'Error al crear el reporte', "red", "Error");
            }
            
        } catch (error) {
            console.error('Error al enviar reporte:', error);
            showAlert('Error de conexión al enviar el reporte', "red", "Error");
        } finally {
            // Restaurar botón - SELECTOR CORREGIDO
            const submitBtn = document.querySelector('button[form="formReportarBien"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Enviar Reporte';
                submitBtn.disabled = false;
            }
        }
    });

    await loadAssetsUniqueUser();
});