// admin/personeel/form-actions.js
document.addEventListener("DOMContentLoaded", function () {
    // Inicializar Select2
    const selects = document.querySelectorAll('.areaSelect');
    selects.forEach(select => {
        $(select).select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Seleccionar...'
        });
    });
});
