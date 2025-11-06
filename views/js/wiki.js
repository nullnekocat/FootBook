// /views/js/wiki.js
const API_BASE = '/FootBook/api/worldcups';
// Asegúrate de que este placeholder exista en /FootBook/img/
const DEFAULT_BANNER = '/FootBook/img/russia2018.png';

const container = document.getElementById('worldcupContainer');

/* -------------------- utils -------------------- */
function guessMimeFromB64(b64) {
  if (!b64 || b64.length < 4) return 'image/jpeg';
  if (b64.startsWith('/9j/')) return 'image/jpeg';      // JPG
  if (b64.startsWith('iVBOR')) return 'image/png';      // PNG
  if (b64.startsWith('UklGR')) return 'image/webp';     // WEBP (RIFF)
  if (b64.startsWith('R0lGO')) return 'image/gif';      // GIF
  return 'image/jpeg';
}

function makeImgSrcFromB64(b64) {
  const mime = guessMimeFromB64(b64);
  return `data:${mime};base64,${b64}`;
}

// Obtiene el src de imagen a partir del objeto de la API.
// Prioriza base64; si no viene y el API expone banner_exists, usa el endpoint binario;
// si nada, usa placeholder.
function getBannerSrc(obj) {
  const b64 = obj.banner_b64 || obj.bannerB64 || obj.banner;
  if (b64 && typeof b64 === 'string' && b64.length > 10) {
    return makeImgSrcFromB64(b64);
  }
  if (obj.banner_exists && obj.id) {
    return `${API_BASE}/${obj.id}/banner?ts=${Date.now()}`;
  }
  return DEFAULT_BANNER;
}

/* -------------------- render -------------------- */
function cardHtml(cup) {
  const imgSrc = getBannerSrc(cup);

  return `
    <div class="card shadow-sm h-100">
      <img class="card-img-top" alt="${cup.name}"
           src="${imgSrc}"
           onerror="this.onerror=null;this.src='${DEFAULT_BANNER}'">
      <div class="card-body text-center">
        <h6 class="card-title mb-1">${cup.name}</h6>
        <p class="text-muted small mb-2">${cup.country} • ${cup.year}</p>
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

/* -------------------- data flows -------------------- */
async function loadWorldCups() {
  try {
    const res = await fetch(API_BASE);              // Debe devolver { ok:true, data:[...] }
    const payload = await res.json();

    if (!payload || payload.ok !== true || !Array.isArray(payload.data)) {
      console.error('Unexpected API response:', payload);
      container.innerHTML = '<p class="text-muted">No se encontraron mundiales.</p>';
      return;
    }

    const cups = payload.data;
    container.innerHTML = cups.map(c => `
      <div class="col-md-4 col-lg-3 mb-3">
        ${cardHtml(c)}
      </div>
    `).join('');

    // Wire del modal
    document.querySelectorAll('[data-bs-target="#worldcupModal"]').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        loadModalData(id);
      });
    });

  } catch (err) {
    console.error('Error fetching World Cups:', err);
    container.innerHTML = '<p class="text-danger">Error cargando mundiales.</p>';
  }
}

async function loadModalData(id) {
  try {
    const res = await fetch(`${API_BASE}/${id}`);   // { ok:true, data:{...} con banner_b64 opcional
    const payload = await res.json();

    if (!payload || payload.ok !== true || !payload.data) {
      console.error('Unexpected detail response:', payload);
      return;
    }

    const d = payload.data;

    document.getElementById('worldcupModalLabel').textContent = d.name || '';
    document.getElementById('modalCountry').textContent = d.country || '';
    document.getElementById('modalYear').textContent = d.year || '';
    document.getElementById('modalDescription').textContent =
      d.description || 'No description available.';

    const img = document.getElementById('modalBanner');
    img.src = getBannerSrc(d);
    img.onerror = () => { img.src = DEFAULT_BANNER; };

  } catch (err) {
    console.error('Error loading modal data:', err);
  }
}

/* -------------------- boot -------------------- */
document.addEventListener('DOMContentLoaded', loadWorldCups);
