async function testStore() {
    console.log('Test function executed.');

    let data = {
        'name': 'John',
        'last_name': 'Doe',
        'middle_name': 'Middle',
        'phone': '123-456-7890',
        'email': 'john.doe@example.com',
        'area_id': 1
    };

    try {
        let response = await fetch(urlTest, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });

        let result = await response.json();

        if (!response.ok) {
            console.error('Server error:', result);
        } else {
            console.log('Response from server:', result);
        }

    } catch (error) {
        console.error('Network or parsing error:', error);
    }
}

async function testUpdate() {
    console.log('Test function executed.');
    
    let data = {
        'is_active': false,
    };
    personnelId = 1;

    try {
        let response = await fetch(urlTest + '/' + personnelId, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });

        let result = await response.json();

        if (!response.ok) {
            console.error('Server error:', result);
        } else {
            console.log('Response from server:', result);
        }

    } catch (error) {
        console.error('Network or parsing error:', error);
    }
}

async function testPersonnelApi() {
    console.log('Test function executed (Personnel API).');
    let option = 'area';  // <- Aquí defines la opción que quieres probar

    try {
        let response = await fetch(urlTest + '/api', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ option })  // <- Aquí mandas la opción
        });

        let result = await response.json();

        if (!response.ok) {
            console.error('Server error:', result);
        } else {
            console.log('Response from server:', result);
        }

    } catch (error) {
        console.error('Network or parsing error:', error);
    }
}


async function testDestroy(){
    console.log('Test function executed.');
    personnelId = 2;
    try {
        let response = await fetch(urlTest + '/' + personnelId, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        let result = await response.json();

        if (!response.ok) {
            console.error('Server error:', result);
        } else {
            console.log('Response from server:', result);
        }

    } catch (error) {
        console.error('Network or parsing error:', error);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Test script loaded and DOM is ready.');
    console.log(urlTest);

    btnStore = document.getElementById('btnStore');
    btnUpdate = document.getElementById('btnUpdate');
    btnDelete = document.getElementById('btnDelete');
    btnPersonnelApi = document.getElementById('btnPersonnelApi');

    btnPersonnelApi.addEventListener('click', function() {
        testPersonnelApi();
        alert('Button clicked, test function called.');
    });
    
    btnStore.addEventListener('click', function() {
        testStore();
        alert('Button clicked, test function called.');
    });

    btnUpdate.addEventListener('click', function() {
        testUpdate();
        alert('Button clicked, test function called.');
    });
    
    btnDelete.addEventListener('click', function() {
        testDestroy();
        alert('Button clicked, test function called.');
    });
});

/*
Tabla Assets
id
name


Model Asset
id
name

Resource
/assets <- peticion Get <- Redirige a index
/assets/{id} <- peticion Get <- Redirige a show
/assets <- peticion Post <- Redirige a store
/assets/{id} <- peticion Put/Patch <- Redirige a update
/assets/{id} <- peticion Delete <- Redirige a destroy

/assets/1 <- PATCH  = /assets/asset 
/assets/{id}
*/