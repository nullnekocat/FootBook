// /FootBook/views/js/admin/index.js
// Arranque por el panel activo en tu HTML (.tab-pane.show.active)
// y cambio de pestañas con botones .admin-tab-btn (data-target="#admin-...")
console.log('[Admin] index.js loaded');

function idToName(panelId) {
  // Mapea id de panel a nombre de módulo
  // admin-categories | admin-posts | admin-wikis | admin-users
  if (!panelId) return null;
  if (panelId.includes('categories')) return 'categories';
  if (panelId.includes('posts'))      return 'posts';
  if (panelId.includes('wikis'))      return 'wikis';
  if (panelId.includes('users'))      return 'users';
  return null;
}

async function boot(name) {
  console.log('[Admin] boot →', name);
  if (name === 'categories') {
    const { bootCategories } = await import('./categories.js');
    bootCategories(); // idempotente
  }
  // Si luego haces módulos:
  // else if (name === 'posts')  { const { bootPosts }  = await import('./posts.js');  bootPosts(); }
  // else if (name === 'wikis')  { const { bootWikis }  = await import('./wikis.js');  bootWikis(); }
  
  else if (name === 'users')  { const { bootUsers }  = await import('./users.js');  bootUsers(); }
}

document.addEventListener('DOMContentLoaded', async () => {
  console.log('[Admin] DOMContentLoaded');

  // 1) Panel activo al cargar
  const activePane = document.querySelector('.tab-pane.show.active');
  const activeName = idToName(activePane?.id);
  if (activeName) await boot(activeName);
  else console.warn('[Admin] No active panel found');

  // 2) Cambio de pestañas por botón
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.admin-tab-btn');
    if (!btn) return;

    const targetSel = btn.getAttribute('data-target'); // ej. "#admin-categories"
    if (!targetSel) return;

    // Simula el switch de tabs (si no usas el JS de Bootstrap)
    const currentActiveBtn  = document.querySelector('.admin-tab-btn.active');
    const currentActivePane = document.querySelector('.tab-pane.show.active');
    currentActiveBtn?.classList.remove('active');
    currentActivePane?.classList.remove('show', 'active');

    btn.classList.add('active');
    const pane = document.querySelector(targetSel);
    pane?.classList.add('show', 'active');

    const name = idToName(pane?.id);
    if (name) await boot(name);
  });
});
