// asset_reports_manager.js - Maneja la funcionalidad de reportes de bienes
const AssetReportsManager = (function() {
    let token;
    let URIReport;
    let vURIAssetsDetails;

    function init(config = {}) {
        token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        URIReport = config.URIReport;
        vURIAssetsDetails = config.vURIAssetsDetails;
        
        if (!URIReport) {
            console.error('URIReport no está definida. Los reportes no funcionarán.');
            return;
        }

        setupReportForm();
    }

    async function openReportModal(assignmentId, assetInventoryNumber) {
        try {
            const assetDetails = await AssetDetailsManager.loadAssetDetails(assignmentId);
            
            if (!assetDetails) {
                showAlert('Error al cargar los detalles del bien', "red", "Error");
                return;
            }

            const assetId = await getAssetIdFromTable(assignmentId);
        
            if (!assetId) {
                showAlert('No se pudo obtener el ID del activo', "red", "Error");
                return;
            }

            document.getElementById('selectBien').value = assetInventoryNumber;
            document.getElementById('selectPersonal').value = assetDetails.receiver_name;
            
            const modal = document.getElementById('modalReportesUnicoUsuario');
            modal.setAttribute('data-asset-id', assetId);
            
            openModalForEdit('modalReportesUnicoUsuario');
            
        } catch (error) {
            console.error('Error al abrir modal de reporte:', error);
            showAlert('Error al preparar el reporte', "red", "Error");
        }
    }

    async function getAssetIdFromTable(assignmentId) {
        try {
            const table = $('#assets_unique_user').DataTable();
            if (!table) return null;
            
            const data = table.rows().data();
            for (let i = 0; i < data.length; i++) {
                const row = data[i];
                if (row.id == assignmentId) {
                    return row.asset_id || null;
                }
            }
            return null;
        } catch (error) {
            console.error('Error al obtener asset_id de la tabla:', error);
            return null;
        }
    }

    function clearReportModal() {
        document.getElementById('selectBien').value = '';
        document.getElementById('selectPersonal').value = '';
        document.getElementById('descripcionReporte').value = '';
        
        const modal = document.getElementById('modalReportesUnicoUsuario');
        modal.removeAttribute('data-asset-id');
        modal.removeAttribute('data-personal-id');
    }

    function setupReportForm() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.reportar-btn')) {
                const button = e.target.closest('.reportar-btn');
                const assetId = button.getAttribute('data-id');
                const inventoryNumber = button.getAttribute('data-inventory');
                
                openReportModal(assetId, inventoryNumber);
            }
        });

        closeModal('btnCerrarModalReporte', 'modalReportesUnicoUsuario', 'btnCerrarFooterReporte');
        closeModal('btnCerrarFooterReporte', 'modalReportesUnicoUsuario', 'btnCerrarFooterReporte');
        
        document.getElementById('modalReportesUnicoUsuario').addEventListener('hidden.bs.modal', function() {
            clearReportModal();
        });

        // Event listener para el envío del formulario - CORREGIDO
        document.getElementById('formReportarBien').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const modal = document.getElementById('modalReportesUnicoUsuario');
            const assetId = modal.getAttribute('data-asset-id');
            const description = document.getElementById('descripcionReporte').value;
            
            if (!assetId) {
                showAlert('Error: No se encontró la información del bien', "red", "Error");
                return;
            }
            
            if (!description.trim()) {
                showAlert('Por favor ingresa una descripción del reporte', "red", "Error");
                document.getElementById('descripcionReporte').focus();
                return;
            }
            
            try {
                const submitBtn = document.querySelector('button[form="formReportarBien"]');
                if (!submitBtn) {
                    showAlert('Error: No se pudo encontrar el botón de envío', "red", "Error");
                    return;
                }
                
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enviando...';
                submitBtn.disabled = true;
                
                // SOLO enviar asset_id y description - CORREGIDO
                const requestData = {
                    asset_id: assetId,
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
                
                if (data.ok || response.status === 200) {
                    showAlert('Reporte creado exitosamente', "green", "Éxito");
                    clearReportModal();
                    closeModalForSuccess('modalReportesUnicoUsuario', 'btnCerrarFooterReporte');
                    
                    if (typeof reloadAssetsTable === 'function') {
                        reloadAssetsTable();
                    }
                } else {
                    // Mostrar mensaje del servidor para errores controlados
                    showAlert(data.message || 'Error al crear el reporte', "red", "Error");
                }
                
            } catch (error) {
                console.error('Error al enviar reporte:', error);
                showAlert('Error de conexión al enviar el reporte', "red", "Error");
            } finally {
                const submitBtn = document.querySelector('button[form="formReportarBien"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Enviar Reporte';
                    submitBtn.disabled = false;
                }
            }
        });
    }

    return {
        init: init
    };
})();