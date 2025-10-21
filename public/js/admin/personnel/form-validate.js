// admin/personeel/form-validate.js
function initFormValidation() {
    // Formulario Crear Personal
    $('#personnelCreateForm').validate({
        rules: {
            name: { required: true, minlength: 3 },
            last_name: { required: true, minlength: 3 },
            email: { required: true, email: true },
            area_id: { required: true }
        },
        messages: {
            name: { required: "El nombre es obligatorio", minlength: "Mínimo 3 caracteres" },
            last_name: { required: "El apellido es obligatorio", minlength: "Mínimo 3 caracteres" },
            email: { required: "El correo es obligatorio", email: "Correo inválido" },
            area_id: { required: "Selecciona un área" }
        },
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        errorPlacement: function(error, element) {
            if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.next('.select2'));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) { $(element).addClass('is-invalid'); },
        unhighlight: function(element) { $(element).removeClass('is-invalid').addClass('is-valid'); }
    });
}

// Función para comprobar si un formulario es válido
function isFormValid(formSelector) {
    return $(formSelector).valid();
}

// Inicializar al cargar
$(document).ready(initFormValidation);
