// /views/js/profile.js
/* ====================== Sección de publicar ========================== */
import { NewPost } from './new_post.js';
import { Comments } from './comment.js';

/* ================== Bootstrap de "nuevo post" ================== */
document.addEventListener('DOMContentLoaded', () => {
  const baseUrl = '/FootBook';
  document.querySelectorAll('[data-new-post]').forEach((node) => {
    new NewPost(node, { baseUrl });
  });
    new Comments({
    baseUrl,
    currentUserProvider: () => currentUser   // <- importante
  });
});

// Variable global para guardar datos del usuario (FUERA de la función async)
let currentUser = null;

(async () => {
  const BASE = (window.__BASE_URL__ || '/FootBook/').replace(/\/+$/, '') + '/';
  const url  = BASE + 'api/users/me';
  const FALLBACK_AVATAR = BASE + 'img/default-avatar.png';

  // ---- helpers ----
  const setText = (id, v) => {
    const el = document.getElementById(id);
    if (!el) { console.warn('[PROFILE] faltó id:', id); return; }
    ('value' in el) ? el.value = (v ?? '') : el.textContent = (v ?? '');
  };
  
  const setImgByIds = (src, ...ids) => {
    ids.forEach(id => {
      const el = document.getElementById(id);
      if (!el) return;
      el.addEventListener?.('error', () => { if (el.src !== FALLBACK_AVATAR) el.src = FALLBACK_AVATAR; });
      el.addEventListener?.('load',  () => console.log('[PROFILE] avatar loaded:', el.src));
      el.src = src;
    });
  };

  // Detecta MIME por prefijo de base64
  const guessMimeFromB64 = (b64 = '') => {
    if (b64.startsWith('/9j/'))   return 'image/jpeg';
    if (b64.startsWith('iVBOR'))  return 'image/png';
    if (b64.startsWith('UklGR'))  return 'image/webp';
    if (b64.startsWith('R0lGO'))  return 'image/gif';
    return 'image/jpeg';
  };
  
  const buildAvatarSrc = (u) => {
    if (u.avatar_url) return u.avatar_url;
    const b64 = u.avatar_b64 || u.avatarB64 || u.avatar;
    if (b64 && b64.length > 10) {
      const mime = guessMimeFromB64(b64);
      return `data:${mime};base64,${b64}`;
    }
    return FALLBACK_AVATAR;
  };

  const mapGenderToValue = (gender) => {
    if (gender === 1 || gender === 'F' || gender === 'Femenino') return 'F';
    if (gender === 2 || gender === 'M' || gender === 'Masculino') return 'M';
    return 'O';
  };

  const mapGenderToText = (gender) => {
    const map = {1:'Femenino', 2:'Masculino', 3:'Otro', 'F':'Femenino', 'M':'Masculino', 'O':'Otro'};
    return map[gender] || (gender ?? '');
  };

  // ---- fetch user data ----
  try {
    const res = await fetch(url, {
      credentials: 'include',
      cache: 'no-store',
      headers: { 'Accept': 'application/json' }
    });
    const raw = await res.text();
    if (!raw.trim()) { 
      alert(`La API respondió vacío (status ${res.status}).`); 
      return; 
    }

    let json;
    try {
      const i0 = raw.indexOf('{'), j0 = raw.lastIndexOf('}');
      json = JSON.parse(i0 >= 0 && j0 >= i0 ? raw.slice(i0, j0+1) : raw);
    } catch (e) {
      console.error('[PROFILE] JSON parse error', e, raw);
      alert('Respuesta no válida del API.');
      return;
    }

    if (!res.ok || (json && json.ok === false)) {
      alert((json && (json.error || json.message)) || `Error ${res.status}`);
      return;
    }

    // Guardar datos del usuario EN LA VARIABLE GLOBAL
    currentUser = (json && (json.data || json.user)) || json || {};
    
    // ---- render texto ----
    setText('profileName',   currentUser.fullname || '');
    setText('profileUser',   currentUser.username ? '@' + currentUser.username : '');
    setText('fullname',      currentUser.fullname);
    setText('username',      currentUser.username);
    setText('email',         currentUser.email);
    setText('birthday',      currentUser.birthday);
    setText('gender',        mapGenderToText(currentUser.gender));
    setText('birth_country', currentUser.birth_country);
    setText('country',       currentUser.country);

    // ---- render avatar ----
    const avatarSrc = buildAvatarSrc(currentUser);
    setImgByIds(avatarSrc, 'profileAvatar', 'avatarImg');

  } catch (err) {
    console.error('[PROFILE] Error loading user data:', err);
    alert('Error al cargar datos del usuario');
    return;
  }

  // ============================================
  // MODAL DE EDICIÓN - Event Listeners
  // ============================================

  const editModal = document.getElementById('editProfileModal');
  if (editModal) {
    // Cuando se abre el modal, rellenar con datos actuales
    editModal.addEventListener('show.bs.modal', () => {
      if (!currentUser) {
        console.error('[PROFILE] currentUser no está disponible');
        return;
      }

      setText('edit_fullname',     currentUser.fullname || '');
      setText('edit_username',     currentUser.username || '');
      setText('edit_email',        currentUser.email || '');
      setText('edit_birthdate',    currentUser.birthday || '');
      setText('edit_birthcountry', currentUser.birth_country || '');
      setText('edit_nationality',  currentUser.country || '');
      
      // Select de género
      const genderSelect = document.getElementById('edit_gender');
      if (genderSelect) {
        genderSelect.value = mapGenderToValue(currentUser.gender);
      }
      
      // Limpiar campo de password y foto
      setText('edit_password', '');
      const photoInput = document.getElementById('edit_photo');
      if (photoInput) photoInput.value = '';
    });
  }

  // ============================================
  // SUBMIT DEL FORMULARIO DE EDICIÓN
  // ============================================

  const editForm = editModal?.querySelector('form');
  if (editForm) {
    editForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const fullname = document.getElementById('edit_fullname')?.value?.trim() || '';
      const username = document.getElementById('edit_username')?.value?.trim() || '';
      const email = document.getElementById('edit_email')?.value?.trim() || '';
      const birthday = document.getElementById('edit_birthdate')?.value || '';
      const gender = document.getElementById('edit_gender')?.value || '';
      const birthcountry = document.getElementById('edit_birthcountry')?.value?.trim() || '';
      const nationality = document.getElementById('edit_nationality')?.value?.trim() || '';
      const password = document.getElementById('edit_password')?.value?.trim() || '';
      const photoInput = document.getElementById('edit_photo');

      // Validaciones básicas
      if (!fullname || !username || !email || !birthday || !gender || !birthcountry || !nationality) {
        alert('Por favor completa todos los campos requeridos.');
        return;
      }

      // Mapear género a número
      const genderMap = { 'F': 1, 'M': 2, 'O': 3 };
      const genderNum = genderMap[gender] || 3;

      // Construir payload
      const payload = {
        fullname,
        username,
        email,
        birthday,
        gender: genderNum,
        birth_country: birthcountry,
        country: nationality
      };

      // Solo incluir password si se escribió algo
      if (password) {
        // Validar password
        const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/;
        if (!pwdRegex.test(password)) {
          alert('La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, un número y un carácter especial.');
          return;
        }
        payload.password = password;
      }

      // Avatar (si se seleccionó uno nuevo)
      if (photoInput?.files?.length) {
        const file = photoInput.files[0];
        const avatarBase64 = await new Promise(resolve => {
          const reader = new FileReader();
          reader.onload = () => resolve(reader.result.split(',')[1] || null);
          reader.onerror = () => resolve(null);
          reader.readAsDataURL(file);
        });
        if (avatarBase64) payload.avatar = avatarBase64;
      }

      try {
        // Enviar actualización
        const updateRes = await fetch(BASE + 'api/users/update', {
          method: 'POST',
          credentials: 'include',
          headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        const updateRaw = await updateRes.text();
        let updateJson;
        try {
          const i0 = updateRaw.indexOf('{'), j0 = updateRaw.lastIndexOf('}');
          updateJson = JSON.parse(i0 >= 0 && j0 >= i0 ? updateRaw.slice(i0, j0+1) : updateRaw);
        } catch {
          alert('Error al procesar la respuesta del servidor');
          return;
        }

        if (!updateRes.ok || updateJson?.ok === false) {
          alert(updateJson?.error || 'Error al actualizar el perfil');
          return;
        }

        // Éxito - cerrar modal y recargar página
        alert('Perfil actualizado correctamente');
        const modal = bootstrap.Modal.getInstance(editModal);
        if (modal) modal.hide();
        window.location.reload();

      } catch (err) {
        console.error('[PROFILE] Error updating:', err);
        alert('Error al actualizar: ' + (err.message || err));
      }
    });
  }

  // ============================================
  // FEED DEL USUARIO filtrado por el usuario activo
  // ============================================

  const API_BASE      = '/FootBook';
  const FEED_ENDPOINT = '/api/feed';

  const FEED = {
    limit: 10,
    lastId: 0,
    loading: false,
    ended: false,
  };

  // DOM
  const $feedList    = document.getElementById('feed-list');
  const $feedLoading = document.getElementById('feed-loading');
  const $feedSection = document.getElementById('feed');

  // Helpers
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

  function renderPostCard(p) {
    const avatar = p.avatar_b64
      ? `data:image/*;base64,${p.avatar_b64}`
      : `${API_BASE}/img/default.jpg`;

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
        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#commentsModal" data-post-id="${p.id}" >
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M880-80 720-240H320q-33 0-56.5-23.5T240-320v-40h440q33 0 56.5-23.5T760-440v-280h40q33 0 56.5 23.5T880-640v560ZM160-473l47-47h393v-280H160v327ZM80-280v-520q0-33 23.5-56.5T160-880h440q33 0 56.5 23.5T680-800v280q0 33-23.5 56.5T600-440H240L80-280Zm80-240v-280 280Z"/></svg>
          ${p.comments_count ?? 0}
        </button>
        </div>
      </div>`;
    return el;
  }

  function resetFeedState() {
    FEED.lastId  = 0;
    FEED.ended   = false;
    FEED.loading = false;
    $feedList.innerHTML = '';
  }

  function buildFeedUrl() {
    // 👇 Solo filtra por usuario activo
    const q = new URLSearchParams();
    q.set('limit', FEED.limit);
    if (FEED.lastId) q.set('after', FEED.lastId);

    // importantísimo: mandar user_id (el back lo pasa al SP como p_user_id)
    q.set('user_id', currentUser.id);

    return `${API_BASE}${FEED_ENDPOINT}?${q.toString()}`;
  }

  let FEED_REQ_VERSION = 0;

  async function loadFeedPage({ reset = false } = {}) {
    if (FEED.loading || FEED.ended) return;
    if (!currentUser || !currentUser.id) return; // espera a tener el usuario

    const myVersion = ++FEED_REQ_VERSION;
    if (reset) resetFeedState();

    FEED.loading = true;
    $feedLoading.classList.remove('d-none');

    try {
      const url = buildFeedUrl();
      const { data } = await ajaxGET(url);

      if (myVersion !== FEED_REQ_VERSION) return;

      if (!Array.isArray(data) || data.length === 0) {
        FEED.ended = true;
        return;
      }

      const frag = document.createDocumentFragment();
      data.forEach(post => frag.appendChild(renderPostCard(post)));
      $feedList.appendChild(frag);

      FEED.lastId = data[data.length - 1].id;
      if (data.length < FEED.limit) FEED.ended = true;
    } catch (err) {
      console.error('[Feed] Error:', err);
    } finally {
      if (myVersion === FEED_REQ_VERSION) {
        FEED.loading = false;
        $feedLoading.classList.add('d-none');
      }
    }
  }

  // Inicia el feed **solo después** de tener currentUser.id
  function startProfileFeed() {
    if (!currentUser || !currentUser.id) return;
    resetFeedState();

    // sentinel para infinite scroll
    const sentinel = document.createElement('div');
    sentinel.id = 'feed-sentinel';
    $feedSection.appendChild(sentinel);

    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) loadFeedPage(); });
    }, { rootMargin: '400px 0px' });

    io.observe(sentinel);

    // primera página
    loadFeedPage({ reset: true });
  }

  // Llamar ahora que ya tenemos currentUser del bloque superior
  startProfileFeed();
})();
