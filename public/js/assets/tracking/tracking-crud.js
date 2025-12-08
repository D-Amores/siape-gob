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
        const codeHttp = response.status;
        const result = await response.json();
        
        if (result.ok) {
            showAlert(result.message || 'Seguimiento creado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else {
            console.log(result);
            
            if (result.errors) {
                const errorMessages = Object.values(result.errors).flat().join('<br>');
                showAlert(errorMessages, 'red', 'Errores de validación', null, 4000);
            } else if(codeHttp === 400){
                showAlert(result.message || 'Ya existe un seguimiento activo para este reporte.', 'orange', 'Advertencia', null, 2000);
            } 
            else {
                showAlert(result.message || 'Error al crear el seguimiento', 'red', 'Error', null, 2000);
            }
        }
    } catch (error) {
        showAlert('Error al crear el seguimiento. Intente nuevamente.', 'red', 'Error');
    }

    return isOk; // true si se creó, false si no
}

async function updateAssetTracking(id, data){
    let isOk = false;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        
        const response = await fetch(`${assetTrackingUrl}/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.ok) { 
            showAlert(result.message || 'Seguimiento actualizado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else {
            if (result.errors) {
                const errorMessages = Object.values(result.errors).flat().join('<br>');
                showAlert(errorMessages, 'red', 'Errores de validación', null, 4000);
            } else {
                showAlert(result.message || 'Error al actualizar el seguimiento', 'red', 'Error', null, 2000);
            }
        }
    } catch (error) {
        showAlert('Error al actualizar el seguimiento. Intente nuevamente.', 'red', 'Error');
    }

    return isOk; // true si se actualizó, false si no   
}

async function destroyAssetTracking(id, data) {
    //peticion fetch para finalizar seguimiento
    let isOk = false;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(`${assetTrackingUrl}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.ok) {
            showAlert(result.message || 'Seguimiento finalizado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else {
            showAlert(result.message || 'Error al finalizar el seguimiento', 'red', 'Error', null, 2000);
        }
    } catch (error) {
        showAlert('Error al finalizar el seguimiento. Intente nuevamente.', 'red', 'Error');
    }

    return isOk; // true si se finalizó, false si no
}