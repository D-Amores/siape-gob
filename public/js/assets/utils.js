// ------------------------------
// Variables globales
// ------------------------------
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let categoriasGlobales = [];
let editingAssetId = null;
let assetIdToDelete = null;

// ------------------------------
// Modal dinámico por categoría
// ------------------------------
const categoriaSelect = document.getElementById('categoria');
const camposDinamicos = document.getElementById('camposDinamicos');
const tableBody = document.querySelector('#file_export tbody');

const camposGenericos = `
    <h6 class="text-uppercase text-secondary fw-semibold mb-3">
        <i class="fas fa-cogs me-2"></i>Detalles Específicos
    </h6>
    <div class="row g-3 mt-2">
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="procesador" placeholder="Procesador">
                <label for="procesador">Procesador</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="velocidad" placeholder="Velocidad">
                <label for="velocidad">Velocidad</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="memoria" placeholder="Memoria">
                <label for="memoria">Memoria</label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" id="almacenamiento" placeholder="Capacidad de almacenamiento">
                <label for="almacenamiento">Capacidad de almacenamiento</label>
            </div>
        </div>
    </div>
`;

// ------------------------------
// Detecta la categoria 
// ------------------------------
if (categoriaSelect) {
    categoriaSelect.addEventListener('change', () => {
        const categoriaId = parseInt(categoriaSelect.value);
        camposDinamicos.innerHTML = '';

        if (!categoriaId) return;

        const categoriaSeleccionada = categoriasGlobales.find(cat => cat.id === categoriaId);
        if (categoriaSeleccionada && categoriaSeleccionada.special_specifications) {
            camposDinamicos.innerHTML = camposGenericos;
        }
    });
}