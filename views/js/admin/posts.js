// views/js/admin/posts.js
const API_BASE = '/FootBook/api/posts';

/* -------------------- utils -------------------- */
function guessMimeFromB64(b64) { 
  if (!b64 || b64.length < 4) return 'image/jpeg';  // Agrega el video
  if (b64.startsWith('/9j/'))   return 'image/jpeg';  // JPG
  if (b64.startsWith('iVBOR'))  return 'image/png';   // PNG
  if (b64.startsWith('UklGR'))  return 'image/webp';  // WEBP
  if (b64.startsWith('R0lGO'))  return 'image/gif';   // GIF
  return 'image/jpeg';
}
function b64src(b64) {
  return `data:${guessMimeFromB64(b64)};base64,${b64}`;
}
async function flexiGetJSON(url) {
  const res = await fetch(url, { credentials: 'include', headers: { 'Accept':'application/json' }});
  const txt = await res.text();
  let json;
  try {
    const i = txt.indexOf('{'), j = txt.lastIndexOf('}');
    json = JSON.parse(i >= 0 && j >= i ? txt.slice(i, j + 1) : txt);
  } catch (e) {
    throw new Error('Respuesta no-JSON del API');
  }
  if (!res.ok || json?.ok === false) {
    const msg = json?.error || (`HTTP ${res.status}`);
    throw new Error(msg);
  }
  return json;
}
function fmtDate(dstr) {
  try {
    const d = new Date(dstr);
    return d.toLocaleDateString('es-MX', { year:'numeric', month:'2-digit', day:'2-digit' });
  } catch { return dstr || ''; }
}

/* -------------------- render -------------------- */
function renderPosts(list, rows) {
  list.innerHTML = '';
  if (!rows || rows.length === 0) {
    list.innerHTML = `<div class="list-group-item text-muted">No hay publicaciones pendientes.</div>`;
    return;
  }

  const frag = document.createDocumentFragment();

  rows.forEach(r => {
    const {
      id,
      username,
      category_name,
      worldcup_name,
      title,
      description,
      media_b64,
      created_at
    } = r;

    const item = document.createElement('div');
    item.className = 'list-group-item';

    const imgHtml = media_b64
      ? `<img src="${b64src(media_b64)}" class="img-fluid rounded mb-2" style="max-width:220px;" alt="media">`
      : '';

    item.innerHTML = `
      <div class="d-flex w-100 justify-content-between align-items-center">
        <div>
          <h6 class="mb-1">
            ${username ?? 'Usuario'} 
            <span class="badge bg-info">${category_name ?? ''}</span> 
            <span class="badge bg-secondary">${worldcup_name ?? ''}</span>
          </h6>
          <small>${fmtDate(created_at)}</small>
          <p class="mb-1 fw-semibold">${title ?? ''}</p>
          <p class="mb-1">${description ?? ''}</p>
          ${imgHtml}
        </div>
        <div class="ms-3">
          <button class="btn btn-success btn-sm mb-2 w-100" data-approve="${id}">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>
          </button>
          <button class="btn btn-danger btn-sm w-100" data-reject="${id}">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
          </button>
        </div>
      </div>
    `;

    frag.appendChild(item);
  });

  list.appendChild(frag);
}

async function loadPostsToAproved() {
  const list = document.getElementById('admin-posts-list');
  if (!list) return;

  try {
    const payload = await flexiGetJSON(API_BASE+'/to_approve');
    renderPosts(list, payload.data || []);
  } catch (err) {
    console.error('[Admin] Error al cargar posts:', err);
    list.innerHTML = `<div class="list-group-item text-danger">${String(err.message || err)}</div>`;
  }
}

/* =========================
   AJAX helper (XMLHttpRequest)
   ========================= */
function ajax(url, { method = 'GET', data = null, headers = {} } = {}) {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.withCredentials = true; // enviar cookies/sesión

    // headers
    xhr.setRequestHeader('Accept', 'application/json');
    if (data !== null) {
      xhr.setRequestHeader('Content-Type', 'application/json');
    }

    xhr.onreadystatechange = () => {
      if (xhr.readyState !== 4) return;
      const status = xhr.status;
      const txt = xhr.responseText || '';

      // intenta recortar y parsear JSON
      let json = null;
      try {
        const i = txt.indexOf('{'), j = txt.lastIndexOf('}');
        json = JSON.parse(i >= 0 && j >= i ? txt.slice(i, j + 1) : txt);
      } catch (e) {
        return reject(new Error('Respuesta no-JSON del API'));
      }

      if (status >= 200 && status < 300 && json?.ok !== false) {
        resolve(json);
      } else {
        reject(new Error(json?.error || ('HTTP ' + status)));
      }
    };

    xhr.onerror = () => reject(new Error('Error de red'));
    xhr.send(data !== null ? JSON.stringify(data) : null);
  });
}

/* =========================
   Aprobar / Desaprobar
   ========================= */
async function approvePost(postId, isApproved) {
  // Endpoint tipo: /FootBook/api/posts/:id/approve  Body: { is_approved: 1|0 }
  const url = `/FootBook/api/posts/${postId}/approve`;
  const body = { is_approved: isApproved ? 1 : 0 };

  // AJAX POST
  const res = await ajax(url, { method: 'POST', data: body });
  // res.data = { id, approved, approved_at }

  return res?.data || {};
}

/* =========================
   Delegación de eventos
   ========================= */
function wireApproveButtons() {
  const list = document.getElementById('admin-posts-list');
  if (!list) return;

  list.addEventListener('click', async (ev) => {
    const btnApprove = ev.target.closest('[data-approve]');
    const btnReject  = ev.target.closest('[data-reject]');

    if (!btnApprove && !btnReject) return;

    try {
      const li = ev.target.closest('.list-group-item');
      const postId = parseInt((btnApprove?.dataset.approve || btnReject?.dataset.reject), 10);

      if (!postId) throw new Error('post_id inválido');

      // UI: deshabilitar mientras procesa
      (btnApprove || btnReject).disabled = true;

      // 1 para aprobar, 0 para desaprobar
      await approvePost(postId, !!btnApprove);

      // Quita el item aprobado/rechazado de la lista
      if (li && li.parentNode) {
        li.parentNode.removeChild(li);
      }

      // Si la lista quedó vacía, recargar para mostrar mensaje vacío
      if (!list.querySelector('.list-group-item')) {
        await loadPostsToAproved();
      }
    } catch (err) {
      console.error('[Admin] approve/reject error:', err);
      alert(err.message || 'No se pudo actualizar el estado');
    } finally {
      if (btnApprove) btnApprove.disabled = false;
      if (btnReject)  btnReject.disabled = false;
    }
  });
}

/* --------- boot ---------- */
export async function bootPosts() {
  console.log('[Admin] Boot Posts');
  await loadPostsToAproved();
    wireApproveButtons();
}
