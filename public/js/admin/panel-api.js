// ===============================
// VARIABLES GLOBALES
// ===============================
const vURIPersonnelApi = `${window.location.origin}/admin/personnel/api`;
const vURIAreaApi = `${window.location.origin}/admin/area/api`;

// ===============================
// FUNCIONES API
// ===============================
async function getPersonnelApi() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(vURIPersonnelApi, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ option: 'area' })
        });

        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const result = await response.json();
        console.log('Datos recibidos del personal:', result);

        if (result.ok) {
            return result.data;
        } else {
            showAlert(result.message || 'Error al cargar los datos del personal', 'red');
        }
    } catch (error) {
        console.error('Error en getPersonnelApi:', error);
        showAlert('Error al cargar los datos. Intente nuevamente.', 'red');
    }
    return [];
}

async function getAreaApi() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(vURIAreaApi, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ option: 'area' })
        });

        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const result = await response.json();
        console.log('✅ Áreas cargadas:', result);

        if (result.ok) {
            return result.data;
        } else {
            showAlert(result.message || 'Error al cargar las áreas', 'red');
        }
    } catch (error) {
        console.error('❌ Error en getAreaApi:', error);
        showAlert('Error en la solicitud. Intente nuevamente.', 'red');
    }
    return [];
}