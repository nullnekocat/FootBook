// /FootBook/views/js/admin/categories.js
console.log('[Categories] module loaded');

const API = {
  // Usa tus endpoints actuales; si cambias a REST canónico, ajusta aquí
  list:   '/FootBook/api/categories/list',
  create: '/FootBook/api/categories/create',
};

export function bootCategories() {
  console.log('[Categories] bootCategories');
  const form  = document.getElementById('category-form');
  const input = document.getElementById('category-name');
  const list  = document.getElementById('category-list');

  if (!form || !input || !list) {
    console.warn('[Categories] missing DOM nodes', { form: !!form, input: !!input, list: !!list });
    return;
  }

  // idempotente: evita registrar múltiples listeners
  if (form.dataset.wired === 'true') {
    console.log('[Categories] already wired');
  } else {
    form.dataset.wired = 'true';
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const name = (input.value || '').trim();
      if (!name) { input.focus(); return; }

      try {
        console.log('[Categories] POST', API.create, { name });
        const res = await fetch(API.create, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name }),
        });
        const raw = await res.text();
        console.log('[Categories] POST status:', res.status, 'raw:', raw);

        const json = JSON.parse(raw);
        if (!res.ok) throw new Error(json.error || ('Error ' + res.status));

        input.value = '';
        await load(); // recarga listado sin refrescar página
      } catch (err) {
        console.error('[Categories] create error:', err);
        alert(err.message || err);
      }
    });
  }

  // Carga al entrar al módulo
  load();

  async function load() {
    try {
      console.log('[Categories] GET', API.list);
      const res = await fetch(API.list, { method: 'GET' });
      const raw = await res.text();
      console.log('[Categories] GET status:', res.status, 'raw:', raw);

      const json = JSON.parse(raw);
      if (!res.ok) throw new Error(json.error || ('Error ' + res.status));

      const items = Array.isArray(json?.data) ? json.data : [];
      render(items);
    } catch (err) {
      console.error('[Categories] list error:', err);
      list.innerHTML = `<li class="list-group-item text-danger">${(err.message || err)}</li>`;
    }
  }

  function render(items) {
    if (!items.length) {
      list.innerHTML = `<li class="list-group-item">No hay categorías.</li>`;
      return;
    }
    list.innerHTML = items.map(c => `
      <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${c.id}">
        <span>${escapeHtml(c.name)}</span>
        <span>
          <button class="btn btn-sm btn-outline-warning me-1" data-action="edit" data-id="${c.id}">✎</button>
          <button class="btn btn-sm btn-outline-danger"  data-action="delete" data-id="${c.id}">🗑</button>
        </span>
      </li>
    `).join('');
  }

  const escapeHtml = (s='') => s
    .replaceAll('&','&amp;').replaceAll('<','&lt;')
    .replaceAll('>','&gt;').replaceAll('"','&quot;')
    .replaceAll("'",'&#039;');
}
