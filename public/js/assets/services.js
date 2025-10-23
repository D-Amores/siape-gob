// ------------------------------
// Cargar categorías dinámicamente
// ------------------------------
async function cargarCategorias(selectedId = null) {
    
    const categoriaSelect = document.getElementById('categoria');
    if (!categoriaSelect) {
        return;
    }

    try {
        const res = await fetch(vURICategoriesApi, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const json = await res.json();

        if (!json.ok) {
            showAlert(json.message || 'Error al cargar las categorías', "red", "Error");
            return;
        }

        categoriasGlobales = json.data;

        categoriaSelect.innerHTML = `<option value="" selected>Seleccione categoría</option>`;

        let optionsCount = 0;
        json.data.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.name;
            if (selectedId && selectedId == cat.id) {
                option.selected = true;
            }
            categoriaSelect.appendChild(option);
            optionsCount++;
        });

    } catch (error) {
        console.error('Error en cargarCategorias:', error);
        showAlert('Error en la carga de categorías: ' + error.message, "red", "Error");
    }
}

// ------------------------------
// Cargar marcas dinámicamente
// ------------------------------
async function cargarMarcas(selectedId = null) {
    
    try {
        const marcaSelect = document.getElementById('marca');
        if (!marcaSelect) {
            return;
        }

        const res = await fetch(vURIBrandssApi, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const json = await res.json();

        if (!json.ok) {
            console.error('Error en respuesta JSON:', json.message);
            showAlert(json.message || 'Error al cargar marcas', "red", "Error");
            return;
        }

        marcaSelect.innerHTML = '<option value="">Seleccione marca</option>';

        let optionsCount = 0;
        json.data.forEach(m => {
            const option = document.createElement('option');
            option.value = m.id;
            option.textContent = m.name;
            if (selectedId && selectedId == m.id) {
                option.selected = true;
            }
            marcaSelect.appendChild(option);
            optionsCount++;
        });

    } catch (error) {
        console.error('Error en cargarMarcas:', error);
        showAlert('Error al cargar las marcas: ' + error.message, "red", "Error");
    }
}