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
        console.log('Respuesta del servidor al crear personal:', result);
        
        if (result.ok) {
            showAlert(result.message || 'Personal creado exitosamente', 'green', 'Éxito', null, 2000);
            isOk = true;
        } else if (result.errors) {
            let errorMessages = Object.values(result.errors).flat().join('<br>');
            showAlert(errorMessages, 'red', 'Errores de Validación');
        } else {
            showAlert(result.message || 'Error al crear el personal', 'red', 'Error', null, 2000);
        }
    } catch (error) {
        console.error('Error en storePersonnel:', error);
        showAlert('Error al crear el personal. Intente nuevamente.', 'red', 'Error');
    }

    return isOk; // true si se creó, false si no
}

