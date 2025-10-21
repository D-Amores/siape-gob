// load-assets.js
async function loadAssets() {
    try {
        const data = await getAsset('all');
        const assetSelect = document.getElementById('assignedAsset');

        assetSelect.innerHTML = '<option value="">Seleccione un bien...</option>';

        data.forEach(asset => {
            const option = document.createElement('option');
            option.value = asset.id;
            option.textContent = asset.text;
            assetSelect.appendChild(option);
        });

    } catch (error) {
        console.error('Error cargando bienes:', error);

        const assetSelect = document.getElementById('assetSelect');
        if (assetSelect) {
            assetSelect.innerHTML = '<option value="">Error al cargar bienes</option>';
        }
    }
}
