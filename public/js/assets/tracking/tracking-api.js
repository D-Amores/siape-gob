async function getAssetReports(option = 'open') {
    try {
        const response = await fetch(reportApiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ option: option })
        });

        if (!response.ok) {
            throw new Error('Error al obtener los reportes');
        }

        const data = await response.json();
        if (data.data && data.ok) {
            return data.data;
        }
    } catch (error) {
        console.error(error);
    }
    return [];
}