// /views/js/profile.js
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
    if (b64.startsWith('/9j/'))   return 'image/jpeg'; // JPG
    if (b64.startsWith('iVBOR'))  return 'image/png';  // PNG
    if (b64.startsWith('UklGR'))  return 'image/webp'; // WEBP
    if (b64.startsWith('R0lGO'))  return 'image/gif';  // GIF
    return 'image/jpeg';
  };
  const buildAvatarSrc = (u) => {
    // Prioriza URL directa si existe
    if (u.avatar_url) return u.avatar_url;
    // Si viene base64 desde el SP
    const b64 = u.avatar_b64 || u.avatarB64 || u.avatar;
    if (b64 && b64.length > 10) {
      const mime = guessMimeFromB64(b64);
      return `data:${mime};base64,${b64}`;
    }
    return FALLBACK_AVATAR;
  };

  // ---- fetch ----
  const res = await fetch(url, {
    credentials: 'include',
    cache: 'no-store',
    headers: { 'Accept': 'application/json' }
  });
  const raw = await res.text();
  if (!raw.trim()) { alert(`La API respondió vacío (status ${res.status}).`); return; }

  let json;
  try {
    // Parse robusto por si hay espacios/BOM
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

  // Acepta { ok:true, data:{...} } o { user:{...} } o plano
  const u = (json && (json.data || json.user)) || json || {};
  // ---- render texto ----
  setText('profileName',   u.fullname || '');
  setText('profileUser',   u.username ? '@' + u.username : '');
  setText('fullname',      u.fullname);
  setText('username',      u.username);
  setText('email',         u.email);
  setText('birthday',      u.birthday);
  setText('gender',        ({1:'Femenino',2:'Masculino',3:'Otro'})[u.gender] || (u.gender ?? ''));
  setText('birth_country', u.birth_country);
  setText('country',       u.country);

  // ---- render avatar (b64 o url; jpg/png/webp/gif) ----
  const avatarSrc = buildAvatarSrc(u);
  setImgByIds(avatarSrc, 'profileAvatar', 'avatarImg');

})();