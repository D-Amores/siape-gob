document.addEventListener('DOMContentLoaded', function () {

    const formBien = document.getElementById('formNuevoBien');

    // ------------------------------
    // Cargar tabla
    // ------------------------------
    function loadAssets() {
        if ($.fn.DataTable.isDataTable('#file_export')) {
            const table = $('#file_export').DataTable();

            fetch(vURIAssetsTableApi, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    option: 'table'
                })
            })
            .then(res => res.json())
            .then(json => {
                if (!json.ok) return;
                table.clear();
                table.rows.add(json.data);
                table.draw();
            })
            .catch(err => {
                console.error("Error al refrescar la tabla:", err);
                showAlert(
                    "Error de conexión al intentar refrescar la tabla.",
                    "red",
                    "Error loadAssets",
                    () => console.log("Error al refrescar la tabla:", err),
                    3000
                );
            });
        }
    }

    // Carga inicial
    loadAssets();

    // ------------------------------
    // Envío del formulario
    // ------------------------------
    if (formBien) {
        formBien.addEventListener('submit', async e => {
            e.preventDefault();
            const mode = formBien.dataset.mode;

            const formData = {
                inventory_number: document.getElementById('numeroInventario').value,
                brand_id: document.getElementById('marca').value,
                model: document.getElementById('modelo').value,
                serial_number: document.getElementById('serie').value,
                is_active: document.getElementById('is_active').value === '1',
                status_id: document.getElementById('status_id').value,
                category_id: document.getElementById('categoria').value,
                description: document.getElementById('descripcion').value,
                type: document.getElementById('tipo').value || null,
                cpu: document.getElementById('procesador')?.value || null,
                speed: document.getElementById('velocidad')?.value || null,
                memory: document.getElementById('memoria')?.value || null,
                storage: document.getElementById('almacenamiento')?.value || null,
                acquisition_date: document.getElementById('acquisition_date')?.value || null,
                model_year: document.getElementById('model_year')?.value || null
            };

            const action = async () => {
                try {
                    let res;

                    if (mode === 'create') {
                        res = await fetch(vURIAssetsApi, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify(formData)
                        });
                    } else {
                        const id = formBien.dataset.id;
                        res = await fetch(`${vURIAssetsApi}/${id}`, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify(formData)
                        });
                    }

                    const result = await res.json();

                    if (!result.ok) {
                        if (res.status === 422 && result.errors) {
                            let messages = Object.values(result.errors)
                                .flat()
                                .join('<br>');
                            showAlert(messages, "red", "Error de validación");
                            return;
                        }

                        showAlert(result.message || 'Error en la operación.', "red", "Error");
                        return;
                    }

                    showAlert(result.message || 'Operación exitosa.', "green", "Éxito", () => {
                        closeModalForSuccess('modalBien', 'focusAfterSave');
                        if (tableApi) {
                            tableApi.draw(false);
                        }
                    });

                } catch (error) {
                    showAlert('Error al enviar el formulario: ' + error.message, "red", "Error");
                }
            };

            if (mode === 'create') confirmStore(action);
            else confirmUpdate(action);
        });
    }

    // ------------------------------
    // Transforma a mayúscula todos los inputs
    // ------------------------------
    if (formBien) {
        formBien.addEventListener('input', e => {
            const target = e.target;
            if ((target.tagName === 'INPUT' && target.type === 'text') || target.tagName === 'TEXTAREA') {
                const start = target.selectionStart;
                const end = target.selectionEnd;
                target.value = target.value.toUpperCase();
                target.setSelectionRange(start, end);
            }
        });
    }
});
