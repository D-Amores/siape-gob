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

async function initAdminPanel() {

    const btnCreateUser = document.getElementById('btnUserCreate');
    const userTableTbody = document.querySelector('#dataUsersTable tbody');
    const btnUserUpdate = document.getElementById('btnUserUpdate');

    openModal("btnOpenmodalUserCreate", "modalUserCreate");
    forceCloseModalWithBlur('btnCloseModalUserCreate', 'modalUserCreate', 'btnOpenmodalUserCreate');
    forceCloseModalWithRemoveId('btnCloseModalUserEdit', 'modalUserEdit', 'btnUserEdit');
    
    
    //saveTabsState(); // Guarda el estado de las pestañas
    await personnelToSelect(); // Carga los personales
    //await loadUsers(); // Carga los usuarios
    
    //btnCreateUser.addEventListener('click', userCreate);
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