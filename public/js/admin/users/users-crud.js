// ===============================
// VARIABLES GLOBALES
// ===============================
const vURIUsers = `${window.location.origin}/admin/users`;


// ===============================
// FUNCIONES STORE, UPDATE, DELETE
// ===============================
async function storeUsers(users) {
    let isOk = false; // usar let, no const
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(vURIUsers, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(users)
        });
        console.log('Respuesta del servidor:', response);

        if (!response.ok) {
            if (response.status === 422) {
                const errorData = await response.json();
                let errorMessages = Object.values(errorData.errors).flat().join('<br>');
                showAlert(errorMessages, 'red', response.message || 'Error de Validación');
                return false;
            } else {
                throw new Error(`Error HTTP: ${response.status}`);
            }
        }

        const result = await response.json();
        
        if (result.ok) {
            showAlert(result.message || 'Usuario creado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else {
            showAlert(result.message || 'Error al crear el usuario', 'red', 'Error', null, 2000);
        }
    } catch (error) {
        showAlert('Error al crear el usuario. Intente nuevamente.', 'red', 'Error');
    }

    return isOk; // true si se creó, false si no
}

async function updateUser(userId, user) {
    let isOk = false; // usar let, no const
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(`${vURIUsers}/${userId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(user)
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
            showAlert(result.message || 'Usuario actualizado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else {
            showAlert(result.message || 'Error al actualizar el usuario', 'red', 'Error', null, 2000);
        }

    } catch (error) {
        console.error('Error en updateUser:', error);
        showAlert('Error al actualizar el usuario. Intente nuevamente.', 'red', 'Error');
    }
    return isOk; // true si se actualizó, false si no
}

async function showUser(userId) {
    let user = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(`${vURIUsers}/${userId}`, {
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
            user = result.data;
        }
    } catch (error) {
        console.error('Error en showUser:', error);
    }

    return user; // objeto user o null
}

async function destroyUser(userId) {
    let isOk = false; // usar let, no const
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(`${vURIUsers}/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            if (response.status === 404) {
                showAlert('El usuario no fue encontrado.', 'red', 'Error', null, 2000);
                return false;
            } else {
                throw new Error(`Error HTTP: ${response.status}`);
            }
        }

        const result = await response.json();
        if (result.ok) {
            showAlert(result.message || 'Usuario eliminado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else {
            showAlert(result.message || 'Error al eliminar el usuario', 'red', 'Error', null, 2000);
        }
    } catch (error) {
        console.error('Error en destroyUser:', error);
        showAlert('Error al eliminar el usuario. Intente nuevamente.', 'red', 'Error');
    }

    return isOk; // true si se eliminó, false si no
}
