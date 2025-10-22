const URIAssignedAsset = `${window.location.origin}/personnel-asset-pending`;
async function assignedAsset(dataObject) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch(URIAssignedAsset, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(dataObject)
    });

    return await response.json();
}

async function destroyAssignedAsset(assignmentId) {
    let isOk = false; // usar let, no const
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(`${URIAssignedAsset}/${assignmentId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            if (response.status === 404) {
                showAlert('El registro no fue encontrado.', 'red', 'Error', null, 2000);
                return false;
            } else {
                throw new Error(`Error HTTP: ${response.status}`);
            }
        }

        const result = await response.json();
        if (result.ok) {
            showAlert(result.message || 'Registro eliminado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else {
            showAlert(result.message || 'Error al eliminar el registro', 'red', 'Error', null, 2000);
        }
    } catch (error) {
        showAlert('Error al eliminar el registro. Intente nuevamente.', 'red', 'Error');
    }

    return isOk; // true si se eliminó, false si no
}