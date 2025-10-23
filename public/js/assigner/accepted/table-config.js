function loadAssetsAccepted(assetPendings) {
    const tableId = "accepted_assignments";

    // Definimos las columnas que van a recibir los datos
    const columns = [];

    // Llamada a tu función moderna
    bottomTableConfig(tableId, assetPendings, columns, '[data-bs-toggle="tooltip"]');
}
