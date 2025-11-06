// views/js/admin/categories.js
import { getJSON } from './api.js';

const API = {
  list:   '/FootBook/api/categories',
  create: '/FootBook/api/categories/:name', // name en la URL
};

function buildCreateUrl(name) {
  return API.create.replace(':name', encodeURIComponent(name));
}

export function bootCategories() {
  const form  = document.getElementById('category-form');
  const input = document.getElementById('category-name');
  const list  = document.getElementById('category-list');
  if (!form || !input || !list) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const name = (input.value || '').trim();
    if (!name) { input.focus(); return; }

    try {
      const res = await fetch(buildCreateUrl(name), { method: 'POST' }); // sin body
      const json = await res.json();
      if (!json.ok) throw new Error(json.error || 'Error creando categoría');
      input.value = '';
      await load();
    } catch (err) {
      console.error(err);
      alert(err.message || 'Error');
    }
  });

  async function load() {
    try {
      const json = await getJSON(API.list);
      render(Array.isArray(json?.data) ? json.data : []);
    } catch (err) {
      console.error(err);
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
        <span>${escapeHtml(c.name ?? c.category ?? '')}</span>
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

  load();
}
