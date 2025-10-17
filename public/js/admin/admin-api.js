// ===============================
// VARIABLES GLOBALES
// ===============================
const vURIPersonnelApi = `${window.location.origin}/admin/personnel/api`;
const vURIAreaApi = `${window.location.origin}/admin/areas/api`;
const vURIUserApi = `${window.location.origin}/admin/users/api`;

// ===============================
// FUNCIONES API
// ===============================
async function getPersonnelApi(consultOption = 'area') {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(vURIPersonnelApi, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ option: consultOption })
        });

        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const result = await response.json();

        if (result.ok) {
            return result.data;
        }
    } catch (error) {
        console.error('Error en obtener personal:', error);
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

        if (result.ok) {
            return result.data;
        }
    } catch (error) {
        console.error('Error en obtener areas:', error);
    }
    return [];
}

async function getUserApi() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(vURIUserApi, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ option: 'users_areas_personnel' })
        });

        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const result = await response.json();

        if (result.ok) {
            return result.data;
        }
    } catch (error) {
        console.error('Error en obtener areas:', error);
    }
    return [];
}

// Obtener roles desde la API de usuarios
async function getRolApi() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    try {
        const response = await fetch(vURIUserApi, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ option: 'roles' })
        });

        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const result = await response.json();

        if (result.ok) {
            return result.data;
        }
    } catch (error) {
        console.error('Error en obtener roles:', error);
    }
    return [];
}