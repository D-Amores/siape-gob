// assets/tracking/form-validate.js
function initTrackingFormValidation() {
    // --- FORMULARIO ACTUALIZAR SEGUIMIENTO ---
    $('#trackingUpdateForm').validate({
        rules: {
            status_id: { required: true },
            asset_status_id: { required: true },
            comment: { maxlength: 500 },
        },
        messages: {
            status_id: { required: "Selecciona el estado del seguimiento" },
            asset_status_id: { required: "Selecciona el estado del activo" },
            comment: { maxlength: "El comentario no debe exceder los 500 caracteres" },
        },
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        errorPlacement: function (error, element) {
            if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.next('.select2'));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element) { $(element).addClass('is-invalid'); },
        unhighlight: function (element) { $(element).removeClass('is-invalid').addClass('is-valid'); }
    });

    // --- FORMULARIO CERRAR SEGUIMIENTO ---
    $('#trackingCloseForm').validate({
        rules: {
            status_id: { required: true },
            asset_status_id: { required: true },
            work_done: { required: true, minlength: 10 },
            observation: { required: true, minlength: 5 },
            comment: { required: true, minlength: 5 },
        },
        messages: {
            status_id: { required: "Selecciona el estado del seguimiento" },
            asset_status_id: { required: "Selecciona el estado del activo" },
            work_done: {
                required: "Describe el trabajo realizado",
                minlength: "Debe tener al menos 10 caracteres"
            },
            observation: {
                required: "Agrega una observación",
                minlength: "Debe tener al menos 5 caracteres"
            },
            comment: {
                required: "Agrega un comentario adicional",
                minlength: "Debe tener al menos 5 caracteres"
            },
        },
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        errorPlacement: function (error, element) {
            if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.next('.select2'));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element) { $(element).addClass('is-invalid'); },
        unhighlight: function (element) { $(element).removeClass('is-invalid').addClass('is-valid'); }
    });
}

// Función genérica para verificar si un formulario es válido
function isTrackingFormValid(formSelector) {
    return $(formSelector).valid();
}

// Inicializar validaciones al cargar el DOM
$(document).ready(initTrackingFormValidation);
