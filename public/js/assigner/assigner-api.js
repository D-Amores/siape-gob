const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
async function getAssetPending(option = 'pending') {
    try{
        const response = await fetch(urlAssignmentApi, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ option })
        });
        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const result = await response.json();

        if (result.ok) {
            return result.data;
        }
        
    } catch (error) {
        console.error('Error en obtener datos:', error);
    }
    return [];
}

async function getAssetApi(option = 'available') {
    try{
        const response = await fetch(urlAssetApi, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },

            body: JSON.stringify({
                option: option,
            })

        });
        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const result = await response.json();

        if (result.ok) {
            return result.data;
        }
        
    } catch (error) {
        console.error('Error en obtener datos:', error);
    }
    return [];
}

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
        console.error('Error en obtener datos:', error);
    }
    return [];
}

async function acceptAssetApi(option = 'accepted', filter = null, personnelId = null) {
    try {
        const payload = { option: option };
        if (filter) {
            payload.filter = filter;
        }
        if (personnelId) {
            payload.personnel_id = personnelId;
        }
        const response = await fetch(urlAssignmentApi, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const result = await response.json();

        if(result.ok) {
            return result.data;
        }
        
    } catch (error) {
        console.error('Error en aceptar asignación:', error);
    }
    return [];
}
