document.querySelectorAll('.admin-tab-btn').forEach(btn => {
    btn.addEventListener('click', function(){
        document.querySelectorAll('.admin-tab-btn').forEach(b=>b.classList.remove('active'));
        this.classList.add('active');
        document.querySelectorAll('.tab-pane').forEach(tp=>tp.classList.remove('show','active'));
        document.querySelector(this.getAttribute('data-target')).classList.add('show','active');
    });
});

async function loadAdminUsers() {
    try {
        const res = await fetch('/FootBook/api/users.php', { method: 'GET' });
        const users = await res.json();

        const tbody = document.querySelector('#admin-users-table tbody');
        tbody.innerHTML = '';

        if (!Array.isArray(users)) {
            tbody.innerHTML = '<tr><td colspan="6">No se pudieron cargar los usuarios.</td></tr>';
            return;
        }

        users.forEach(u => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${u.id ?? u.user_id ?? ''}</td>
                <td>${u.username ?? ''}</td>
                <td>${u.fullname ?? u.fullName ?? ''}</td>
                <td>${u.email ?? ''}</td>
                <td>${u.created_at ?? ''}</td>
                <td>
                    <button class="btn btn-sm btn-danger admin-user-delete" data-id="${u.id ?? u.user_id}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Wire delete buttons
        document.querySelectorAll('.admin-user-delete').forEach(btn => {
            btn.addEventListener('click', async function(){
                const id = this.getAttribute('data-id');
                if (!confirm('¿Eliminar usuario con ID ' + id + '?')) return;

                try {
                    const resp = await fetch('/FootBook/api/users.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id })
                    });
                    const body = await resp.json();
                    alert(body.message || body.error || 'Acción completada');
                    loadAdminUsers();
                } catch (err) {
                    alert('Error: ' + err.message);
                }
            });
        });

    } catch (err) {
        console.error(err);
    }
}

// Load users when Admin Users tab is activated
document.querySelectorAll('.admin-tab-btn').forEach(btn => {
    btn.addEventListener('click', function(){
        const target = this.getAttribute('data-target');
        if (target === '#admin-users') loadAdminUsers();
    });
});

// If admin users tab is active on load, fetch immediately
if (document.querySelector('#admin-users').classList.contains('show')) {
    loadAdminUsers();
}

(async () => {
  // === Utilidad para escapar HTML ===
  const escapeHtml = (s='') => s
    .replaceAll('&','&amp;').replaceAll('<','&lt;')
    .replaceAll('>','&gt;').replaceAll('"','&quot;')
    .replaceAll("'",'&#039;');

  // === Nuevo fetch adaptado al controller ===
  async function fetchCategories() {
    const res = await fetch('/FootBook/api/categories?ts=' + Date.now(), { method: 'GET' });
    if (!res.ok) throw new Error('No se pudieron obtener las categorías');
    const data = await res.json();
    // nuestro controller devuelve { data: [...] }
    return Array.isArray(data?.data) ? data.data : [];
  }

  function renderCategoryList(items) {
    const ul = document.getElementById('category-list');
    if (!ul) return;

    ul.innerHTML = '';
    if (!items.length) {
      ul.innerHTML = `<li class="list-group-item">No hay categorías registradas.</li>`;
      return;
    }

    for (const c of items) {
      const li = document.createElement('li');
      li.className = 'list-group-item d-flex justify-content-between align-items-center';
      li.dataset.id = c.id ?? c.category_id ?? '';

      li.innerHTML = `
        <span>${escapeHtml(c.name ?? c.category_name ?? '')}</span>
        <span>
          <button class="btn btn-sm btn-outline-warning me-1" data-action="edit" data-id="${c.id ?? c.category_id}">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Z"/></svg>
          </button>
          <button class="btn btn-sm btn-outline-danger" data-action="delete" data-id="${c.id ?? c.category_id}">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Z"/></svg>
          </button>
        </span>
      `;
      ul.appendChild(li);
    }
  }

  function renderCategorySelect(items) {
    const sel = document.getElementById('categorySelect');
    if (!sel) return;
    sel.innerHTML = items.map(c => `<option value="${c.id ?? c.category_id}">${escapeHtml(c.name ?? c.category_name ?? '')}</option>`).join('');
    sel.classList.toggle('d-none', items.length === 0);
  }

  async function loadCategories() {
    const items = await fetchCategories();
    renderCategoryList(items);
    renderCategorySelect(items);
  }

  // Delegación para los botones Edit/Delete
  document.addEventListener('click', async (ev) => {
    const btn = ev.target.closest('[data-action]');
    if (!btn) return;

    const action = btn.dataset.action;
    const id = btn.dataset.id;

    if (action === 'edit') {
      alert('Editar categoría ' + id + ' (implementa tu PUT /FootBook/api/categories/' + id + ')');
    }

    if (action === 'delete') {
      if (!confirm('¿Eliminar la categoría ' + id + '?')) return;
      alert('DELETE aún no implementado en el backend.');
      // Cuando implementes DELETE:
      /*
      const resp = await fetch('/FootBook/api/categories/' + id, { method: 'DELETE' });
      const body = await resp.json();
      alert(body.message || body.error || 'Acción completada');
      await loadCategories();
      */
    }
  });

  // Carga inicial
  try { await loadCategories(); }
  catch (e) { console.error(e); alert(e.message); }
})();