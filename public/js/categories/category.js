const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const categoryForm = document.getElementById('categoryForm');
const categoryNameInput = document.getElementById('categoryName');

const editCategoryForm = document.getElementById('editCategoryForm');
const editCategoryNameInput = document.getElementById('editCategoryName');
const editCategoryIdInput = document.getElementById('editCategoryId');
const specialCategoryInput = document.getElementById('specialCategory');

document.addEventListener('DOMContentLoaded', function () {
    loadCategories();

    categoryNameInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    editCategoryNameInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
});

categoryForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    let categoryName = categoryNameInput.value.trim().toUpperCase();
    let special = specialCategoryInput.checked ? 1 : 0;

    const validation = validateCategoryForm(categoryName);

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

    categoryName = validation.cleanedValue;

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
                name: categoryName,
                special_specifications: special
            })
        });

        const data = await response.json();

        if (data.ok) {
            $.alert({
                title: 'Éxito',
                content: 'La categoría se ha guardado correctamente.',
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
            const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
            modal.hide();
            categoryForm.reset();
            loadCategories();
        } else {
            let errorMessage = 'Ocurrió un error al guardar la categoría.';

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
        console.error('❌ Error al guardar la categoría:', error);
        $.alert({
            title: 'Error',
            content: 'Ocurrió un error al guardar la categoría. Revisa la consola para más detalles.',
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
        const categoryId = button.getAttribute('data-category-id');
        const categoryName = button.getAttribute('data-category-name');

        confirmDestroy(
            () => {
                deleteCategory(categoryId);
            },
            `¿Estás seguro de que deseas eliminar la categoría "<strong>${categoryName}</strong>"? Esta acción no podrá ser revertida.`,
            'Eliminar'
        );
    }

    if (e.target.closest('.btn-edit')) {
        const button = e.target.closest('.btn-edit');
        const categoryId = button.getAttribute('data-category-id');
        const categoryName = button.getAttribute('data-category-name');
        const special = button.getAttribute('data-category-special');

        editCategoryNameInput.value = categoryName;
        editCategoryIdInput.value = categoryId;

        document.getElementById('specialCategoryEdit').checked = special == 1 ? true : false;
    }
});

editCategoryForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    let categoryName = editCategoryNameInput.value.trim().toUpperCase();
    const categoryId = editCategoryIdInput.value;

    const validation = validateCategoryForm(categoryName);

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

    categoryName = validation.cleanedValue;

    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...';
    const specialValue = document.getElementById('specialCategoryEdit').checked ? 1 : 0;

    try {
        // NUEVO: Petición PUT para actualizar
        const response = await fetch(`${baseUrl}/${categoryId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: categoryName,
                special_specifications: specialValue
            })
        });

        const data = await response.json();

        if (data.ok) {
            $.alert({
                title: 'Éxito',
                content: 'La categoría se ha actualizado correctamente.',
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

            const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
            modal.hide();

            editCategoryForm.reset();

            loadCategories();
        } else {
            let errorMessage = 'Ocurrió un error al actualizar la categoría.';
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
        console.error('❌ Error al actualizar la categoría:', error);
        $.alert({
            title: 'Error',
            content: 'Ocurrió un error al actualizar la categoría. Revisa la consola para más detalles.',
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

const loadCategories = async () => {

    try {
        const response = await fetch(UrlLoad, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (data.ok) {
            updateCategoriesTable(data.data);
        } else {
            console.error('❌ Error al cargar categorías:', data.message);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar las categorías. Revisa la consola.',
                confirmButtonColor: '#d33'
            });
        }

    } catch (error) {
        console.error('❌ Error al cargar categorías:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar las categorías. Revisa la consola.',
            confirmButtonColor: '#d33'
        });
    }
}

const updateCategoriesTable = (categories) => {

    const tableSelector = 'file_export';

    const columns = [
        {
            data: null,
            render: (d, t, r, meta) => meta.row + 1,
            title: "#"
        },
        {
            data: 'name',
            title: 'Nombre',
            render: function (data, type, row) {
                return `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tag text-muted me-3"></i>
                        <span>${data}</span>
                    </div>
                `;
            }
        },
        {
            data: 'special_specifications',
            title: 'Tipo',
            className: 'text-center',
            render: function (data, type, row) {
                return data === 1
                    ? '<span class="badge bg-success">Especial</span>'
                    : '<span class="badge bg-secondary">Normal</span>';
            }
        },
        {
            data: null,
            title: 'Acciones',
            className: 'text-center',
            orderable: false,
            render: function (data, type, row) {
                return `
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button"
                                class="btn btn-outline-primary border-0 btn-edit"
                                data-category-id="${row.id}"
                                data-category-name="${row.name}"
                                data-category-special="${row.special_specifications}"
                                data-bs-toggle="modal"
                                data-bs-target="#editCategoryModal"
                                title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button"
                                class="btn btn-outline-danger border-0 btn-delete"
                                data-category-id="${row.id}"
                                data-category-name="${row.name}"
                                title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `;
            }
        }
    ];
    const tooltips = '[data-bs-toggle="tooltip"]';

    bottomTableConfig(tableSelector, categories, columns, tooltips);
};

const deleteCategory = async (categoryId) => {
    try {
        const response = await fetch(`${baseUrl}/${categoryId}`, {
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
                content: 'La categoría se ha eliminado correctamente.',
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
            loadCategories();
        }
        else {
            showAlert(data.message || 'Ocurrió un error al eliminar la categoría.', 'red', 'Error', null, 0);
        }
    } catch (error) {
        console.error('❌ Error al eliminar la categoría:', error);
        $.alert({
            title: 'Error',
            content: 'Ocurrió un error al eliminar la categoría. Revisa la consola para más detalles.',
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

function validateCategoryForm(categoryName) {
    const errors = [];

    if (!categoryName) {
        errors.push('El nombre de la categoría es obligatorio');
    }

    if (categoryName.length < 2) {
        errors.push('El nombre debe tener al menos 2 caracteres');
    }

    if (categoryName.length > 50) {
        errors.push('El nombre no puede exceder los 50 caracteres');
    }

    const validChars = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-_.&]+$/;
    if (!validChars.test(categoryName)) {
        errors.push('Solo se permiten letras, números, espacios y los caracteres: - _ . &');
    }

    const cleanedName = categoryName.trim().replace(/\s+/g, ' ');

    return {
        isValid: errors.length === 0,
        errors: errors,
        cleanedValue: cleanedName
    };
}
