function moveFocus() {
    $("#btnPersonnelCreateModal").trigger("focus");
}

$(document).ready(function () {
    $("#btnClosePersonnelCreate").on("click", function () {
        moveFocus();
        $("#personnelCreateModal").modal("hide");
    });
});

function moveModalFocus(modalId, buttonId) {
    //
}

