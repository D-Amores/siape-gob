async function loginUser(data) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        console.log('Enviando datos de login:', data);
        const response = await fetch(vURI, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        const alertBox = document.getElementById('alert');
        alertBox.classList.add('d-none');
        alertBox.textContent = '';

        if (!response.ok) {
            // Mostrar mensaje general
            let errorText = result.message || 'Error de autenticación.';

            // Mostrar el primer error del array de errores si existe
            if (result.errors && Object.keys(result.errors).length > 0) {
                const firstKey = Object.keys(result.errors)[0];
                errorText += ': ' + result.errors[firstKey][0];
            }

            alertBox.textContent = errorText;
            alertBox.classList.remove('d-none');
        } else {
            // Redirección si todo salió bien
            if (result.location) {
                window.location.href = result.location;
            } else {
                window.location.reload();
            }
        }

    } catch (error) {
        console.error('Error de conexión:', error);
    }
}

function validateForm(form) {
    const alertBox = document.getElementById('alert');
    alertBox.classList.add('d-none');
    alertBox.textContent = '';

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const remember = document.getElementById('flexCheckChecked').checked;

    if (!username) {
        form.classList.add('was-validated');
        alertBox.textContent = 'El nombre de usuario es obligatorio.';
        alertBox.classList.remove('d-none');
        return false;
    }

    if (!password) {
        form.classList.add('was-validated');
        alertBox.textContent = 'La contraseña es obligatoria.';
        alertBox.classList.remove('d-none');
        return false;
    }

    form.classList.remove('was-validated');
    return { username, password, remember };
}

document.addEventListener('DOMContentLoaded', function () {
    const btnSubmit = document.getElementById('btnSubmit');
    const form = document.getElementById('loginForm');

    btnSubmit.addEventListener('click', async function () {
        const formData = validateForm(form);
        if (formData) {
            await loginUser(formData);
        }
    });
});
