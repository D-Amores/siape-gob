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
    let maintenanceReportId = '';
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

async function trackingClose() {
    if (!isTrackingFormValid('#trackingCloseForm')) return;
    const formData = new FormData(document.getElementById('trackingCloseForm'));
    const btnTrackingClose = document.getElementById('btnTrackingClose');
    const trackingCloseSpinner = document.getElementById('trackingCloseSpinner');

    const data = {};
    let maintenanceReportId = '';
    formData.forEach((value, key) => {
        const trimmedValue = value.trim();
        if (key === "maintenance_report_id") {
            maintenanceReportId = trimmedValue; // Guardamos aparte
        }else {
            data[key] = trimmedValue;
        }
    });

    confirmDestroy(async() => {
        trackingCloseSpinner.classList.remove('d-none');
        btnTrackingClose.disabled = true;
        const isOk = await destroyAssetTracking(maintenanceReportId, data);
        if (isOk) {
            closeModalForSuccess('modalTrackingClose', 'btnOpenModalTrackingClose');
            resetFormAndSelect(document.getElementById('trackingCloseForm'));
            await statusToSelect();
            await loadReportsTracking(); // Recargar tabla de reportes
        }
        trackingCloseSpinner.classList.add('d-none');
        btnTrackingClose.disabled = false;
    }, '¿Estás seguro de finalizar el seguimiento del activo? Esta acción no se puede deshacer.');
}

async function startApp(){
    const dataReportsTableBody = document.querySelector('#tracking-table tbody');
    const btnUpdateTracking = document.getElementById('btnTrackingEdit');
    const btnCloseTracking = document.getElementById('btnTrackingClose');
    const reports =  await getAssetReports('tracking');
    loadReportsTable(reports);

    await statusToSelect();

    forceCloseModalWithRemoveId('btnCloseModalTrackingEdit', 'modalTrackingEdit', 'btnOpenModalTrackingEdit');
    forceCloseModalWithRemoveId('btnCloseModalTrackingClose', 'modalTrackingClose', 'btnOpenModalTrackingClose');
    
    btnUpdateTracking.addEventListener('click', trackingUpdate);
    btnCloseTracking.addEventListener('click', trackingClose);

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
            const maintenanceReportIdClose = document.getElementById('maintenance_report_id_close');
            maintenanceReportIdClose.value = reportId;
            openModalForEdit("modalTrackingClose");
            console.log(reportId);
        }
    });
}

document.addEventListener('DOMContentLoaded', startApp);