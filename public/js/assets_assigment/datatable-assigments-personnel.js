// Inicializar la tabla cuando el DOM esté listo

document.addEventListener('DOMContentLoaded', function () {
    loadPersonnel();
    loadAssets();

    $('#personnel_assigments').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    loadAssetPending();
});
