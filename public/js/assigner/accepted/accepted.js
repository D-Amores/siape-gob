async function startApp() {
    const acceptedTableData = await acceptAssetApi('accepted');
    loadAssetsAccepted(acceptedTableData);
    
}

document.addEventListener('DOMContentLoaded', function () {
    startApp();
});