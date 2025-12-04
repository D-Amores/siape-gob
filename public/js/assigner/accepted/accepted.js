async function filter(text) {
    const acceptedTableData = await acceptAssetApi('accepted', text);
    loadAssetsAcceptedBasicInfo(acceptedTableData);
}

async function downloadPdfReport(personnelId) {
    const result = await getPdfReport(personnelId);

    if (result.pdf) {
        const url = window.URL.createObjectURL(result.pdf);
        const a = document.createElement("a");
        a.href = url;
        a.download = "reporte_asignaciones.pdf";
        a.click();
        window.URL.revokeObjectURL(url);
        return;
    }

    showAlert('Error', 'No se pudo generar el reporte PDF.', 'error');
}


async function unassign(assetId, personnelId) {
    confirmDestroy(
        async () => {
            await unassignAsset(assetId);
            const detailData = await acceptAssetApi('details', null, personnelId);
            if (detailData.length == 0) {
                closeModalForSuccess('details-modal', 'btn-details-open-modal');
                const acceptedTableData = await acceptAssetApi('accepted');
                loadAssetsAcceptedBasicInfo(acceptedTableData);
            }else{
                loadAssignmentsList(detailData);
            }
        }, 
        '¿Está seguro de desasignar este activo? Esta acción no podrá ser revertida.',
        'Desasignar'
    );
}

async function startApp() {
    //const btnUnassign = document.querySelectorAll('.btn-unassign');
    const inputFilter = document.getElementById('filter-accepted-assignments');

    //const btnDetails = document.querySelectorAll('.btn-details');
    const btnGenerateReport = document.getElementById('details-generate-report');
    const assignedTableTbody = document.querySelector('#accepted-assignments-names tbody');
    const detailTableTbody = document.querySelector('#details-table tbody');
    const acceptedTableData = await acceptAssetApi('accepted');
    loadAssetsAcceptedBasicInfo(acceptedTableData);
    //loadAssetsAccepted(acceptedTableData); carga los datos de asignados

    forceCloseModalWithRemoveId('btn-details-close-modal', 'details-modal', 'btn-details-open-modal');


    let debounceTimeout;
    inputFilter.addEventListener('input', (e) => {
        const text = e.target.value;

        // Limpiar el timeout anterior
        clearTimeout(debounceTimeout);

        // Esperar 3 segundos antes de ejecutar filter
        debounceTimeout = setTimeout(async () => {
            if (text.length === 0 || text.length >= 3) {
                await filter(text);
            }
        }, 2000); // 2000 ms = 2 segundos
    });

    assignedTableTbody.addEventListener('click', async (e) => {
        const btnDetails = e.target.closest('.btn-details');
        if (btnDetails) {
            const personnelId = btnDetails.getAttribute('data-id');
            btnDetails.setAttribute('id', 'btn-details-open-modal');            
            const detailData = await acceptAssetApi('details', null, personnelId);
            loadAssignmentsList(detailData);
            const nameTitle = detailData.length > 0 ? detailData[0].receiver.name : 'Detalles de Asignación';
            document.getElementById('modal-receiver-name').innerText = nameTitle;
            const areaTitle = detailData.length > 0 ? detailData[0].receiver.area : '';
            document.getElementById('modal-receiver-area').innerText = areaTitle;
            document.getElementById('receiver-id').innerText = personnelId;
            openModalForEdit('details-modal');
        }
    });

    detailTableTbody.addEventListener('click', async (e) => {
        const btnUnassign = e.target.closest('.btn-unassign');
        if (btnUnassign) {
            const personnelId = document.getElementById('receiver-id').innerText;
            const assetId = btnUnassign.getAttribute('data-id');
            await unassign(assetId, personnelId);
        }
    });
    btnGenerateReport.addEventListener('click', async () => {
        const personnelId = document.getElementById('receiver-id').innerText;
        await downloadPdfReport(personnelId);
    });


}

document.addEventListener('DOMContentLoaded', function () {
    startApp();
});