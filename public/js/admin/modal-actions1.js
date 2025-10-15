function moveFocus(focusId) {
    const element = document.querySelector(focusId);
    element?.focus();
}

function openModal(btnClickId, modalId) {
    document.getElementById(btnClickId).addEventListener("click", () => {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    });
}

function openModalForEdit(modalId) {
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
}

function closeModal(btnCloseId, modalId, focusId) {
    document.getElementById(btnCloseId).addEventListener("click", () => {
        let focusIdFinal = `#${focusId}`;
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        modal.hide();
        moveFocus(focusIdFinal);
    });
}

function closeModalForSuccess(modalId, focusId) {
    let focusIdFinal = `#${focusId}`;
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    modal.hide();
    moveFocus(focusIdFinal);
}

function forceCloseModalWithBlur(btnCloseId, modalId, focusId) {
    document.getElementById(btnCloseId).addEventListener("click", () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        document.activeElement.blur(); //Fuerza a que se quite el foco de un elemento
        modal.hide();
        let focusIdFinal = `#${focusId}`;
        moveFocus(focusIdFinal);
    });
}

function forceCloseModalWithRemoveId(btnCloseId, modalId, focusId) {
    document.getElementById(btnCloseId).addEventListener("click", () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        modal.hide();
        let focusIdFinal = `#${focusId}`;
        moveFocus(focusIdFinal);
        const btnEdit = document.querySelector(focusIdFinal);
        btnEdit.removeAttribute('id');
    });
}

function forceCloseModalWithRemoveIdAndBlur(btnCloseId, modalId, focusId) {
    document.getElementById(btnCloseId).addEventListener("click", () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        document.activeElement.blur(); //Fuerza a que se quite el foco de un elemento
        modal.hide();
        let focusIdFinal = `#${focusId}`;
        moveFocus(focusIdFinal);
        const btnEdit = document.querySelector(focusIdFinal);
        btnEdit.removeAttribute('id');
    });
}

