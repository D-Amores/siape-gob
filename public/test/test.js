userURL = vURI + 'users';
personnelURL = vURI + 'personnel';
loginURL = vURI + 'login';
logoutURL = vURI + 'logout';
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

    let dataUser = {
        "username": "usuario123",
        "password": "secreta123",
        "password_confirmation": "secreta123",
        "personnel_id": 1
    };


    try {
        let response = await fetch(userURL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(dataUser)
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
        'is_active': true,
    };

    let dataUser = {
        //"username": "usuario_actualizado",
        "password": "nueva_secreta1",
        "password_confirmation": "nueva_secreta1",
    };
    personnelId = 1;
    userId = 1;

    try {
        let response = await fetch(userURL + '/' + userId, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(dataUser)
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

async function testApi() {
    console.log('Test function executed (Personnel API).');
    let option = 'users_areas_personnel';  // <- Aquí defines la opción que quieres probar
/*
{
    option: '',
}
*/
    try {
        let response = await fetch(userURL + '/api', {
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
    personnelId = 3;
    userId = 1;
    try {
        let response = await fetch(personnelURL + '/' + personnelId, {
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

async function testLogin() {
    console.log('Test function executed (Login).');
    
    let dataLogin = {
        "username": "usuario123",
        "password": "secreta123",
        "remember": false
    };
    
    try {
        let response = await fetch(loginURL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(dataLogin)
        });

        let result = await response.json();

        if (!response.ok) {
            console.error('Server error:', result);
        } else {
            console.log('Response from server:', result);
            // Si la respuesta incluye una ubicación, redirige allí
            if (result.location) {
                DelayNode(2000);
                window.location.href = result.location;
            }
        }

    } catch (error) {
        console.error('Network or parsing error:', error);
    }
}

async function testLogout() {
    console.log('Test function executed (Logout).');
    
    try {
        let response = await fetch(logoutURL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin'
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
    console.log(personnelURL);
    console.log(userURL);


    btnStore = document.getElementById('btnStore');
    btnUpdate = document.getElementById('btnUpdate');
    btnDelete = document.getElementById('btnDelete');
    btnApi = document.getElementById('btnApi');
    btnLogin = document.getElementById('btnLogin');
    btnLogOut = document.getElementById('btnLogOut');

    btnLogin.addEventListener('click', function() {
        testLogin();
        alert('Button clicked, test function called.');
    });

    btnLogOut.addEventListener('click', function() {
        testLogout();
        alert('Button clicked, test function called.');
    });

    btnApi.addEventListener('click', function() {
        testApi();
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