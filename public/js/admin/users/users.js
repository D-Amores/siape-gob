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
            const option = document.createElement('option');
            option.value = personnel.id;
            option.textContent = personnel.name + ' ' + personnel.last_name + (personnel.middle_name ? ' ' + personnel.middle_name : '');

            select.appendChild(option);
        });
    });
}

// Cargar datos en la tabla
async function loadUsers() {
    data = await getUserApi();
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
    //btnUserUpdate.addEventListener('click', userUpdate);
    
    userTableTbody.addEventListener('click', (e)=>{
        const btnEdit = e.target.closest('.btn-edit');
        if (btnEdit) {
            btnEdit.id = 'btnUserEdit'
            openModalForEdit("modalUserEdit");
            const userId = btnEdit.getAttribute('data-id');
            //openModalUserEdit(userId);
        }

        const btnDelete = e.target.closest('.btn-delete');
        if (btnDelete) {
            const userId = btnDelete.getAttribute('data-id');
            //userDelete(userId);
        }
    });

}

document.addEventListener('DOMContentLoaded', initAdminPanel);