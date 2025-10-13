const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const categoryForm = document.getElementById('categoryForm');
const categoryNameInput = document.getElementById('categoryName');

const editCategoryForm = document.getElementById('editCategoryForm');
const editCategoryNameInput = document.getElementById('editCategoryName');
const editCategoryIdInput = document.getElementById('editCategoryId');


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
        const response = await fetch('categories', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: categoryName
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

const loadCategories = async () => {

    try {
        const response = await fetch('/categories/api', {
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
    const table = document.getElementById('file_export');

    if ($.fn.DataTable.isDataTable(table)) {
        $(table).DataTable().destroy();
    }

    const tbody = table.querySelector('tbody');
    tbody.innerHTML = '';

    if (categories.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    <i class="fas fa-plus"></i><br>
                    Agrega la primera categoría
                </td>
                <td class="text-center text-muted py-4">
                    <p>No hay categorías disponibles</p>
                </td>
            </tr>
        `;
    } else {
        categories.forEach(category => {
            const row = document.createElement('tr');
            row.setAttribute('data-category-id', category.id);
            row.innerHTML = `
                <td class="ps-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tag text-muted me-3"></i>
                        <span>${category.name}</span>
                    </div>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button"
                                class="btn btn-outline-primary border-0 btn-edit"
                                data-category-id="${category.id}"
                                data-category-name="${category.name}"
                                data-bs-toggle="modal"
                                data-bs-target="#editCategoryModal"
                                title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button"
                                class="btn btn-outline-danger border-0 btn-delete"
                                data-category-id="${category.id}"
                                data-category-name="${category.name}"
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
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        columnDefs: [
            { orderable: false, targets: 1 } // Desactivar ordenamiento en columna de acciones
        ]
    });

};

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
