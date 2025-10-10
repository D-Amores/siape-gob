const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const brandForm = document.getElementById('brandForm');
const brandNameInput = document.getElementById('brandName');


document.addEventListener('DOMContentLoaded', function () {
    loadBrands();
});

brandForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const brandName = brandNameInput.value.trim();

    if (!brandName) {
        $.alert({
            title: 'Atención',
            content: 'El nombre de la marca no puede estar vacío.',
            type: 'orange',
            theme: 'material',
            backgroundDismiss: true,
            buttons: {
                ok: {
                    text: 'Aceptar',
                    btnClass: 'btn-orange'
                }
            }
        });
    }

    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';

    try {
        const response = await fetch('brands', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: brandName
            })
        });

        const data = await response.json();

        if (data.ok) {
            $.alert({
                title: 'Éxito',
                content: 'La marca se ha guardado correctamente.',
                type: 'green',
                theme: 'material',
                backgroundDismiss: true,
                buttons: {
                    ok: {
                        text: 'Aceptar',
                        btnClass: 'btn-green'
                    }
                }
            });
            const modal = bootstrap.Modal.getInstance(document.getElementById('addBrandModal'));
            modal.hide();
            brandForm.reset();
            loadBrands();
        }
    } catch (error) {
        console.error('❌ Error al guardar la marca:', error);
        $.alert({
            title: 'Error',
            content: 'Ocurrió un error al guardar la marca. Revisa la consola para más detalles.',
            type: 'red',
            theme: 'material',
            backgroundDismiss: true,
            buttons: {
                ok: {
                    text: 'Aceptar',
                    btnClass: 'btn-red'
                }
            }
        });
    } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    }
});

const loadBrands = async () => {

    try {
        const response = await fetch('/brands/api', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.ok) {
            updateBrandsTable(data.data);
        } else {
            console.error('❌ Error al cargar marcas:', data.message);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar las marcas. Revisa la consola.',
                confirmButtonColor: '#d33'
            });
        }

    } catch (error) {
        console.error('❌ Error al cargar marcas:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar las marcas. Revisa la consola.',
            confirmButtonColor: '#d33'
        });
    }
}

const updateBrandsTable = (brands) => {
    const table = document.getElementById('file_export');

    if ($.fn.DataTable.isDataTable(table)) {
        $(table).DataTable().destroy();
    }

    const tbody = table.querySelector('tbody');
    tbody.innerHTML = '';

    if (brands.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    No hay marcas registradas
                </td>
                <td class="text-center text-muted py-4">
                    <i class="fas fa-plus"></i><br>
                    Agrega la primera marca
                </td>
            </tr>
        `;
    } else {
        brands.forEach(brand => {
            const row = document.createElement('tr');
            row.setAttribute('data-brand-id', brand.id);
            row.innerHTML = `
                <td class="ps-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tag text-muted me-3"></i>
                        <span>${brand.name}</span>
                    </div>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button"
                                class="btn btn-outline-primary border-0 btn-edit"
                                data-brand-id="${brand.id}"
                                data-brand-name="${brand.name}"
                                title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button"
                                class="btn btn-outline-danger border-0 btn-delete"
                                data-brand-id="${brand.id}"
                                data-brand-name="${brand.name}"
                                title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    $(table).DataTable({
        language: {
            url: language
        },
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        dom: 'Bfrtip',  // ✅ Restauramos el dom para botones
        buttons: [      // ✅ Restauramos todos los botones de exportación
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        columnDefs: [
            { orderable: false, targets: 1 } // Desactivar ordenamiento en columna de acciones
        ]
    });

};
