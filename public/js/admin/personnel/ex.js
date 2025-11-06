// --- Buscador interactivo en el modal ---
const searchInput = document.getElementById('searchPadron');
const searchResults = document.getElementById('searchResults');

let searchTimeout = null;

searchInput.addEventListener('input', async (e) => {
  const value = e.target.value.trim();

  clearTimeout(searchTimeout);

  if (value.length < 3) {
    searchResults.style.display = 'none';
    return;
  }

  // Espera 500ms para evitar consultas por cada tecla
  searchTimeout = setTimeout(async () => {
    const data = await getExternalDataPersonnelApi(value);

    if (!data.length) {
      searchResults.innerHTML = `<div class="list-group-item text-muted">Sin resultados</div>`;
      searchResults.style.display = 'block';
      return;
    }

    // Muestra resultados
    searchResults.innerHTML = data.map(person =>
      `<button type="button" class="list-group-item list-group-item-action"
          data-person='${JSON.stringify(person)}'>
          <strong>${person.persona}</strong><br>
          <small>${person.correo || ''} — ${person.telefono || ''}</small>
      </button>`
    ).join('');

    searchResults.style.display = 'block';
  }, 500);
});

// Cuando seleccionas un resultado
searchResults.addEventListener('click', (e) => {
  const btn = e.target.closest('.list-group-item');
  if (!btn) return;

  const person = JSON.parse(btn.dataset.person);

  // Separar el nombre completo en palabras
  const parts = person.persona.trim().split(/\s+/);

  let nombre = '';
  let apellidoPaterno = '';
  let apellidoMaterno = '';

  if (parts.length >= 3) {
    apellidoMaterno = parts.pop(); // última palabra
    apellidoPaterno = parts.pop(); // penúltima palabra
    nombre = parts.join(' ');      // el resto
  } else if (parts.length === 2) {
    [nombre, apellidoPaterno] = parts;
  } else {
    nombre = parts[0] ?? '';
  }

  // Rellenar los campos del formulario
  document.getElementById('name').value = nombre;
  document.getElementById('last_name').value = apellidoPaterno;
  document.getElementById('middle_name').value = apellidoMaterno;
  document.getElementById('email').value = person.correo ?? '';
  document.getElementById('phone').value = person.telefono ?? '';

  // Cerrar la lista
  searchResults.style.display = 'none';
  searchInput.value = person.persona;

  console.log({ nombre, apellidoPaterno, apellidoMaterno });
});

