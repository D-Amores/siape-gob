async function getHistoricApi(option = 'historic') {
    let result = null;
    try {
        const response = await fetch(vHistoricApi, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ option: option })
        });

        const resultData = await response.json();
        if (response.ok) {
            result = resultData;
            return result;
        }
        else {
            showAlert(resultData.message, 'red', 'Error', null, 0);
        }

    }catch (error) {
        showAlert('Error al conectar con el servidor.', 'red', 'Error', null, 0);
        console.log(error);
        
        return null;
    }
}