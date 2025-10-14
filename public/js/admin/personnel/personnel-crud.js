// ===============================
// VARIABLES GLOBALES
// ===============================
const vURIPersonnel = `${window.location.origin}/admin/personnel`;
const spinnerPersonnel = document.getElementById('spinnerPersonnelCreate');
const btnPersonnelCreate = document.getElementById('btnPersonnelCreate');


// ===============================
// FUNCIONES STORE, UPDATE, DELETE
// ===============================
async function storePersonnel(personnel) {
    let isOk = false; // usar let, no const
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(vURIPersonnel, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(personnel)
        });

        if (!response.ok) {
            if (response.status === 422) {
                const errorData = await response.json();
                let errorMessages = Object.values(errorData.errors).flat().join('<br>');
                showAlert(errorMessages, 'red', 'Errores de Validación');
                return false;
            } else {
                throw new Error(`Error HTTP: ${response.status}`);
            }
        }

        const result = await response.json();
        
        if (result.ok) {
            showAlert(result.message || 'Personal creado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else {
            showAlert(result.message || 'Error al crear el personal', 'red', 'Error', null, 2000);
        }
    } catch (error) {
        showAlert('Error al crear el personal. Intente nuevamente.', 'red', 'Error');
    }

    return isOk; // true si se creó, false si no
}

async function updatePersonnel(personnelId, personnel) {
    let isOk = false; // usar let, no const
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(`${vURIPersonnel}/${personnelId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(personnel)
        });

        if (!response.ok) {
            if (response.status === 422) {
                const errorData = await response.json();
                let errorMessages = Object.values(errorData.errors).flat().join('<br>');
                showAlert(errorMessages, 'red', 'Errores de Validación');
                return false;
            }
        }
        const result = await response.json();
        if (result.ok) {
            showAlert(result.message || 'Personal actualizado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else {
            showAlert(result.message || 'Error al actualizar el personal', 'red', 'Error', null, 2000);
        }

    } catch (error) {
        console.error('Error en updatePersonnel:', error);
        showAlert('Error al actualizar el personal. Intente nuevamente.', 'red', 'Error');
    }
    return isOk; // true si se actualizó, false si no
}

async function showPersonnel(personnelId) {
    let personnel = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(`${vURIPersonnel}/${personnelId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            if (response.status === 404) {
                return null;
            } else {
                throw new Error(`Error HTTP: ${response.status}`);
            }
        }

        const result = await response.json();
        if (result.ok) {
            personnel = result.data;
        }
    } catch (error) {
        console.error('Error en showPersonnel:', error);
    }

    return personnel; // objeto personnel o null
}

async function destroyPersonnel(personnelId) {
    let isOk = false; // usar let, no const
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(`${vURIPersonnel}/${personnelId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            if (response.status === 404) {
                showAlert('El personal no fue encontrado.', 'red', 'Error', null, 2000);
                return false;
            } else {
                throw new Error(`Error HTTP: ${response.status}`);
            }
        }

        const result = await response.json();
        if (result.ok) {
            showAlert(result.message || 'Personal eliminado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else {
            showAlert(result.message || 'Error al eliminar el personal', 'red', 'Error', null, 2000);
        }
    } catch (error) {
        console.error('Error en destroyPersonnel:', error);
        showAlert('Error al eliminar el personal. Intente nuevamente.', 'red', 'Error');
    }

    return isOk; // true si se eliminó, false si no
}
