// /views/js/admin/wikis.js
const API_BASE = '/FootBook/API/worldcups.php';
const DEFAULT_BANNER = '/FootBook/img/russia2018.png';

const container = document.getElementById('worldcupContainer');
let currentEditingId = null;

/* -------------------- Utils -------------------- */
function guessMimeFromB64(b64) {
  if (!b64 || b64.length < 4) return 'image/jpeg';
  if (b64.startsWith('/9j/'))   return 'image/jpeg';
  if (b64.startsWith('iVBOR'))  return 'image/png';
  if (b64.startsWith('UklGR'))  return 'image/webp';
  if (b64.startsWith('R0lGO'))  return 'image/gif';
  return 'image/jpeg';
}

function b64src(b64) {
  return `data:${guessMimeFromB64(b64)};base64,${b64}`;
}

function getBannerSrc(obj) {
  const b64 = obj.banner_b64 || obj.bannerB64 || obj.banner;
  if (b64 && typeof b64 === 'string' && b64.length > 10) {
    return b64src(b64);
  }
  return DEFAULT_BANNER;
}

function normalizeCup(raw = {}) {
  // Depuración: ver qué viene de la API
  console.log('Raw data from API:', raw);
  
  return {
    id: raw.id ?? raw.worldcup_id ?? raw.id_worldcup ?? raw.ID,
    name: raw.name ?? raw.title ?? raw.nom ?? '',
    country: raw.country ?? raw.host_country ?? raw.country_name ?? '',
    year: raw.year ?? raw.season ?? raw.edition_year ?? '',
    description: raw.description ?? raw.desc ?? '',
    banner_b64: raw.banner_b64 ?? raw.bannerB64 ?? raw.banner ?? null,
    banner_exists: raw.banner_exists ?? raw.has_banner ?? raw.bannerExists ?? 0,
    status: raw.status ?? 1,
  };
}

async function apiRequest(endpoint, options = {}) {
  try {
    const url = endpoint.startsWith('http') ? endpoint : `${API_BASE}${endpoint}`;
    const response = await fetch(url, {
      ...options,
      headers: {
        'Content-Type': 'application/json',
        ...options.headers,
      },
    });

    const text = await response.text();
    let json;
    
    try {
      json = JSON.parse(text);
    } catch {
      throw new Error(`Invalid JSON response: ${text.slice(0, 300)}`);
    }

    if (!response.ok) {
      throw new Error(json.error || `HTTP ${response.status}`);
    }

    if (json.ok === true && 'data' in json) {
      return json.data;
    }
    
    if (json.ok === false) {
      throw new Error(json.error || 'API error');
    }

    return json;
    
  } catch (error) {
    console.error('API Request failed:', error);
    throw error;
  }
}

// Convertir archivo a base64
function fileToBase64(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = () => {
      // Remover el prefijo data:image/...;base64,
      const base64 = reader.result.split(',')[1];
      resolve(base64);
    };
    reader.onerror = reject;
    reader.readAsDataURL(file);
  });
}

/* -------------------- Render -------------------- */
function createWikiCard(cup) {
  const imgSrc = getBannerSrc(cup);
  const name = cup.name ?? '';
  const country = cup.country ?? '';
  const year = cup.year ?? '';
  const id = cup.id ?? '';

  return `
    <div class="card shadow-sm h-100">
      <img class="card-img-top" 
           alt="${name}"
           src="${imgSrc}"
           onerror="this.onerror=null;this.src='${DEFAULT_BANNER}'"
           style="height: 150px; object-fit: cover;">
      <div class="card-body text-center">
        <h6 class="card-title mb-1">${name}</h6>
        <p class="text-muted small mb-2">${country} • ${year}</p>
          <button class="btn btn-outline-success btn-sm flex-fill edit-wiki-btn"
                  data-id="${id}"
                  data-bs-toggle="modal"
                  data-bs-target="#editWikiModal">
            <i class="bi bi-pencil"></i> Editar
          </button>
          <button class="btn btn-outline-danger btn-sm delete-wiki-btn"
                  data-id="${id}"
                  data-name="${name}">
            <i class="bi bi-trash"></i> Eliminar
          </button>
      </div>
    </div>
  `;
}

/* -------------------- Data Loading -------------------- */
async function loadWorldCups() {
  try {
    container.innerHTML = '<div class="col-12 text-center"><div class="spinner-border" role="status"></div></div>';
    
    const data = await apiRequest('?type=full');
    const cups = (Array.isArray(data) ? data : []).map(normalizeCup);

    if (!cups.length) {
      container.innerHTML = '<p class="col-12 text-center text-muted">No hay mundiales registrados.</p>';
      return;
    }

    container.innerHTML = cups.map(c => `
      <div class="col-md-4 col-lg-3 mb-3">
        ${createWikiCard(c)}
      </div>
    `).join('');

    // Eventos para editar
    document.querySelectorAll('.edit-wiki-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        const id = btn.getAttribute('data-id');
        await loadWikiData(id);
      });
    });

    // Eventos para eliminar
    document.querySelectorAll('.delete-wiki-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        confirmDelete(id, name);
      });
    });

  } catch (err) {
    console.error('Error fetching World Cups:', err);
    container.innerHTML = `
      <div class="col-12">
        <div class="alert alert-danger">
          <i class="bi bi-exclamation-triangle"></i> 
          ${err.message || 'Error cargando mundiales'}
        </div>
      </div>
    `;
  }
}

async function loadWikiData(id) {
  try {
    const data = await apiRequest(`/${id}`);
    const d = normalizeCup(data);
    currentEditingId = id;

    const modal = document.getElementById('editWikiModal');
    if (!modal) {
      console.error('Modal #editWikiModal no encontrado');
      return;
    }

    // Título del modal
    const titleEl = modal.querySelector('.modal-title');
    if (titleEl) titleEl.textContent = `Editar Wiki - ${d.name || ''}`;

    // Llenar campos del formulario
    const nameEl = modal.querySelector('[name="name"]');
    const countryEl = modal.querySelector('[name="country"]');
    const yearEl = modal.querySelector('[name="year"]');
    const descEl = modal.querySelector('[name="description"]');
    const previewImg = modal.querySelector('[name="main_image_preview"]');

    if (nameEl) nameEl.value = d.name ?? '';
    if (countryEl) countryEl.value = d.country ?? '';
    if (yearEl) yearEl.value = d.year ?? '';
    if (descEl) descEl.value = d.description ?? '';
    
    if (previewImg) {
      previewImg.src = getBannerSrc(d);
      previewImg.onerror = () => { previewImg.src = DEFAULT_BANNER; };
    }

  } catch (err) {
    console.error('Error cargando wiki:', err);
    alert(`Error cargando la información: ${err.message}`);
  }
}

async function confirmDelete(id, name) {
  if (!confirm(`¿Estás seguro de eliminar "${name}"?\n\nEsta acción no se puede deshacer.`)) {
    return;
  }

  try {
    await apiRequest(`/${id}?mode=soft`, {
      method: 'DELETE'
    });

    alert('Mundial eliminado exitosamente');
    await loadWorldCups();

  } catch (err) {
    console.error('Error deleting World Cup:', err);
    alert(`Error al eliminar: ${err.message}`);
  }
}

/* -------------------- Form Handlers -------------------- */
async function handleFormSubmit(e) {
  e.preventDefault();
  
  const form = e.target;
  const submitBtn = form.querySelector('button[type="submit"]');
  const originalBtnText = submitBtn.innerHTML;
  
  try {
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

    const formData = {
      name: form.querySelector('[name="name"]')?.value,
      country: form.querySelector('[name="country"]')?.value,
      year: form.querySelector('[name="year"]')?.value,
      description: form.querySelector('[name="description"]')?.value,
    };

    // Manejar imagen si se seleccionó una nueva
    const fileInput = form.querySelector('[name="main_image"]');
    if (fileInput?.files?.[0]) {
      formData.banner = await fileToBase64(fileInput.files[0]);
    }

    let result;
    
    if (currentEditingId) {
      // Actualizar
      formData.id = currentEditingId;
      result = await apiRequest(`/${currentEditingId}`, {
        method: 'PUT',
        body: JSON.stringify(formData)
      });
    } else {
      // Crear nuevo
      result = await apiRequest('', {
        method: 'POST',
        body: JSON.stringify(formData)
      });
    }

    alert(currentEditingId ? 'Mundial actualizado exitosamente' : 'Mundial creado exitosamente');
    
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('editWikiModal'));
    if (modal) modal.hide();
    
    // Recargar lista
    await loadWorldCups();
    
    // Reset
    form.reset();
    currentEditingId = null;

  } catch (err) {
    console.error('Error saving World Cup:', err);
    alert(`Error al guardar: ${err.message}`);
  } finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = originalBtnText;
  }
}

// Preview de imagen al seleccionar archivo
function handleImagePreview(e) {
  const file = e.target.files?.[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = (evt) => {
    const preview = document.querySelector('[name="main_image_preview"]');
    if (preview) {
      preview.src = evt.target.result;
    }
  };
  reader.readAsDataURL(file);
}

// Función para resetear el modal (se puede llamar externamente también)
export function resetWikiModal() {
  const modal = document.getElementById('editWikiModal');
  if (!modal) return;
  
  currentEditingId = null;
  
  // Resetear título
  const titleEl = modal.querySelector('.modal-title');
  if (titleEl) titleEl.textContent = 'Crear nuevo mundial';
  
  // Resetear formulario
  const form = modal.querySelector('form');
  if (form) form.reset();
  
  // Resetear preview de imagen
  const preview = modal.querySelector('[name="main_image_preview"]');
  if (preview) preview.src = DEFAULT_BANNER;
}

/* -------------------- Boot -------------------- */
export async function bootWikis() {
  console.log('[Admin] Boot Wikis');
  
  // Cargar mundiales
  await loadWorldCups();
  
  // Event listener para el formulario de edición
  const editForm = document.querySelector('#editWikiModal form');
  if (editForm) {
    editForm.addEventListener('submit', handleFormSubmit);
  }
  
  // Event listener para preview de imagen
  const imageInput = document.querySelector('#editWikiModal [name="main_image"]');
  if (imageInput) {
    imageInput.addEventListener('change', handleImagePreview);
  }
  
  // Reset cuando se cierra el modal
  const modal = document.getElementById('editWikiModal');
  if (modal) {
    modal.addEventListener('hidden.bs.modal', () => {
      resetWikiModal();
    });
  }
  
  // Event listener para el botón "Crear nuevo"
  const createBtn = document.querySelector('[data-bs-target="#editWikiModal"][onclick*="resetWikiModal"]');
  if (createBtn) {
    createBtn.removeAttribute('onclick'); // Remover el onclick inline
    createBtn.addEventListener('click', resetWikiModal);
  }
}