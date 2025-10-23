// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    loadPersonnel();
    loadAssets();
    loadAssetPending();

    const userTableTbody = document.querySelector('#file_export tbody');

    openModal("btnOpenModalAddAssignment", "addAssignmentModal");
    closeModal('btnCloseModalAddAssignment', 'addAssignmentModal', 'btnOpenModalAddAssignment');
    //forceCloseModalWithRemoveId('btnCloseModalAssignmentEdit', 'modalAssignmentEdit', 'btnOpenModalAssignmentEdit');

    userTableTbody.addEventListener('click', async (e)=>{
        const btnDelete = e.target.closest('.btn-delete');
        if (btnDelete) {
            const assignmentId = btnDelete.getAttribute('data-id');
            await assignmentDelete(assignmentId);
        }
    });
});

//funcion para eliminar
async function assignmentDelete(assignmentId) {
    confirmDestroy(async () => {
        const isOk = await destroyAssignedAsset(assignmentId);
        if (isOk) {
            await loadAssets();
            await loadAssetPending(); // recarga la tabla solo si la creación fue exitosa
        }
    });
}

//SOlo se usa esta logica, es codigo de otro desarrollador, no es mia, pero por el momento lo dejo asi, mas adelante lo optimizo
const assignmentForm = document.getElementById('assignmentForm');
assignmentForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const personnelSelect = document.getElementById('assignedUser');
    const assetSelect = document.getElementById('assignedAsset');

    if (!personnelSelect.value || !assetSelect.value) {
        showAlert(
            'Debes seleccionar personal y un bien para la asignación.',
            'red',
            'Error'
        );
        return;
    }

    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';

    try {

        const formData = new FormData(assignmentForm);
        const data = Object.fromEntries(formData.entries());
        data.assignment_date = new Date().toISOString().slice(0, 10);

        const result = await assignedAsset(data);

        if (result.ok) {
            showAlert(
                'La asignación se ha guardado correctamente.',
                'green',
                'Éxito'
            );
            closeModalForSuccess('addAssignmentModal', 'btnOpenModalAddAssignment');
            assignmentForm.reset();
            await loadAssetPending();
            await loadAssets();
        } else {
            showAlert(
                result.message || 'Ocurrió un error al guardar la asignación.',
                'red',
                'Error'
            );
        }

    } catch (error) {
        console.error('❌ Error al guardar la asignación:', error);
        showAlert(
            'Ocurrió un error al guardar la asignación. Revisa la consola para más detalles.',
            'red',
            'Error'
        );
    } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    }
});

//También se usa esta logica, es codigo de otro desarrollador, lo optimice un poco, pero deje la estructura base para no romper nada
const loadAssetPending = async () => {
    const data = await getAssetPending('pending');
    if (data){
        updateAssetPendingTable(data);
    }
}

//También se usa esta logica, es codigo de otro desarrollador, lo optimice un poco, pero deje la estructura base para no romper nada
const updateAssetPendingTable = (assetPendings) => {
    loadAssetsPending(assetPendings);
};
