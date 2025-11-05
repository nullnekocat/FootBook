// /FootBook/views/js/admin/users.js
console.log('[Users] module loaded');

const API = { list: '/FootBook/api/users/list' };

// ✅ función hoisteada (ya no entra en TDZ)
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
  if (!pane || !table || !tbody) { console.warn('[Users] Faltan nodos'); return; }

  if (pane.dataset.wired === 'true') { load(); return; }
  pane.dataset.wired = 'true';

  load();

  async function load() {
    try {
      console.log('[Users] GET', API.list);
      const res = await fetch(API.list, { method: 'GET' });
      const raw = await res.text();
      console.log('[Users] GET status:', res.status, 'raw:', raw);

      let json; try { json = JSON.parse(raw); } catch { throw new Error('Respuesta no es JSON:\n' + raw.slice(0,300)); }
      if (!res.ok) throw new Error(json.error || ('Error ' + res.status));

      const items = Array.isArray(json) ? json : (Array.isArray(json?.data) ? json.data : []);
      render(items);
    } catch (err) {
      console.error('[Users] list error:', err);
      tbody.innerHTML = `<tr><td colspan="6" class="text-danger">${err.message || err}</td></tr>`;
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
        <tr data-id="${id}">
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
