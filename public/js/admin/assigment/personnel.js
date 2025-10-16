// load-personnel.js

// Función para cargar el personal
async function loadPersonnel() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url = "/admin/personnel/api";

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                option: 'area'
            })
        });

        const data = await response.json();
        const personnelSelect = document.getElementById('assignedUser');

        personnelSelect.innerHTML = '<option value="">Seleccione personal...</option>';

        data.data.forEach(person => {
            const fullName = `${person.name || ''} ${person.middle_name || ''} ${person.last_name || ''}`.trim();

            const option = document.createElement('option');
            option.value = person.id;
            option.textContent = fullName || `Personal ID: ${person.id}`;
            personnelSelect.appendChild(option);
        });

    } catch (error) {
        console.error('Error cargando personal:', error);

        const personnelSelect = document.getElementById('assignedUser');
        if (personnelSelect) {
            personnelSelect.innerHTML = '<option value="">Error al cargar personal</option>';
        }
    }
}
