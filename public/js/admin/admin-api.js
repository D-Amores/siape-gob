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

// [12:50 p. m., 5/11/2025] +52 1 961 160 9107: http://172.16.2.48/nexus/public/api/declarachiapas/padron
// [12:51 p. m., 5/11/2025] +52 1 961 160 9107: token bear: bGFQoY1BTgwaVCO4KNdzWZXsMBY
// [12:51 p. m., 5/11/2025] +52 1 961 160 9107: parámetro:  search
const urlAPIExternal = 'http://172.16.2.48/nexus/public/api/declarachiapas/padron';

async function getExternalDataPersonnelApi(searchParam) {
  const tokenBear = 'bGFQoY1BTgwaVCO4KNdzWZXsMBY';
  try {
    const urlWithParam = `${urlAPIExternal}?search=${encodeURIComponent(searchParam)}`;

    const response = await fetch(urlWithParam, {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${tokenBear}`,
        'Accept': 'application/json',
      },
    });

    if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

    const result = await response.json();
    return result.data ?? [];
  } catch (error) {
    console.error('Error en obtener datos del personal externo:', error);
    return [];
  }
}
