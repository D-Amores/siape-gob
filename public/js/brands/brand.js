const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const brandForm = document.getElementById('brandForm');
const brandNameInput = document.getElementById('brandName');


document.addEventListener('DOMContentLoaded', function () {
    loadBrands();

    brandNameInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
});

brandForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    let brandName = brandNameInput.value.trim().toUpperCase();

    const validation = validateBrandForm(brandName);

    if (!validation.isValid) {
        // Mostrar todos los errores en una sola alerta
        const errorMessage = validation.errors.join('<br>• ');
        $.alert({
            title: 'Errores en el formulario',
            content: `• ${errorMessage}`,
            type: 'red',
            theme: 'material',
            buttons: {
                ok: {
                    text: 'Corregir',
                    btnClass: 'btn-red'
                }
            }
        });
        return;
    }

    // Usar el valor limpio (sin espacios extras)
    brandName = validation.cleanedValue;

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
        } else {
            let errorMessage = 'Ocurrió un error al guardar la marca.';

            $.alert({
                title: 'Error',
                content: errorMessage,
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

document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-delete')) {
        const button = e.target.closest('.btn-delete');
        const brandId = button.getAttribute('data-brand-id');
        const brandName = button.getAttribute('data-brand-name');

        $.confirm({
            title: 'Confirmar eliminación',
            content: `¿Estás seguro de que deseas eliminar la marca "<strong>${brandName}</strong>"?`,
            type: 'red',
            theme: 'material',
            backgroundDismiss: true,
            buttons: {
                confirm: {
                    text: 'Eliminar',
                    btnClass: 'btn-red',
                    action: function () {
                        deleteBrand(brandId);
                    }
                },
                cancel: {
                    text: 'Cancelar',
                    btnClass: 'btn-default'
                }
            }
        });
    }

    if (e.target.closest('.btn-edit')) {
        const button = e.target.closest('.btn-edit');
        const brandId = button.getAttribute('data-brand-id');
        const brandName = button.getAttribute('data-brand-name');


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
                    <i class="fas fa-plus"></i><br>
                    Agrega la primera marca
                </td>
                <td class="text-center text-muted py-4">
                    <p>No hay marcas disponibles</p>
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

const deleteBrand = async (brandId) => {
    try {
        const response = await fetch(`brands/${brandId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.ok) {
            $.alert({
                title: 'Éxito',
                content: 'La marca se ha eliminado correctamente.',
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
            loadBrands();
        }
    } catch (error) {
        console.error('❌ Error al eliminar la marca:', error);
        $.alert({
            title: 'Error',
            content: 'Ocurrió un error al eliminar la marca. Revisa la consola para más detalles.',
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
    }
}

function validateBrandForm(brandName) {
    const errors = [];

    // 1. Campo requerido
    if (!brandName) {
        errors.push('El nombre de la marca es obligatorio');
    }

    // 2. Longitud mínima
    if (brandName.length < 2) {
        errors.push('El nombre debe tener al menos 2 caracteres');
    }

    // 3. Longitud máxima
    if (brandName.length > 50) {
        errors.push('El nombre no puede exceder los 50 caracteres');
    }

    const validChars = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-_.&]+$/;
    if (!validChars.test(brandName)) {
        errors.push('Solo se permiten letras, números, espacios y los caracteres: - _ . &');
    }

    const cleanedName = brandName.trim().replace(/\s+/g, ' ');

    return {
        isValid: errors.length === 0,
        errors: errors,
        cleanedValue: cleanedName
    };
}
