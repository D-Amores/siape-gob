const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
async function getAssetPending(option = null) {
    try{
        const response = await fetch(urlApiAssetPending, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Content-Type': 'application/json'
            },
            if (option) {
                body: JSON.stringify({ option })
            }
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
