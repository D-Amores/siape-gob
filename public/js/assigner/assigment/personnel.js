// load-personnel.js

// Función para cargar el personal
async function loadPersonnel() {
    try {
        const data = await getPersonnelApi();
        const personnelSelect = document.getElementById('assignedUser');

        personnelSelect.innerHTML = '<option value="">Seleccione personal...</option>';

        data.forEach(person => {
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
