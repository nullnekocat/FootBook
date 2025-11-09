// views/js/admin/categories.js
import { getJSON, postNoBodyJSON, postJSON } from './api.js';

const API = {
  list:   '/FootBook/api/categories',
  create: '/FootBook/api/categories/:name', // name en la URL
};

function buildCreateUrl(name) {
  return API.create.replace(':name', encodeURIComponent(name));
}

function buildUpdateUrl(id) {
  return `/FootBook/api/categories/${encodeURIComponent(id)}/update`;
}

function buildDeleteUrl(id) {
  return `/FootBook/api/categories/${encodeURIComponent(id)}/delete`;
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

  // ===== DELEGACIÓN DE EVENTOS (editar/eliminar) =====
  list.addEventListener('click', async (e) => {
    const btnEdit = e.target.closest('[data-action="edit"]');
    const btnDel  = e.target.closest('[data-action="delete"]');

    if (btnEdit) {
      const id = btnEdit.getAttribute('data-id');
      const li = btnEdit.closest('li');
      const currentName = li.querySelector('span').textContent.trim();
      await editCategory(id, currentName);
    }

    if (btnDel) {
      const id = btnDel.getAttribute('data-id');
      const li = btnDel.closest('li');
      const currentName = li.querySelector('span').textContent.trim();
      await deleteCategory(id, currentName);
    }
  });

  // ===== EDITAR =====
  async function editCategory(id, currentName) {
    const newName = prompt(`Editar categoría:\n\n(Actual: "${currentName}")`, currentName);
    if (!newName || newName.trim() === '') return;
    if (newName.trim() === currentName) {
      alert('No hubo cambios');
      return;
    }

    try {
      const json = await postJSON(buildUpdateUrl(id), { name: newName.trim() });
      if (!json.ok) throw new Error(json.error || 'Error actualizando categoría');
      await load();
      alert('Categoría actualizada correctamente');
    } catch (err) {
      console.error(err);
      alert(err.message || 'Error al actualizar');
    }
  }

  // ===== ELIMINAR =====
  async function deleteCategory(id, name) {
    const confirmed = window.confirm(
      `¿Estás seguro de eliminar la categoría "${name}"?\n\n` +
      `Esta acción no se puede deshacer.`
    );
    if (!confirmed) return;

    try {
      // Usamos postNoBodyJSON para mantener el POST sin body que espera tu router/controlador
      const json = await postNoBodyJSON(buildDeleteUrl(id));
      if (!json.ok) throw new Error(json.error || 'Error eliminando categoría');
      await load();
      alert('Categoría eliminada correctamente');
    } catch (err) {
      // Si la respuesta del servidor no fue JSON, postNoBodyJSON lanzará con el texto (lo mostramos)
      console.error('Error al eliminar categoría:', err);
      alert(err.message || 'Error al eliminar');
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
        <button type="button" class="btn btn-sm btn-outline-warning me-1" data-action="edit" data-id="${c.id}">✎</button>
        <button type="button" class="btn btn-sm btn-outline-danger" data-action="delete" data-id="${c.id}">🗑</button>
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
