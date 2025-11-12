async function loadReportsTracking(){
    const reports =  await getAssetReports('tracking');
    loadReportsTable(reports)
}


async function statusToSelect() {
    const statuses = await getStatuses();
    const statusSelects = document.querySelectorAll('.status-select');

    statusSelects.forEach(select => {
        // Limpiar opciones existentes
        select.innerHTML = '';

        // Agregar una opción por cada estado
        statuses.forEach(status => {
            const option = document.createElement('option');
            option.value = status.id;
            option.textContent = status.name;
            select.appendChild(option);
        });
    });
    
}

async function trackingUpdate() {
    if (!isTrackingFormValid('#trackingUpdateForm')) return;

    const formData = new FormData(document.getElementById('trackingUpdateForm'));
    const btnTrackingEdit = document.getElementById('btnTrackingEdit');
    const trackingEditSpinner = document.getElementById('trackingEditSpinner');

    const data = {};
    let maintenanceReportId = formData.get('maintenance_report_id');
    formData.forEach((value, key) => {
        const trimmedValue = value.trim();
        if (key === "maintenance_report_id") {
            maintenanceReportId = trimmedValue; // Guardamos aparte
        }else {
            data[key] = trimmedValue;
        }
    });

    confirmUpdate(async () => {
        trackingEditSpinner.classList.remove('d-none');
        btnTrackingEdit.disabled = true;
        const isOk = await updateAssetTracking(maintenanceReportId, data);
        if (isOk) {
            closeModalForSuccess('modalTrackingEdit', 'btnOpenModalTrackingEdit');
            resetFormAndSelect(document.getElementById('trackingUpdateForm'));
            await statusToSelect();
            await loadReportsTracking(); // Recargar tabla de reportes
        }
        trackingEditSpinner.classList.add('d-none');
        btnTrackingEdit.disabled = false;
    },
    '¿Estás seguro de actualizar el seguimiento del activo?');
}

async function startApp(){
    const dataReportsTableBody = document.querySelector('#tracking-table tbody');
    const btnUpdateTracking = document.getElementById('btnTrackingEdit');
    const reports =  await getAssetReports('tracking');
    loadReportsTable(reports);

    await statusToSelect();

    forceCloseModalWithRemoveId('btnCloseModalTrackingEdit', 'modalTrackingEdit', 'btnOpenModalTrackingEdit');
    forceCloseModalWithRemoveId('btnCloseModalTrackingClose', 'modalTrackingClose', 'btnOpenModalTrackingClose');
    
    btnUpdateTracking.addEventListener('click', trackingUpdate);
    dataReportsTableBody.addEventListener('click', async (e)=>{
        const btnEdit = e.target.closest('.btn-edit');
        if (btnEdit) {
            btnEdit.id = 'btnOpenModalTrackingEdit';
            const reportId = btnEdit.getAttribute('data-id');
            const maintenanceReportId = document.getElementById('maintenance_report_id_update');
            maintenanceReportId.value = reportId;
            openModalForEdit("modalTrackingEdit");
            console.log(reportId);
            
            //await editAssetTracking(reportId);

        //     btnCreate.removeAttribute('id');
        //     console.log('click en crear seguimiento para el reporte:', reportId);

        }

        const btnClose = e.target.closest('.btn-chk');
        if (btnClose) {
            btnClose.id = 'btnOpenModalTrackingClose';
            const reportId = btnClose.getAttribute('data-id');
            openModalForEdit("modalTrackingClose");
            console.log(reportId);
        }
    });
}

document.addEventListener('DOMContentLoaded', startApp);