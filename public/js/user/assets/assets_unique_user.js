document.addEventListener('DOMContentLoaded', async function () {
    try {
        await AssetsTableManager.init();
        AssetDetailsManager.init();
        AssetReportsManager.init({
            URIReport: URIReport,
            vURIAssetsDetails: vURIAssetsDetails
        });
        
    } catch (error) {
        console.error('Error inicializando módulos:', error);
    }
});