// /views/js/admin/wikis.js

const API = {
  list: '/FootBook/api/worldcups',  // Para listar todas las wikis (WorldCups)
  show: '/FootBook/api/worldcups/:id' // Para ver el detalle de una wiki
};

function escapeHtml(s = '') {
  return s
    .replaceAll('&','&amp;').replaceAll('<','&lt;')
    .replaceAll('>','&gt;').replaceAll('"','&quot;')
    .replaceAll("'",'&#039;');
}

// Función para hacer el GET de forma reutilizable
async function getJSON(url, opts = {}) {
  const res = await fetch(url, { method: 'GET', ...opts });
  const txt = await res.text();
  let data;
  try {
    data = JSON.parse(txt);
  } catch {
    throw new Error(txt.slice(0, 300));
  }
  if (!res.ok) throw new Error(data.error || ('Error ' + res.status));
  return data;
}

export async function bootWikis() {
  console.log('[Admin] Boot Wikis');
  const container = document.querySelector('#admin-wikis .row'); // Contenedor de las wikis

  if (!container) {
    console.error('[Admin] No se encontró el contenedor para wikis.');
    return;
  }

  // Cargar las wikis desde la API
  try {
    const data = await getJSON(API.list);

    // Verifica si 'data' es un array antes de intentar mapearlo
    if (Array.isArray(data)) {
      container.innerHTML = data.map(c => createWikiCard(c)).join('');
      addEditModalListeners();  // Agregar los listeners de modales
    } else {
      console.error('[Admin] Error al cargar las wikis:', data);
      container.innerHTML = '<p class="text-muted">No se encontraron wikis.</p>';
    }
  } catch (err) {
    console.error('[Admin] Error al obtener wikis:', err);
    container.innerHTML = '<p class="text-danger">Error cargando las wikis.</p>';
  }
}

// Crear una tarjeta de wiki
function createWikiCard(wiki) {
  const imgSrc = wiki.banner_b64 ? `data:image/jpeg;base64,${wiki.banner_b64}` : '/FootBook/img/default.jpg';
  return `
    <div class="col">
      <div class="card h-100">
        <img src="${imgSrc}" class="card-img-top" alt="${escapeHtml(wiki.name)}">
        <div class="card-body">
          <h6 class="card-title mb-0">${escapeHtml(wiki.name)}</h6>
          <button class="btn btn-outline-success btn-sm mt-2 w-100" 
                  data-bs-toggle="modal" 
                  data-bs-target="#editWikiModal${wiki.id}" 
                  data-id="${wiki.id}">
            <i class="bi bi-pencil"></i> Editar wiki
          </button>
        </div>
      </div>
    </div>
  `;
}

// Asignar event listeners a los botones de "Editar"
function addEditModalListeners() {
  const editButtons = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target^="#editWikiModal"]');
  editButtons.forEach(btn => {
    btn.addEventListener('click', async (e) => {
      const id = e.target.closest('[data-id]').getAttribute('data-id');
      await loadWikiData(id);
    });
  });
}

// Cargar datos de la wiki en el modal de edición
async function loadWikiData(id) {
  try {
    const wiki = await getJSON(API.show.replace(':id', id));

    // Verifica si wiki.data existe antes de intentar asignar los valores
    if (wiki && wiki.data) {
      const modal = document.querySelector(`#editWikiModal${id}`);
      if (!modal) return;

      // Llenar los campos con la data obtenida
      modal.querySelector('[name="description"]').value = wiki.data.description || '';
      modal.querySelector('[name="countries"]').value = wiki.data.countries || '';
    } else {
      console.error('[Admin] No se encontró la data de la wiki');
    }
  } catch (err) {
    console.error('[Admin] Error al cargar los datos de la wiki:', err);
  }
}