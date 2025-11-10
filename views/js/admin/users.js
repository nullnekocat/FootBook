// /views/js/admin/users.js
console.log('[Users] module loaded');

const API = { 
  list: '/FootBook/api/users',
  delete: '/FootBook/api/users/delete'
};

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

  // 👇 Delegación: escuchar clicks en botones de la tabla
  tbody.addEventListener('click', async (ev) => {
    const btn = ev.target.closest('button[data-action="delete"]');
    if (!btn) return;

    const tr = btn.closest('tr');
    const id = tr?.getAttribute('data-id');
    if (!id) return;

    // Confirmación simple
    const ok = confirm(`¿Dar de baja al usuario #${id}?`);
    if (!ok) return;

    // UI: estado de carga
    const oldHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '…';

    try {
      const res = await fetch(API.delete, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ user_id: Number(id) })
      });
      const raw = await res.text();
      let json;
      try { json = JSON.parse(raw); }
      catch { throw new Error('Respuesta no es JSON:\n' + raw.slice(0,300)); }

      if (json?.ok !== true) {
        throw new Error(json?.error || 'Error del API');
      }

      // 👇 Opción A: quitar la fila
      tr.remove();

      // Opción B (en lugar de quitar): marcar “inactivo”
      // tr.classList.add('table-warning');
      // btn.remove(); // o deshabilitar permanentemente

    } catch (err) {
      console.error('[Users] delete error:', err);
      alert(err?.message || 'No se pudo dar de baja');
      btn.disabled = false;
      btn.innerHTML = oldHTML;
      return;
    }
  });

  load();

  async function load() {
    try {
      console.log('[Users] GET', API.list);
      const res = await fetch(API.list, { method: 'GET' });
      const raw = await res.text();
      let json; 
      try { json = JSON.parse(raw); } 
      catch { throw new Error('Respuesta no es JSON:\n' + raw.slice(0,300)); }

      if (Array.isArray(json)) { render(json); return; }
      if (json && json.ok === true && Array.isArray(json.data)) { render(json.data); return; }
      if (json && json.ok === false) { throw new Error(json.error || 'Error del API'); }

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
          <td>
            <button class="btn btn-sm btn-outline-danger" data-action="delete" title="Dar de baja">🗑</button>
          </td>
        </tr>`;
    }).join('');
    tbody.innerHTML = rows;
  }
}
