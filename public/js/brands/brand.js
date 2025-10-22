const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const brandForm = document.getElementById('brandForm');
const brandNameInput = document.getElementById('brandName');

const editBrandForm = document.getElementById('editBrandForm');
const editBrandNameInput = document.getElementById('editBrandName');
const editBrandIdInput = document.getElementById('editBrandId');


document.addEventListener('DOMContentLoaded', function () {
    loadBrands();

    brandNameInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    editBrandNameInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
});

brandForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    let brandName = brandNameInput.value.trim().toUpperCase();

    const validation = validateBrandForm(brandName);

    if (!validation.isValid) {
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

    brandName = validation.cleanedValue;

    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';

    try {
        const response = await fetch(baseUrl, {
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

        const editBrandNameInput = document.getElementById('editBrandName');
        const editBrandIdInput = document.getElementById('editBrandId');

        editBrandNameInput.value = brandName;
        editBrandIdInput.value = brandId;
    }
});

editBrandForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    let brandName = editBrandNameInput.value.trim().toUpperCase();
    const brandId = editBrandIdInput.value;

    const validation = validateBrandForm(brandName);

    if (!validation.isValid) {
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

    brandName = validation.cleanedValue;

    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...';

    try {
        // NUEVO: Petición PUT para actualizar
        const response = await fetch(`${baseUrl}/${brandId}`, {
            method: 'PUT', // Usamos PUT para actualizar
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
                content: 'La marca se ha actualizado correctamente.',
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

            const modal = bootstrap.Modal.getInstance(document.getElementById('editBrandModal'));
            modal.hide();

            editBrandForm.reset();

            loadBrands();
        } else {
            let errorMessage = 'Ocurrió un error al actualizar la marca.';
            if (data.message) {
                errorMessage = data.message;
            }

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
        console.error('❌ Error al actualizar la marca:', error);
        $.alert({
            title: 'Error',
            content: 'Ocurrió un error al actualizar la marca. Revisa la consola para más detalles.',
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
        const response = await fetch(ApiUrl, {
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
    const tableId = 'file_export';

    const columns = [
        {
            data: null,
            render: (d, t, r, meta) => meta.row + 1,
            title: "#"
        },
        {
            data: 'name',
            title: 'Nombre',
            render: (data) => `
                <div class="d-flex align-items-center">
                    <i class="fas fa-tag text-muted me-3"></i>
                    <span>${data}</span>
                </div>
            `
        },
        {
            data: null,
            title: 'Acciones',
            className: 'text-center',
            orderable: false,
            render: (data, type, row) => `
                <div class="d-flex justify-content-center gap-2">
                    <button type="button"
                            class="btn btn-outline-primary border-0 btn-edit"
                            data-brand-id="${row.id}"
                            data-brand-name="${row.name}"
                            data-bs-toggle="modal"
                            data-bs-target="#editBrandModal"
                            title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button"
                            class="btn btn-outline-danger border-0 btn-delete"
                            data-brand-id="${row.id}"
                            data-brand-name="${row.name}"
                            title="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `
        }
    ];

    const tooltips = '[data-bs-toggle="tooltip"]';

    bottomTableConfig(tableId, brands, columns, tooltips);
};


const deleteBrand = async (brandId) => {
    try {
        const response = await fetch(`${baseUrl}/${brandId}`, {
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
