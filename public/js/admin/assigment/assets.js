// load-assets.js
async function loadAssets() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url = "/assets/api";

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                option: 'all'
            })
        });

        const data = await response.json();
        const assetSelect = document.getElementById('assignedAsset');

        assetSelect.innerHTML = '<option value="">Seleccione un bien...</option>';

        data.data.forEach(asset => {
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
