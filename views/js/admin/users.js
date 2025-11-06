// /views/js/admin/users.js
console.log('[Users] module loaded');

const API = { 
  list: '/FootBook/api/users'
};

// util simple y hoisteado
function escapeHtml(s = '') {
  return s
    .replaceAll('&','&amp;').replaceAll('<','&lt;')
    .replaceAll('>','&gt;').replaceAll('"','&quot;')
    .replaceAll("'",'&#039;');
}

export function bootUsers() {
  console.log('[Users] bootUsers');
  const pane  = document.getElementById('admin-users');
  const table = document.getElementById('admin-users-table');
  const tbody = table?.querySelector('tbody');
  if (!pane || !table || !tbody) { 
    console.warn('[Users] Faltan nodos'); 
    return; 
  }

  if (pane.dataset.wired === 'true') { load(); return; }
  pane.dataset.wired = 'true';

  load();

  async function load() {
    try {
      console.log('[Users] GET', API.list);
      const res = await fetch(API.list, { method: 'GET' });
      const raw = await res.text();
      let json; 
      try { json = JSON.parse(raw); } 
      catch { throw new Error('Respuesta no es JSON:\n' + raw.slice(0,300)); }

      // Soporta { ok:true, data:[...] } o directamente [...]
      if (Array.isArray(json)) {
        render(json);
        return;
      }
      if (json && json.ok === true && Array.isArray(json.data)) {
        render(json.data);
        return;
      }
      // Si el controller manda ok:false, muéstralo
      if (json && json.ok === false) {
        throw new Error(json.error || 'Error del API');
      }

      throw new Error('Formato inesperado de respuesta');
    } catch (err) {
      console.error('[Users] list error:', err);
      tbody.innerHTML = `<tr><td colspan="6" class="text-danger">${escapeHtml(err.message || String(err))}</td></tr>`;
    }
  }

  function render(items) {
    if (!items.length) {
      tbody.innerHTML = `<tr><td colspan="6">No hay usuarios.</td></tr>`;
      return;
    }
    const rows = items.map(u => {
      const id        = u.id ?? u.user_id ?? '';
      const username  = u.username ?? '';
      const fullname  = u.fullname ?? u.fullName ?? '';
      const email     = u.email ?? '';
      const created   = u.created_at ?? u.createdAt ?? '';

      return `
        <tr data-id="${escapeHtml(String(id))}">
          <td>${escapeHtml(String(id))}</td>
          <td>${escapeHtml(username)}</td>
          <td>${escapeHtml(fullname)}</td>
          <td>${escapeHtml(email)}</td>
          <td>${escapeHtml(created)}</td>
          <td><button class="btn btn-sm btn-outline-danger" disabled>🗑</button></td>
        </tr>`;
    }).join('');
    tbody.innerHTML = rows;
  }
}
