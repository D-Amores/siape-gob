function moveFocus(focusId) {
    const element = document.querySelector(focusId);
    element?.focus();
}

document.addEventListener("DOMContentLoaded", () => {
  // Abrir modal "Crear personal"
    document.getElementById("btnpersonnelCreateModal").addEventListener("click", () => {
        const modal = new bootstrap.Modal(document.getElementById("personnelCreateModal"));
        modal.show();
    });

    // Cerrar modal "Crear personal"
    document.getElementById("btnClosePersonnelCreate").addEventListener("click", () => {
        moveFocus("#btnpersonnelCreateModal");
        const modal = bootstrap.Modal.getInstance(document.getElementById("personnelCreateModal"));
        modal.hide();
    });

    // Cerrar modal "Editar personal"
    document.getElementById("btnClosePersonnelEdit").addEventListener("click", () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById("personnelModalEdit"));
        document.activeElement.blur(); //Fuerza a que se quite el foco de un elemento
        modal.hide();
        moveFocus("#btnPersonnelEdit");
        const btnEdit = document.querySelector("#btnPersonnelEdit");
        btnEdit.removeAttribute('id');
    });
});

