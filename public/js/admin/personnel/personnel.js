let dataOriginal = [];
// Cargar datos en las tablas y selects, modales, etc.
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

// Cargar personal
async function loadPersonnel() {
    data = await getPersonnelApi();
    loadPersonnelTable(data);
}

//Cargar datos en el modal de edición
async function loadPersonnelDataOnModalEdit(personnelId) {
    const personnel = await showPersonnel(personnelId);
    if (!personnel) {
        showAlert('No se pudo cargar la información del personal.', 'red', 'Error');
        return;
    }
    // Llenar los campos del formulario de edición
    document.getElementById('personnel_id_edit').value = personnel.id || '';
    document.getElementById('name_edit').value = personnel.name || '';
    document.getElementById('last_name_edit').value = personnel.last_name || '';
    document.getElementById('middle_name_edit').value = personnel.middle_name || '';
    document.getElementById('phone_edit').value = personnel.phone || '';
    document.getElementById('email_edit').value = personnel.email || '';

    // Estado (is_active)
    const isActiveSelect = document.getElementById('is_active_edit');
    Array.from(isActiveSelect.options).forEach(option => {
        option.selected = option.value == (personnel.is_active ? '1' : '0');
    });

    // Área
    const areaSelect = document.getElementById('area_id_edit');
    Array.from(areaSelect.options).forEach(option => {
        option.selected = option.value == (personnel.area?.id || '');
    });
    // Guardamos los datos originales para comparar después
    dataOriginal = {
        name: personnel.name || '',
        last_name: personnel.last_name || '',
        middle_name: personnel.middle_name || '',
        phone: personnel.phone || '',
        email: personnel.email || '',
        is_active: personnel.is_active ? '1' : '0',
        area_id: personnel.area_id || ''
    };
}

//Funciones Create, Edit, Delete
// Función para crear personal
async function personnelCreate() {
    if (!isFormValid('#personnelCreateForm')) return;

    const personnel = document.getElementById('personnelCreateForm');
    const formData = Object.fromEntries(new FormData(personnel));

    const personnelCreateSpinner = document.getElementById('personnelCreateSpinner');
    const btnPersonnelCreate = document.getElementById('btnPersonnelCreate');

    // Pasamos una función async como callback
    confirmStore(async () => {
        // Deshabilitar botón y mostrar spinner
        personnelCreateSpinner.classList.remove('d-none');
        btnPersonnelCreate.disabled = true;
        
        // Llamar a la función para almacenar el personal
        const isOk = await storePersonnel(formData);
        if (isOk) {
            closeModalForSuccess('modalPersonnelCreate', 'btnOpenModalPersonnelCreate');
            resetFormAndSelect(personnel);
            await loadPersonnel();
        }
        personnelCreateSpinner.classList.add('d-none');
        btnPersonnelCreate.disabled = false;
    });
}

//funcion para actualizar personal
async function personnelUpdate() {
    const personnelForm = document.getElementById('personnelEditForm');
    const formData = new FormData(personnelForm);
    const spinnerPersonnel = document.getElementById('personnelEditSpinner');
    const btnPersonnelEdit = document.getElementById('btnPersonnelEdit');

    const data = {};
    let personnelId = null;

    formData.forEach((value, key) => {  
        const trimmedValue = value.trim();

        if (key === "personnel_id") {
            personnelId = trimmedValue; // Guardamos aparte
        } else if (trimmedValue !== "") {
            // Solo agregamos si tiene contenido
            data[key] = trimmedValue;
        }
    });

    // 🔍 Solo enviar los campos que cambiaron
    const changedData = getChangedData(dataOriginal, data);

    // Si no hay cambios, cancelamos
    if (Object.keys(changedData).length === 0) {
        showAlert('No se detectaron cambios para actualizar.', 'blue', 'Sin cambios');
        return;
    }

    confirmUpdate(async () => {
        spinnerPersonnel.classList.remove('d-none');
        btnPersonnelEdit.disabled = true;

        const isOk = await updatePersonnel(personnelId, changedData);
        if (isOk) {
            closeModalForSuccess('modalPersonnelEdit', 'btnOpenModalPersonnelEdit');
            resetFormAndSelect(personnelForm);
            await loadPersonnel(); // recarga la tabla solo si la creación fue exitosa
        }
        spinnerPersonnel.classList.add('d-none');
        btnPersonnelEdit.disabled = false;
    });

}

//funcion para eliminar personal
async function personnelDelete(personnelId) {
    confirmDestroy(async () => {
        const isOk = await destroyPersonnel(personnelId);
        if (isOk) {
            await loadPersonnel(); // recarga la tabla solo si la creación fue exitosa
        }
    });
}

async function initAdminPanel() {

    const btnPersonnelCreate = document.getElementById('btnPersonnelCreate');
    const personnelTableTbody = document.querySelector('#dataPersonnelTable tbody');
    const btnPersonnelEdit = document.getElementById('btnPersonnelEdit');

    openModal("btnOpenModalPersonnelCreate", "modalPersonnelCreate");
    closeModal('btnCloseModalPersonnelCreate', 'modalPersonnelCreate', 'btnOpenModalPersonnelCreate');
    forceCloseModalWithRemoveId('btnCloseModalPersonnelEdit', 'modalPersonnelEdit', 'btnOpenModalPersonnelEdit');
    
    await loadPersonnel(); // Carga el personal
    await areasToSelect(); // Carga las áreas

    btnPersonnelCreate.addEventListener('click', personnelCreate);
    btnPersonnelEdit.addEventListener('click', personnelUpdate);

    personnelTableTbody.addEventListener('click', async (e)=>{
        const btnEdit = e.target.closest('.btn-edit');
        if (btnEdit) {
            btnEdit.id = 'btnOpenModalPersonnelEdit';
            openModalForEdit("modalPersonnelEdit");
            const personnelId = btnEdit.getAttribute('data-id');
            await loadPersonnelDataOnModalEdit(personnelId);
        }

        const btnDelete = e.target.closest('.btn-delete');
        if (btnDelete) {
            const personnelId = btnDelete.getAttribute('data-id');
            await personnelDelete(personnelId);
        }
    });

}

document.addEventListener('DOMContentLoaded', initAdminPanel);