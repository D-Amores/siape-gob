async function loadReportsTracking(){
    const reports =  await getAssetReports('tracking');
    loadReportsTable(reports)
}

async function startApp(){
    const dataReportsTableBody = document.querySelector('#tracking-table tbody');
    const reports =  await getAssetReports('tracking');
    loadReportsTable(reports);

    const statuses = await getStatuses();
        console.log('Estados obtenidos:', statuses);

    forceCloseModalWithRemoveId('btnCloseModalTrackingEdit', 'modalTrackingEdit', 'btnOpenModalTrackingEdit');
    forceCloseModalWithRemoveId('btnCloseModalTrackingClose', 'modalTrackingClose', 'btnOpenModalTrackingClose');

    dataReportsTableBody.addEventListener('click', async (e)=>{
        const btnEdit = e.target.closest('.btn-edit');
        if (btnEdit) {
            btnEdit.id = 'btnOpenModalTrackingEdit';
            const reportId = btnEdit.getAttribute('data-id');
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