(async () => {
  const BASE = (window.__BASE_URL__ || '/FootBook/').replace(/\/+$/, '') + '/';
  const url  = BASE + 'api/users/me?t=' + Date.now();

  console.log('[PROFILE] BASE:', BASE, 'URL:', url);

  const res = await fetch(url, {
    credentials: 'include',
    cache: 'no-store',
    headers: { 'Accept': 'application/json' }
  });

  const raw = await res.text();
  console.log('[ME] status:', res.status, 'ct:', res.headers.get('content-type'), 'len:', raw.length, 'preview:', raw.slice(0,200));

  if (!raw.trim()) { alert('La API respondió vacío (status ' + res.status + ').'); return; }
  if (!res.ok) { try { alert(JSON.parse(raw).error); } catch { alert('Error ' + res.status); } return; }

  // parse robusto (por si hay whitespace)
  let data;
  try {
    const i0 = raw.indexOf('{'), j0 = raw.lastIndexOf('}');
    data = JSON.parse(i0 >= 0 && j0 >= i0 ? raw.slice(i0, j0+1) : raw);
  } catch (e) {
    console.error('[ME] parse error', e, raw);
    alert('Respuesta no válida');
    return;
  }

  const u = data.user || {};

  // helpers
  const setText = (id, v) => {
    const el = document.getElementById(id);
    if (!el) { console.warn('[PROFILE] faltó id:', id); return; }
    ('value' in el) ? el.value = (v ?? '') : el.textContent = (v ?? '');
  };
  const setImg = (id, src) => {
    const el = document.getElementById(id);
    if (!el) return;
    el.src = src;
  };

  // texto
  setText('profileName',  u.fullname || '');
  setText('profileUser',  '@' + (u.username || ''));
  setText('fullname',     u.fullname);
  setText('username',     u.username);
  setText('email',        u.email);
  setText('birthday',     u.birthday);
  setText('gender',       ({1:'Femenino',2:'Masculino',3:'Otro'})[u.gender] || u.gender);
  setText('birth_country',u.birth_country);
  setText('country',      u.country);

// avatar
const fallback = BASE + 'img/default-avatar.png'; // ajusta si tu placeholder está en otra ruta

// util para setear el src probando dos IDs posibles
const setAvatarSrc = (src) => {
  const el = document.getElementById('profileAvatar');
  if (el) el.src = src;
};

if (u.avatar_url) {
  setAvatarSrc(u.avatar_url);  
} else {
  setAvatarSrc(fallback);
}

const el = document.getElementById('avatarImg') || document.getElementById('profileAvatar');

if (el) {
  el.addEventListener('error', () => console.warn('[PROFILE] avatar failed:', el.src));
  el.addEventListener('load',  () => console.log('[PROFILE] avatar loaded:', el.src));
  el.src = u.avatar_url || fallback;
}



})();
