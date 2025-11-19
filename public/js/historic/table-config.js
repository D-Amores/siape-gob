function loadHistoricTable(data) {
    const tableSelector = "historic";
    const tooltips = '[data-bs-toggle="tooltip"]';

    // ✅ Definimos las columnas según la estructura que envías
    const columns = [
        { 
            data: null,
            title: "#",
            render: (data, type, row, meta) => meta.row + 1 // ✅ Numeración consecutiva
        },
        { data: "asset_name", title: "Bien" },
        { data: "brand", title: "Marca" },
        { data: "category", title: "Categoría" },
        { data: "assigned_by", title: "Asigna" },
        { data: "assigned_to", title: "Asignado" },
        { data: "assignment_date", title: "Asignación" },
        { data: "confirmation_date", title: "Confirmación" },
        { data: "unassignment_date", title: "Desasigna" },
        // { 
        //     data: "status", 
        //     title: "Estado",
        //     render: (data) => {
        //         // ✅ Estado con color visual
        //         const badgeClass = data === "assigned" 
        //             ? "bg-success"
        //             : data === "unassigned" 
        //                 ? "bg-secondary"
        //                 : "bg-warning";
        //         return `<span class="badge ${badgeClass}">${data}</span>`;
        //     }
        // },
        // { 
        //     data: null,
        //     title: "Acción",
        //     orderable: false,
        //     render: (row) => `
        //         <div class="d-flex justify-content-center gap-2">
        //             <button class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Ver Detalles">
        //                 <i class="fas fa-eye"></i>
        //             </button>
        //             <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Eliminar">
        //                 <i class="fas fa-trash-alt"></i>
        //             </button>
        //         </div>
        //     `
        // }
    ];

    // ✅ Llamamos al helper de DataTables con las columnas y datos
    basicTableConfig(tableSelector, data.data, columns, tooltips);
}