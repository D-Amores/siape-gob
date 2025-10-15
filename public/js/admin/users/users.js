let dataOriginal = [];
// Cargar datos en las tablas y selects, modales, etc.
async function personnelToSelect() {
    const selects = document.getElementsByClassName('personnelSelect');
    const personnels = await getPersonnelApi();

    if (!personnels) {
        showAlert('No se pudieron cargar los personales. Recargue la página e intente de nuevo.', 'red', 'Error');
        return;
    }

    Array.from(selects).forEach(select => {
        // Limpiar opciones
        select.innerHTML = '<option value="" disabled selected>Seleccionar personal</option>';

        // Llenar opciones
        personnels.forEach(personnel => {
            const fullName = [
                capitalizeWords(personnel.name ?? ''),
                capitalizeWords(personnel.last_name ?? ''),
                capitalizeWords(personnel.middle_name ?? '')
            ].filter(Boolean).join(' ');

            const option = document.createElement('option');
            option.value = personnel.id;
            option.textContent = fullName;

            select.appendChild(option);
        });
    });
}
// Abrir modal de edición y cargar datos
async function loadUserDataOnModalEdit(userId) {
    const user = await showUser(userId);
    if(!user){
        showAlert('No se pudo cargar la información del usuario.', 'red', 'Error');
        return;
    }
    // Llenar los campos del formulario de edición
    document.getElementById('userEdit').value = user.id || '';
    document.getElementById('username_edit').value = user.username || '';
    document.getElementById('personnel_id_edit').value = user.personnel_id || '';

    // Personal
    const personnelSelect = document.getElementById('personnel_id_edit');
    Array.from(personnelSelect.options).forEach(option => {
        option.selected = option.value == (user.personnel_id || '');
    });

    dataOriginal = {
        username: user.username || '',
        personnel_id: user.personnel_id || ''
    };

}

// Cargar datos en la tabla
async function loadUsers() {
    data = await getUserApi();
    console.log(data);
    loadUsersTable(data);
}

// Crear usuario
async function userCreate() {
    if (!isFormValid('#userCreateForm')) return;

    const userForm = document.getElementById('userCreateForm');
    const formData = Object.fromEntries(new FormData(userForm));

    const spinnerUser = document.getElementById('userCreateSpinner');
    const btnUserCreate = document.getElementById('btnUserCreate');

    confirmStore(async () => {
        spinnerUser.classList.remove('d-none');
        btnUserCreate.disabled = true;

        const isOk = await storeUsers(formData); // Cambiado a storeUsers
        if (isOk) {
            closeModalForSuccess('modalUserCreate', 'btnOpenModalUserCreate');
            resetFormAndSelect(userForm);
            await loadUsers(); // Recargar tabla de usuarios
        }
        spinnerUser.classList.add('d-none');
        btnUserCreate.disabled = false;

    });

}

//Actualizar usuario
async function userEdit() {
    if (!isFormValid('#userEditForm')) return;
    
    const userForm = document.getElementById('userEditForm');
    const formData = new FormData(userForm);
    const spinnerUser = document.getElementById('userEditSpinner');
    const btnUserUpdate = document.getElementById('btnUserUpdate');

    const data = {};
    let userId = null;

    formData.forEach((value, key) => {  
        const trimmedValue = value.trim();

        if (key === "user_id") {
            userId = trimmedValue; // Guardamos aparte
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
        spinnerUser.classList.remove('d-none');
        btnUserUpdate.disabled = true;
        const isOk = await updateUser(userId, data); // Cambiado a updateUser
        if (isOk) {
            closeModalForSuccess('modalUserEdit', 'btnUserEdit');
            resetFormAndSelect(userForm);
            await loadUsers(); // Recargar tabla de usuarios
        }
        spinnerUser.classList.add('d-none');
        btnUserUpdate.disabled = false;

    });

}

async function initAdminPanel() {

    const btnUserCreate = document.getElementById('btnUserCreate');
    const userTableTbody = document.querySelector('#dataUsersTable tbody');
    const btnUserUpdate = document.getElementById('btnUserUpdate');

    openModal("btnOpenModalUserCreate", "modalUserCreate");
    forceCloseModalWithBlur('btnCloseModalUserCreate', 'modalUserCreate', 'btnOpenModalUserCreate');
    forceCloseModalWithRemoveId('btnCloseModalUserEdit', 'modalUserEdit', 'btnUserEdit');
    
    
    //saveTabsState(); // Guarda el estado de las pestañas
    await loadUsers(); // Carga los usuarios
    await personnelToSelect(); // Carga los personales

    btnUserCreate.addEventListener('click', userCreate);
    btnUserUpdate.addEventListener('click', userEdit);
    
    userTableTbody.addEventListener('click', (e)=>{
        const btnEdit = e.target.closest('.btn-edit');
        if (btnEdit) {
            btnEdit.id = 'btnUserEdit'
            openModalForEdit("modalUserEdit");
            const userId = btnEdit.getAttribute('data-id');
            loadUserDataOnModalEdit(userId);
        }

        const btnDelete = e.target.closest('.btn-delete');
        if (btnDelete) {
            const userId = btnDelete.getAttribute('data-id');
            //userDelete(userId);
        }
    });

}

document.addEventListener('DOMContentLoaded', initAdminPanel);