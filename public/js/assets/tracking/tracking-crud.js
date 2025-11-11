async function storeAssetTracking(data){
    let isOk = false;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(assetTrackingUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        
        if (result.ok) {
            showAlert(result.message || 'Usuario creado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else {
            if (result.errors) {
                const errorMessages = Object.values(result.errors).flat().join('<br>');
                showAlert(errorMessages, 'red', 'Errores de validación', null, 4000);
            } else {
                showAlert(result.message || 'Error al crear el usuario', 'red', 'Error', null, 2000);
            }
        }
    } catch (error) {
        showAlert('Error al crear el usuario. Intente nuevamente.', 'red', 'Error');
    }

    return isOk; // true si se creó, false si no
}