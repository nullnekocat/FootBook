// Rellena la vista con /api/me.php
(async () => {
  try {
    const res = await fetch('/FootBook/api/me.php', { credentials: 'include' });
    const raw = await res.text();
    let data;
    try { data = JSON.parse(raw); } catch { console.error(raw); alert('Respuesta no válida'); return; }

    if (!res.ok) { alert(data.error || 'Error'); return; }

    const u = data.user;
    // Cabecera
    document.getElementById('profileName').textContent = u.fullname || '';
    document.getElementById('profileUser').textContent = '@' + (u.username || '');

    // Campos
    const set = (id, v) => { const el = document.getElementById(id); if (el) el.value = v ?? ''; };
    set('fullname', u.fullname);
    set('email', u.email);
    set('username', u.username);
    set('birthday', u.birthday);
    set('gender',    ({1:'Femenino',2:'Masculino',3:'Otro'})[u.gender] || u.gender);
    set('birth_country', u.birth_country);
    set('country',  u.country);
    set('created_at', (u.created_at||'').replace('T',' ').substring(0,19));

    // Avatar ya está en el <img> por id; 
  } catch (e) {
    alert(e.message);
  }
})();
