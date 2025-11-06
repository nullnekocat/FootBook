// /views/js/wiki.js
const API_BASE = '/FootBook/api/worldcups';
const DEFAULT_BANNER = '/FootBook/img/russia2018.png';

const container = document.getElementById('worldcupContainer');

/* -------------------- utils -------------------- */
function guessMimeFromB64(b64) {
  if (!b64 || b64.length < 4) return 'image/jpeg';
  if (b64.startsWith('/9j/'))   return 'image/jpeg';  // JPG
  if (b64.startsWith('iVBOR'))  return 'image/png';   // PNG
  if (b64.startsWith('UklGR'))  return 'image/webp';  // WEBP
  if (b64.startsWith('R0lGO'))  return 'image/gif';   // GIF
  return 'image/jpeg';
}
function b64src(b64) {
  return `data:${guessMimeFromB64(b64)};base64,${b64}`;
}
function getBannerSrc(obj) {
  const b64 = obj.banner_b64 || obj.bannerB64 || obj.banner;
  if (b64 && typeof b64 === 'string' && b64.length > 10) return b64src(b64);

  // distintos flags que podría devolver tu API
  const exists = obj.banner_exists ?? obj.has_banner ?? obj.bannerExists;
  const id = obj.id ?? obj.worldcup_id ?? obj.id_worldcup;
  if (exists && id) return `${API_BASE}/${id}/banner?ts=${Date.now()}`;
  return DEFAULT_BANNER;
}

// Normaliza campos por si el API usa otros nombres
function normalizeCup(raw = {}) {
  return {
    id:   raw.id ?? raw.worldcup_id ?? raw.id_worldcup ?? raw.ID,
    name: raw.name ?? raw.title ?? raw.nom ?? '',
    country: raw.country ?? raw.host_country ?? raw.country_name ?? '',
    year:    raw.year ?? raw.season ?? raw.edition_year ?? '',
    description: raw.description ?? raw.desc ?? '',
    banner_b64: raw.banner_b64 ?? raw.bannerB64 ?? raw.banner ?? null,
    banner_exists: raw.banner_exists ?? raw.has_banner ?? raw.bannerExists ?? null,
  };
}

async function getFlexibleJSON(url, options) {
  const res = await fetch(url, options);
  const text = await res.text();
  let json;
  try { json = JSON.parse(text); }
  catch { throw new Error(`Respuesta no-JSON de ${url}:\n${text.slice(0,300)}`); }

  // Acepta {ok:true,data:...} o ... directo
  if (Array.isArray(json)) return json;
  if (json && json.ok === true && 'data' in json) return json.data;
  if (json && json.ok === false) throw new Error(json.error || 'API error');
  return json; // objeto plano (p.ej. detalle)
}

/* -------------------- render -------------------- */
function cardHtml(cup) {
  const imgSrc = getBannerSrc(cup);
  const name = cup.name ?? '';
  const country = cup.country ?? '';
  const year = cup.year ?? '';

  return `
    <div class="card shadow-sm h-100">
      <img class="card-img-top" alt="${name}"
           src="${imgSrc}"
           onerror="this.onerror=null;this.src='${DEFAULT_BANNER}'">
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

/* -------------------- data flows -------------------- */
async function loadWorldCups() {
  try {
    const raw = await getFlexibleJSON(API_BASE); // lista
    const cups = (Array.isArray(raw) ? raw : (raw?.items ?? []))
      .map(normalizeCup);

    if (!cups.length) {
      container.innerHTML = '<p class="text-muted">No se encontraron mundiales.</p>';
      return;
    }

    container.innerHTML = cups.map(c => `
      <div class="col-md-4 col-lg-3 mb-3">
        ${cardHtml(c)}
      </div>
    `).join('');

    // click -> abre modal
    document.querySelectorAll('[data-bs-target="#worldcupModal"]').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        loadModalData(id);
      });
    });

  } catch (err) {
    console.error('Error fetching World Cups:', err);
    container.innerHTML = `<p class="text-danger">${(err.message || 'Error cargando mundiales.')}</p>`;
  }
}

async function loadModalData(id) {
  try {
    const raw = await getFlexibleJSON(`${API_BASE}/${id}`); // detalle
    // si vino como {ok:true,data:{...}} getFlexibleJSON ya te devolvió data
    const d = normalizeCup(raw);

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
    // opcional: mostrar un mensaje en el modal
  }
}

/* -------------------- boot -------------------- */
document.addEventListener('DOMContentLoaded', loadWorldCups);
