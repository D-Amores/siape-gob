function btnAnimation() {
    // Inicializar tooltip
    new bootstrap.Tooltip(document.getElementById('btnLogout'));

    const btnLogout = document.getElementById('btnLogout');
    const icon = btnLogout.querySelector('i');

    // Definir transición una sola vez
    icon.style.transition = 'transform 0.3s ease';

    btnLogout.addEventListener('mouseenter', () => {
        icon.style.transform = 'rotate(20deg) scale(1.2)';
        icon.classList.add('text-danger');
    });

    btnLogout.addEventListener('mouseleave', () => {
        icon.style.transform = 'rotate(0deg) scale(1)';
        icon.classList.remove('text-danger');
    });
}

async function logoutUser() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('_token', csrfToken);

    try {
        const response = await fetch('/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            console.log('Logout successful:', data.message);
            window.location.href = '/login';
        } else {
            const errorData = await response.json();
            console.error('Error logging out:', response.status, errorData);
        }
    } catch (error) {
        console.error('Error logging out:', error);
    }
}



document.addEventListener('DOMContentLoaded', function () {
    btnAnimation();

    const btnLogout = document.getElementById('btnLogout');
   // btnLogout.addEventListener('click', logoutUser);
});
