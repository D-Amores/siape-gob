async function unassignAsset(id) {
    let isOk = false; // usar let, no const
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(`${urlUnassignedAsset}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        console.log(response);
        

        if (!response.ok) {
            if (response.status === 404) {
                showAlert('El activo no fue encontrado.', 'red', 'Error', null, 2000);
                return false;
            } else {
                throw new Error(`Error HTTP: ${response.status}`);
            }
        }

        const result = await response.json();
        if (result.ok) {
            showAlert(result.message || 'Activo desasignado exitosamente', 'green', 'Éxito', null, 2000);
            console.log(result);
            isOk = true;
        } else {
            showAlert(result.message || 'Error al desasignar el activo', 'red', 'Error', null, 2000);
        }
    } catch (error) {
        showAlert('Error al desasignar el activo. Intente nuevamente.', 'red', 'Error');
    }

    return isOk; // true si se desasignó, false si no
}