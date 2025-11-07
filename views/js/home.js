import { NewPost } from './new_post.js';

document.addEventListener('DOMContentLoaded', () => {
  const baseUrl = '/FootBook';
  document.querySelectorAll('[data-new-post]').forEach((node) => {
    new NewPost(node, { baseUrl });
  });
});
// === FEED DINÁMICO (infinite scroll con AJAX nativo) ===

const FEED = {
  baseUrl: '/FootBook',          // raíz de tu proyecto
  endpoint: '/api/feed',         // endpoint del feed
  limit: 10,                     // tamaño de página
  lastId: 0,                     // cursor para keyset pagination (id del último post mostrado)
  loading: false,
  ended: false
};

const $feedList    = document.getElementById('feed-list');
const $feedLoading = document.getElementById('feed-loading');
const $btnMore     = document.getElementById('feed-load-more');

// Helper AJAX nativo (mismo estilo que ya usas en admin)
function ajaxGET(url) {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.withCredentials = true;
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.onreadystatechange = () => {
      if (xhr.readyState !== 4) return;
      const txt = xhr.responseText || '';
      try {
        // tolerante a HTML envolviendo JSON
        const i = txt.indexOf('{'), j = txt.lastIndexOf('}');
        const json = JSON.parse(i >= 0 && j >= i ? txt.slice(i, j + 1) : txt);
        if (json && json.ok) resolve(json);
        else reject(new Error(json?.error || 'Error API'));
      } catch (e) {
        reject(new Error('Respuesta no-JSON del API'));
      }
    };

    xhr.onerror = () => reject(new Error('Error de red'));
    xhr.send();
  });
}

// Render de una card (ajusta a tu look & feel)
function renderPostCard(p) {
  const avatar = p.avatar_b64
    ? `data:image/*;base64,${p.avatar_b64}`
    : '/FootBook/img/default.jpg';

  const wc  = [p.worldcup_name, p.worldcup_year].filter(Boolean).join(' ');
  const cat = p.category_name || '';

  const mediaHTML = p.media_b64
    ? `<img class="img-fluid rounded mb-2" src="data:image/*;base64,${p.media_b64}" alt="Post image">`
    : '';

  const el = document.createElement('div');
  el.className = 'card mb-3 shadow-sm';
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
        <button class="btn btn-sm btn-outline-success me-2">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M720-120H280v-520l280-280 50 50q7 7 11.5 19t4.5 23v14l-44 174h258q32 0 56 24t24 56v80q0 7-2 15t-4 15L794-168q-9 20-30 34t-44 14Zm-360-80h360l120-280v-80H480l54-220-174 174v406Zm0-406v406-406Zm-80-34v80H160v360h120v80H80v-520h200Z"/></svg>
          ${p.likes_count ?? 0}
        </button>
        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#commentsModal">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M880-80 720-240H320q-33 0-56.5-23.5T240-320v-40h440q33 0 56.5-23.5T760-440v-280h40q33 0 56.5 23.5T880-640v560ZM160-473l47-47h393v-280H160v327ZM80-280v-520q0-33 23.5-56.5T160-880h440q33 0 56.5 23.5T680-800v280q0 33-23.5 56.5T600-440H240L80-280Zm80-240v-280 280Z"/></svg>
          ${p.comments_count ?? 0}
        </button>
      </div>
    </div>`;
  return el;
}

async function loadFeedPage({ reset = false } = {}) {
  if (FEED.loading || FEED.ended) return;
  FEED.loading = true;
  $feedLoading.classList.remove('d-none');

  if (reset) {
    FEED.lastId = 0;
    FEED.ended  = false;
    $feedList.innerHTML = '';
  }

  const qs = new URLSearchParams();
  qs.set('limit', FEED.limit);
  if (FEED.lastId) qs.set('after', FEED.lastId);

  const url = `${FEED.baseUrl}${FEED.endpoint}?${qs.toString()}`;

  try {
    const { data } = await ajaxGET(url);
    if (!Array.isArray(data) || data.length === 0) {
      FEED.ended = true;
      return;
    }
    // render
    for (const post of data) {
      $feedList.appendChild(renderPostCard(post));
    }
    // cursor = id del último
    FEED.lastId = data[data.length - 1].id;
  } catch (err) {
    console.error('[Feed] Error:', err);
  } finally {
    FEED.loading = false;
    $feedLoading.classList.add('d-none');
  }
}

// Scroll infinito (IntersectionObserver)
const sentinel = document.createElement('div');
sentinel.id = 'feed-sentinel';
document.getElementById('feed').appendChild(sentinel);

const io = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) loadFeedPage();
  });
}, { rootMargin: '400px 0px' });

io.observe(sentinel);

// Botón "Cargar más" (por si lo quieres visible)
$btnMore?.addEventListener('click', () => loadFeedPage());

// Primera carga
document.addEventListener('DOMContentLoaded', () => {
  loadFeedPage({ reset: true });
});
