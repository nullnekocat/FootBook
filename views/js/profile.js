(async () => {

  const BASE = (window.__BASE_URL__ || '/FootBook/').replace(/\/+$/, '') + '/';
  const url  = BASE + 'api/me.php?t=' + Date.now();

  console.log('[PROFILE] BASE:', BASE, 'URL:', url);

  const res = await fetch(url, {
    credentials: 'include',
    cache: 'no-store',
    headers: { 'Accept': 'application/json' }
  });

  const raw = await res.text();
  console.log('[ME] status:', res.status, 'ct:', res.headers.get('content-type'), 'len:', raw.length, 'preview:', raw.slice(0,200));

  if (!raw.trim()) { alert('La API respondió vacío (status '+res.status+'). Revisa me.php/echo/exit.'); return; }
  if (!res.ok) { try { alert(JSON.parse(raw).error); } catch { alert('Error '+res.status); } return; }

  // parse robusto
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
  const setText = (id, v) => { const el = document.getElementById(id); if (!el) { console.warn('faltó id:', id); return; } ('value' in el) ? el.value = v ?? '' : el.textContent = v ?? ''; };

  setText('profileName', u.fullname || '');
  setText('profileUser', '@' + (u.username || ''));
  setText('fullname', u.fullname);
  setText('username', u.username);
  setText('email', u.email);
  setText('birthday', u.birthday);
  setText('gender', ({1:'Femenino',2:'Masculino',3:'Otro'})[u.gender] || u.gender);
  setText('birth_country', u.birth_country);
  setText('country', u.country);
})();
