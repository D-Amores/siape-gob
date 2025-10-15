//funciones auxiliares
// Capitalizar la primera letra de una palabra
function capitalizeWords(text) {
    if (!text) return '';
    return text
        .toLowerCase()
        .split(' ')
        .filter(word => word.trim() !== '') // evita espacios dobles
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
}

// resetear formulario y selects con Select2
function resetFormAndSelect(form) {
    form.reset();

    // Eliminar clases de validación
    const elements = form.querySelectorAll('.is-valid, .is-invalid');
    elements.forEach(el => el.classList.remove('is-valid', 'is-invalid'));

    // Resetear selects con Select2
    $(form).find('select').val('').trigger('change');
}

// Cerrar modal
function closeModal(modalId, focusButtonId) {
    // Cerrar modal y resetear formulario solo si la creación fue exitosa
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    document.getElementById(focusButtonId)?.focus();
    modal?.hide();
}

// Para obtener solo los datos que cambiaron en el formulario
function getChangedData(original, current) {
    const changed = {};

    // 🔹 Función auxiliar para normalizar tipos
    const normalizeValue = (key, value) => {
        if (value === null || value === undefined) return value;

        const strValue = String(value).trim();

        // Si termina en _id o es numérico → número
        if (key.endsWith('_id') || /^-?\d+$/.test(strValue)) {
            return Number(strValue);
        }

        // Si parece booleano → boolean
        if (["true", "false", "1", "0"].includes(strValue.toLowerCase())) {
            return strValue === "true" || strValue === "1";
        }

        return strValue;
    };

    // 🔹 Comparamos ambos objetos normalizados
    for (const key in current) {
        const normalizedCurrent = normalizeValue(key, current[key]);
        const normalizedOriginal = normalizeValue(key, original[key]);

        console.log(`Comparando campo "${key}": original="${normalizedOriginal}", actual="${normalizedCurrent}"`);

        if (normalizedCurrent !== normalizedOriginal) {
            changed[key] = normalizedCurrent;
        }
    }

    return changed;
}