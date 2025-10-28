async function startApp() {
    //const btnUnassign = document.querySelectorAll('.btn-unassign');
    const assignedTableTbody = document.querySelector('#accepted_assignments tbody');
    const acceptedTableData = await acceptAssetApi('accepted');
    loadAssetsAccepted(acceptedTableData);

    assignedTableTbody.addEventListener('click', async (e)=>{
        console.log('click detected');
        const btnUnassign = e.target.closest('.btn-unassign');
        if (btnUnassign) {
            const assetId = btnUnassign.getAttribute('data-id');
            confirmDestroy(
                async () => {
                    await unassignAsset(assetId);
                }, 
                '¿Está seguro de desasignar este activo? Esta acción no podrá ser revertida.',
                'Desasignar'
            );
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    startApp();
});