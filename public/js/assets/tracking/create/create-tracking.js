let filterOption = 'all';

async function loadReports(option = 'all') {
    const reports =  await getAssetReports(option);
    loadReportsTable(reports)
}

async function createAssetTracking(reportId) {

    const btnCreate = document.getElementById('btnCreateTracking');
    let spinner = btnCreate.querySelector('.spinner-border');
    let icon = btnCreate.querySelector('i');

    const trackingData = {
        maintenance_report_id: reportId,
    };


    confirmStore(async () => {
        spinner.classList.remove('d-none');
        btnCreate.disabled = true;
        icon.classList.add('d-none');
        const isOk = await storeAssetTracking(trackingData);
        if (isOk) {
            await loadReports(filterOption); // Recargar tabla de reportes
        }
        spinner.classList.add('d-none');
        btnCreate.disabled = false;
        icon.classList.remove('d-none');

    }, '¿Está seguro de crear el seguimiento para este reporte?');
}

async function startApp(){
    const btnFilter = document.getElementById('btn-filter');
    const dataReportsTableBody = document.querySelector('#reports-table tbody');
    const reports =  await getAssetReports('all');
    loadReportsTable(reports);
    console.log(reports);
    

    dataReportsTableBody.addEventListener('click', async (e)=>{
        const btnCreate = e.target.closest('.btn-create');
        if (btnCreate) {
            btnCreate.id = 'btnCreateTracking';
            const reportId = btnCreate.getAttribute('data-id');
            await createAssetTracking(reportId);

            btnCreate.removeAttribute('id');

        }
    });

    btnFilter.addEventListener('click', async ()=>{
        const filterSelect = document.getElementById('filter-reports');
        const selectedOption = filterSelect.value;
        filterOption = selectedOption;
        await loadReports(selectedOption);
    });
}

document.addEventListener('DOMContentLoaded', startApp);