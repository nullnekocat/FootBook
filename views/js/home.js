// views/js/home.js
import { NewPost } from './new_post.js';
import { Comments } from './comment.js';

/* ================== Bootstrap de "nuevo post" ================== */
document.addEventListener('DOMContentLoaded', () => {
  const baseUrl = '/FootBook';
  document.querySelectorAll('[data-new-post]').forEach((node) => {
    new NewPost(node, { baseUrl });
  });
  new Comments({ baseUrl });
});

/* ================== FEED (AJAX + infinite scroll + BÚSQUEDA) ================== */
const API_BASE            = '/FootBook';
const FEED_ENDPOINT       = '/api/feed';
const WORLDCUPS_LIGHT_API = '/FootBook/api/worldcups/light';

const FEED = {
  limit: 10,
  lastId: 0,
  loading: false,
  ended: false,
  filters: {
    worldcupId: '',
    orderBy: 'cronologico',
    searchText: ''  // 👈 NUEVO: texto de búsqueda
  }
};

// DOM
const $feedList    = document.getElementById('feed-list');
const $feedLoading = document.getElementById('feed-loading');
const $btnMore     = document.getElementById('feed-load-more');

/* ------------------ AJAX helper (GET) ------------------ */
function ajaxGET(url) {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.withCredentials = true;
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.onreadystatechange = () => {
      if (xhr.readyState !== 4) return;
      const raw = xhr.responseText || '';
      try {
        const i = raw.indexOf('{'), j = raw.lastIndexOf('}');
        const json = JSON.parse(i >= 0 && j >= i ? raw.slice(i, j + 1) : raw);
        if (json && json.ok) resolve(json);
        else reject(new Error(json?.error || 'Error API'));
      } catch {
        reject(new Error('Respuesta no-JSON del API'));
      }
    };

    xhr.onerror = () => reject(new Error('Error de red'));
    xhr.send();
  });
}

/* ------------------ Render de tarjeta ------------------ */
function renderPostCard(p) {
  const avatar = p.avatar_b64
    ? `data:image/*;base64,${p.avatar_b64}`
    : `${API_BASE}/img/default.jpg`;

  const wc  = [p.worldcup_name, p.worldcup_year].filter(Boolean).join(' ');
  const cat = p.category_name || '';

  const mediaHTML = p.media_b64
    ? `<img class="img-fluid rounded mb-2" src="data:image/*;base64,${p.media_b64}" alt="Post image">`
    : '';

  // Clases dinámicas para el botón de like
  const likeClass = p.liked_by_me ? 'btn-success' : 'btn-outline-success';
  const likeIcon = p.liked_by_me 
    ? '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M720-120H280v-520l280-280 50 50q7 7 11.5 19t4.5 23v14l-44 174h258q32 0 56 24t24 56v80q0 7-2 15t-4 15L794-168q-9 20-30 34t-44 14Zm-360-80h360l120-280v-80H480l54-220-174 174v406Zm0-406v406-406Zm-80-34v80H160v360h120v80H80v-520h200Z"/></svg>'
    : '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M720-120H280v-520l280-280 50 50q7 7 11.5 19t4.5 23v14l-44 174h258q32 0 56 24t24 56v80q0 7-2 15t-4 15L794-168q-9 20-30 34t-44 14Zm-360-80h360l120-280v-80H480l54-220-174 174v406Zm0-406v406-406Zm-80-34v80H160v360h120v80H80v-520h200Z"/></svg>';

  const el = document.createElement('div');
  el.className = 'card mb-3 shadow-sm';
  el.dataset.postId = p.id;
  el.innerHTML = `
    <div class="card-body">
      <div class="d-flex mb-2 align-items-center">
        <img src="${avatar}" class="rounded-circle me-2" width="40" height="40" alt="User">
        <div>
          <strong>${p.username ?? 'Usuario'}</strong>
          <span class="text-muted small">en ${wc}</span>
          <span class="badge bg-secondary ms-2">${cat}</span>
        </div>
      </div>
      ${p.title ? `<h6 class="mb-1">${p.title}</h6>` : ''}
      <p class="mb-2">${p.description ?? ''}</p>
      ${mediaHTML}
      <div>
        <button class="btn btn-sm ${likeClass} me-2" data-action="like" data-post-id="${p.id}">
          ${likeIcon}
          <span class="like-count">${p.likes_count ?? 0}</span>
        </button>
        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#commentsModal" data-post-id="${p.id}">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M880-80 720-240H320q-33 0-56.5-23.5T240-320v-40h440q33 0 56.5-23.5T760-440v-280h40q33 0 56.5 23.5T880-640v560ZM160-473l47-47h393v-280H160v327ZM80-280v-520q0-33 23.5-56.5T160-880h440q33 0 56.5 23.5T680-800v280q0 33-23.5 56.5T600-440H240L80-280Zm80-240v-280 280Z"/></svg>
          <span class="comment-count">${p.comments_count ?? 0}</span>
        </button>
      </div>
    </div>`;
  
  return el;
}

/* ------------------ Cargar página del feed ------------------ */
let FEED_REQ_VERSION = 0;

async function loadFeedPage({ reset = false } = {}) {
  console.log('[Feed] loadFeedPage llamado. Loading:', FEED.loading, 'Ended:', FEED.ended, 'Reset:', reset);
  
  if (FEED.loading || FEED.ended) {
    console.log('[Feed] loadFeedPage cancelado (loading o ended)');
    return;
  }

  const myVersion = ++FEED_REQ_VERSION;
  console.log('[Feed] Versión de request:', myVersion);

  if (reset) {
    console.log('[Feed] Reseteando estado del feed');
    resetFeedState();
  }

  FEED.loading = true;
  $feedLoading?.classList.remove('d-none');

  const url = buildFeedUrl();
  console.log('[Feed] URL de búsqueda:', url);

  try {
    const { data } = await ajaxGET(url);
    console.log('[Feed] Datos recibidos:', data?.length || 0, 'posts');

    if (myVersion !== FEED_REQ_VERSION) {
      console.log('[Feed] Versión desactualizada, ignorando');
      return;
    }

    if (!Array.isArray(data) || data.length === 0) {
      FEED.ended = true;
      
      // Mostrar mensaje si no hay resultados
      if (reset && data.length === 0 && FEED.filters.searchText) {
        console.log('[Feed] No hay resultados para:', FEED.filters.searchText);
        $feedList.innerHTML = `
          <div class="alert alert-info text-center">
            No se encontraron publicaciones para "<strong>${escapeHtml(FEED.filters.searchText)}</strong>"
          </div>`;
      }
      return;
    }

    console.log('[Feed] Renderizando', data.length, 'posts');
    data.forEach(post => $feedList.appendChild(renderPostCard(post)));
    FEED.lastId = data[data.length - 1].id;
    if (data.length < FEED.limit) FEED.ended = true;
  } catch (err) {
    console.error('[Feed] Error:', err);
  } finally {
    if (myVersion === FEED_REQ_VERSION) {
      FEED.loading = false;
      $feedLoading?.classList.add('d-none');
      console.log('[Feed] loadFeedPage completado');
    }
  }
}

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

/* ------------------ Scroll infinito (sentinela) ------------------ */
const $feedSection = document.getElementById('feed');
const sentinel = document.createElement('div');
sentinel.id = 'feed-sentinel';
$feedSection.appendChild(sentinel);

const io = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) loadFeedPage();
  });
}, { rootMargin: '400px 0px' });

io.observe(sentinel);

$btnMore?.addEventListener('click', () => loadFeedPage());

/* ================== Filtros: rellenado + submit ================== */
async function fillWorldcupFilter() {
  try {
    const res = await ajaxGET(WORLDCUPS_LIGHT_API);
    if (!res?.ok || !Array.isArray(res.data)) return;

    const sel = document.getElementById('filter-worldcup');
    sel.querySelectorAll('option:not([value=""])').forEach(o => o.remove());

    res.data.forEach(cup => {
      const opt = document.createElement('option');
      opt.value = cup.id;
      opt.textContent = `${cup.name}`;
      sel.appendChild(opt);
    });
  } catch (err) {
    console.warn('[Feed] No se pudo cargar worldcups:', err.message);
  }
}

function initFeedFilters() {
  fillWorldcupFilter();

  const form        = document.getElementById('feed-filters-form');
  const selWorldcup = document.getElementById('filter-worldcup');
  const selOrder    = document.getElementById('filter-order');

  form.addEventListener('submit', async (ev) => {
    ev.preventDefault();

    FEED.filters.worldcupId = selWorldcup.value ? parseInt(selWorldcup.value, 10) : '';
    FEED.filters.orderBy    = selOrder.value || 'cronologico';

    FEED_REQ_VERSION++;
    await loadFeedPage({ reset: true });
  });
}

/* ================== BÚSQUEDA desde navbar ================== */
function initSearch() {
  console.log('[Feed] Inicializando búsqueda');
  
  // Escuchar evento personalizado del navbar
  document.addEventListener('feedSearch', async (e) => {
    console.log('[Feed] Evento feedSearch recibido:', e.detail);
    const searchText = e.detail.searchText || '';
    FEED.filters.searchText = searchText;
    
    // Actualizar input del navbar con el texto buscado
    const navInput = document.getElementById('main-search-input');
    if (navInput) navInput.value = searchText;
    
    console.log('[Feed] Actualizando feed con búsqueda:', searchText);
    FEED_REQ_VERSION++;
    resetFeedState();
    
    try {
      await loadFeedPage({ reset: true });
      console.log('[Feed] Feed actualizado correctamente');
    } catch (err) {
      console.error('[Feed] Error al actualizar feed:', err);
    }
  });

  // Leer parámetro ?q= de la URL si existe
  const urlParams = new URLSearchParams(window.location.search);
  const qParam = urlParams.get('q');
  if (qParam) {
    console.log('[Feed] Parámetro q encontrado:', qParam);
    FEED.filters.searchText = qParam.trim();
    
    // Actualizar input del navbar
    const navInput = document.getElementById('main-search-input');
    if (navInput) navInput.value = FEED.filters.searchText;
  }
  
  console.log('[Feed] Búsqueda inicializada. Filtros actuales:', FEED.filters);
}

/* ============ Resetear el filtro ===================== */
function resetFeedState() {
  console.log('[Feed] Reseteando estado del feed');
  FEED.lastId = 0;
  FEED.ended  = false;
  FEED.loading = false;
  $feedList.innerHTML = '';
  console.log('[Feed] Estado reseteado:', FEED);
}

function buildFeedUrl() {
  const q = new URLSearchParams();
  q.set('limit', FEED.limit);
  if (FEED.lastId) q.set('after', FEED.lastId);

  // Filtros
  if (FEED.filters.worldcupId !== '') q.set('worldcup_id', FEED.filters.worldcupId);
  if (FEED.filters.orderBy)           q.set('order', FEED.filters.orderBy);
  if (FEED.filters.searchText !== '') q.set('q', FEED.filters.searchText); // 👈 NUEVO

  return `${API_BASE}${FEED_ENDPOINT}?${q.toString()}`;
}

/* ================== Primera carga ================== */
document.addEventListener('DOMContentLoaded', () => {
  initFeedFilters();
  initSearch(); // 👈 NUEVO: inicializar búsqueda
  loadFeedPage({ reset: true });
});

/* ================== LIKES ================== */
document.addEventListener('click', async (e) => {
  const likeBtn = e.target.closest('[data-action="like"]');
  if (!likeBtn) return;

  e.preventDefault();
  
  const postId = likeBtn.dataset.postId;
  if (!postId) return;

  // UI: deshabilitar temporalmente
  const wasDisabled = likeBtn.disabled;
  likeBtn.disabled = true;

  try {
    const url = `${API_BASE}/api/posts/${postId}/like`;
    const res = await fetch(url, {
      method: 'POST',
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    });

    const raw = await res.text();
    let json;
    try {
      const i = raw.indexOf('{'), j = raw.lastIndexOf('}');
      json = JSON.parse(i >= 0 && j >= i ? raw.slice(i, j + 1) : raw);
    } catch {
      throw new Error('Respuesta no-JSON del API');
    }

    if (!res.ok || !json?.ok) {
      throw new Error(json?.error || 'Error al procesar like');
    }

    // Actualizar UI
    const card = likeBtn.closest('[data-post-id]');
    const likeCount = likeBtn.querySelector('.like-count');
    
    if (json.liked) {
      likeBtn.classList.remove('btn-outline-success');
      likeBtn.classList.add('btn-success');
      likeBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M720-120H280v-520l280-280 50 50q7 7 11.5 19t4.5 23v14l-44 174h258q32 0 56 24t24 56v80q0 7-2 15t-4 15L794-168q-9 20-30 34t-44 14Zm-360-80h360l120-280v-80H480l54-220-174 174v406Zm0-406v406-406Zm-80-34v80H160v360h120v80H80v-520h200Z"/></svg>
        <span class="like-count">${json.total_likes}</span>
      `;
    } else {
      likeBtn.classList.remove('btn-success');
      likeBtn.classList.add('btn-outline-success');
      likeBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M720-120H280v-520l280-280 50 50q7 7 11.5 19t4.5 23v14l-44 174h258q32 0 56 24t24 56v80q0 7-2 15t-4 15L794-168q-9 20-30 34t-44 14Zm-360-80h360l120-280v-80H480l54-220-174 174v406Zm0-406v406-406Zm-80-34v80H160v360h120v80H80v-520h200Z"/></svg>
        <span class="like-count">${json.total_likes}</span>
      `;
    }

  } catch (err) {
    console.error('[Like] Error:', err);
    alert(err.message || 'Error al dar like');
  } finally {
    if (!wasDisabled) likeBtn.disabled = false;
  }
});