// admin/personeel/form-validate.js
function initFormValidation() {
    // Formulario Crear Usuario
    $('#userCreateForm').validate({
        rules: {
            username: { required: true, minlength: 6 },
            password: { required: true, minlength: 8 },
            personnel_id: { required: true }
        },
        messages: {
            username: { required: "El nombre de usuario es obligatorio", minlength: "Mínimo 6 caracteres" },
            password: { required: "La contraseña es obligatoria", minlength: "Mínimo 8 caracteres" },
            personnel_id: { required: "Selecciona un personal" }
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
