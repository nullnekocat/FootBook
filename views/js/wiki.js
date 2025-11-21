// /views/js/wiki.js
const API_BASE = '/FootBook/API/worldcups.php';
const DEFAULT_BANNER = '/FootBook/img/russia2018.png';

const container = document.getElementById('worldcupContainer');

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
  const b64 = obj.banner_b64;
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

    // Soporte para formato {ok: true, data: ...}
    if (json.ok === true && 'data' in json) {
      return json.data;
    }
    
    if (json.ok === false) {
      throw new Error(json.error || 'API returned ok: false');
    }

    return json;
    
  } catch (error) {
    console.error('API Request failed:', error);
    throw error;
  }
}

/* -------------------- Render -------------------- */
function cardHtml(cup) {
  const imgSrc = getBannerSrc(cup);
  const name = cup.name ?? '';
  const country = cup.country ?? '';
  const year = cup.year ?? '';

  return `
    <div class="card shadow-sm h-100">
      <img class="card-img-top" 
           alt="${name}"
           src="${imgSrc}"
           onerror="this.onerror=null;this.src='${DEFAULT_BANNER}'"
           style="height: 200px; object-fit: cover;">
      <div class="card-body text-center">
        <h6 class="card-title mb-1">${name}</h6>
        <p class="text-muted small mb-2">${country} • ${year}</p>
        <button class="btn btn-outline-primary btn-sm"
                data-id="${cup.id}"
                data-bs-toggle="modal"
                data-bs-target="#worldcupModal">
          Ver detalles
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
      container.innerHTML = '<p class="col-12 text-center text-muted">No se encontraron mundiales.</p>';
      return;
    }

    container.innerHTML = cups.map(c => `
      <div class="col-md-6 col-lg-4 mb-3">
        ${cardHtml(c)}
      </div>
    `).join('');

    // Asignar eventos a botones
    document.querySelectorAll('[data-bs-target="#worldcupModal"]').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        loadModalData(id);
      });
    });

  } catch (err) {
    console.error('Error fetching World Cups:', err);
    container.innerHTML = `
      <div class="col-12">
        <div class="alert alert-danger" role="alert">
          <i class="bi bi-exclamation-triangle"></i> 
          ${err.message || 'Error cargando mundiales'}
        </div>
      </div>
    `;
  }
}

async function loadModalData(id) {
  try {
    const data = await apiRequest(`/${id}`);
    const d = normalizeCup(data);
    const imgSrc = getBannerSrc(d);

    // Actualizar todos los elementos del modal
    const modalTitle = document.getElementById('worldcupModalLabel');
    const modalCountry = document.getElementById('modalCountry');
    const modalYear = document.getElementById('modalYear');
    const modalDescription = document.getElementById('modalDescription');
    const modalBanner = document.getElementById('modalBanner');

    if (modalTitle) modalTitle.textContent = d.name || 'Mundial';
    if (modalCountry) modalCountry.textContent = d.country || 'N/A';
    if (modalYear) modalYear.textContent = d.year || 'N/A';
    if (modalDescription) modalDescription.textContent = d.description || 'No hay descripción disponible.';
    
    if (modalBanner) {
      modalBanner.src = imgSrc;
      modalBanner.onerror = () => { modalBanner.src = DEFAULT_BANNER; };
    }

  } catch (err) {
    console.error('Error loading modal data:', err);
    alert(`Error cargando información: ${err.message}`);
  }
}

/* -------------------- Boot -------------------- */
document.addEventListener('DOMContentLoaded', loadWorldCups);