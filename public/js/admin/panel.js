//funciones auxiliares
function resetFormAndSelect(form) {
    form.reset();

    // Eliminar clases de validación
    const elements = form.querySelectorAll('.is-valid, .is-invalid');
    elements.forEach(el => el.classList.remove('is-valid', 'is-invalid'));

    // Resetear selects con Select2
    $(form).find('select').val('').trigger('change');
}

function closeModal(modalId, focusButtonId) {
    // Cerrar modal y resetear formulario solo si la creación fue exitosa
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    document.getElementById(focusButtonId)?.focus();
    modal?.hide();
}

// Cargar datos en las tablas y selects
async function areasToSelect() {
    const selects = document.getElementsByClassName('areaSelect');
    const areas = await getAreaApi();

    Array.from(selects).forEach(select => {
        // Limpiar opciones
        select.innerHTML = '<option value="" disabled selected>Seleccionar área</option>';

        // Llenar opciones
        areas.forEach(area => {
            const option = document.createElement('option');
            option.value = area.id;
            option.textContent = area.name;

            select.appendChild(option);
        });
    });
}

async function loadPersonnel() {
    data = await getPersonnelApi();
    loadPersonnelTable(data);
}

// Función para crear personal
async function personnelCreate() {
    if (!isFormValid('#personnelCreateForm')) return;

    const personnel = document.getElementById('personnelCreateForm');
    const formData = Object.fromEntries(new FormData(personnel));

    const spinnerPersonnel = document.getElementById('spinnerPersonnelCreate');
    const btnPersonnelCreate = document.getElementById('btnPersonnelCreate');

    // Pasamos una función async como callback
    confirmStore(async () => {
        // Deshabilitar botón y mostrar spinner
        spinnerPersonnel.classList.remove('d-none');
        btnPersonnelCreate.disabled = true;
        
        // Llamar a la función para almacenar el personal
        const isOk = await storePersonnel(formData);
        if (isOk) {
            closeModal('personnelCreateModal', 'btnPersonnelCreateModal');
            resetFormAndSelect(personnel);
            loadPersonnel(); // recarga la tabla solo si la creación fue exitosa
            console.log('tabla recargada');
        }

        // Habilitar botón y ocultar spinner
        spinnerPersonnel.classList.add('d-none');
        btnPersonnelCreate.disabled = false;
    });
}

// Abrir modal de edición con datos del personal
function openPersonnelEditModal(personnel) {
    // Llenar campos
    document.getElementById('edit_personnel_id').value = personnel.id;
    document.getElementById('edit_name').value = personnel.name;

    // Área
    const areaSelect = document.getElementById('edit_area_id');
    Array.from(areaSelect.options).forEach(option => {
        option.selected = option.value == (personnel.area?.id || '');
    });

    // Rol (minúsculas para coincidir con el backend)
    const rolSelect = document.getElementById('edit_rol');
    Array.from(rolSelect.options).forEach(option => {
        option.selected = option.value.toLowerCase() == (user.roles?.[0]?.name || '').toLowerCase();
    });
}

//funcion para actualizar personal
async function personnelUpdate(personnelId) {
}

// Guardar el estado de las pestañas en localStorage
function saveTabsState() {
    const adminTabs = document.querySelectorAll('#adminTabs button[data-bs-toggle="tab"]');
    const savedTab = localStorage.getItem('activeAdminTab');

    if (savedTab) {
        const tabTrigger = document.querySelector(`#adminTabs button[data-bs-target="${savedTab}"]`);
        if (tabTrigger) new bootstrap.Tab(tabTrigger).show();
    } else if (adminTabs.length > 0) {
        new bootstrap.Tab(adminTabs[0]).show();
    }

    adminTabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', e => {
            localStorage.setItem('activeAdminTab', e.target.getAttribute('data-bs-target'));
        });
    });
}


async function initAdminPanel() {

    const btnCreatePersonnel = document.getElementById('btnPersonnelCreate');
    const personnelTable = document.getElementById('dataPersonnelTable');


    saveTabsState(); // Guarda el estado de las pestañas
    await areasToSelect(); // Carga las áreas
    await loadPersonnel(); // Carga el personal

    btnCreatePersonnel.addEventListener('click', personnelCreate);

    personnelTable.querySelector('tbody').addEventListener('click', function(e) {
        // Botón de Editar
        const btnEdit = e.target.closest('.btn-edit'); // coincide con tu render de DataTable
        if (btnEdit) {
            const row = btnEdit.closest('tr');
            // Obtenemos los datos completos de la fila desde DataTable
            const rowData = personnelTableInstance.row(row).data(); 
            if (rowData) openPersonnelEditModal(rowData);
            return;
        }
    });

}

document.addEventListener('DOMContentLoaded', initAdminPanel);