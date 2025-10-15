// admin/personeel/form-validate.js
function initFormValidation() {
    // Formulario Crear Usuario
    $('#userCreateForm').validate({
        rules: {
            username: { required: true, minlength: 6 },
            password: { required: true, minlength: 8 },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            },
            personnel_id: { required: true }
        },
        messages: {
            username: { required: "El nombre de usuario es obligatorio", minlength: "Mínimo 6 caracteres" },
            password: { required: "La contraseña es obligatoria", minlength: "Mínimo 8 caracteres" },
            password_confirmation: {
                required: "Debes confirmar la contraseña",
                minlength: "Mínimo 8 caracteres",
                equalTo: "Las contraseñas no coinciden"
            },
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

    // --- FORMULARIO EDITAR USUARIO ---
    $('#userEditForm').validate({
        rules: {
            username: { required: true, minlength: 6 },
            password: {
                minlength: 8, // solo valida si no está vacío
            },
            password_confirmation: {
                minlength: 8,
                equalTo: "#password_edit"
            },
            personnel_id: { required: true }
        },
        messages: {
            username: { required: "El nombre de usuario es obligatorio", minlength: "Mínimo 6 caracteres" },
            password: { minlength: "Mínimo 8 caracteres" },
            password_confirmation: {
                minlength: "Mínimo 8 caracteres",
                equalTo: "Las contraseñas no coinciden"
            },
            personnel_id: { required: "Selecciona un personal" }
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

    // 💡 Reglas dinámicas: solo pedir confirmación si hay contraseña
    $('#password_edit').on('keyup change', function () {
        const hasPassword = $(this).val().length > 0;
        $('#password_confirmation_edit').rules('add', {
            required: hasPassword,
            messages: {
                required: "Debes confirmar la contraseña si la cambias"
            }
        });
    });
}

// Función para comprobar si un formulario es válido
function isFormValid(formSelector) {
    return $(formSelector).valid();
}

// Inicializar al cargar
$(document).ready(initFormValidation);
