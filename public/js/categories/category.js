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

