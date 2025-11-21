// views/js/navbar.js

const API_BASE = '/FootBook';

// Estado de filtros
const navbarFilters = {
    active: false,
    category: '',
    worldcup: '',
    order: 'cronologico',
    searchText: ''
};

// ========== CARGAR CATEGORÍAS ==========
async function loadCategories() {
    try {
        const response = await fetch(`${API_BASE}/api/categories`, {
            credentials: 'include',
            headers: { 'Accept': 'application/json' }
        });

        const raw = await response.text();
        let json;
        try {
            const i = raw.indexOf('{'), j = raw.lastIndexOf('}');
            json = JSON.parse(i >= 0 && j >= i ? raw.slice(i, j + 1) : raw);
        } catch {
            console.error('[Navbar] Error parsing categories JSON');
            return;
        }

        if (!json.ok || !Array.isArray(json.data)) {
            console.warn('[Navbar] No se pudieron cargar las categorías');
            return;
        }

        const categorySelect = document.getElementById('filter-category');
        if (!categorySelect) return;

        // Limpiar opciones excepto "Todas"
        categorySelect.querySelectorAll('option:not([value=""])').forEach(opt => opt.remove());

        // Agregar categorías
        json.data.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.name;
            categorySelect.appendChild(option);
        });

        console.log('[Navbar] Categorías cargadas:', json.data.length);

    } catch (err) {
        console.error('[Navbar] Error loading categories:', err);
    }
}

// ========== CARGAR MUNDIALES ==========
async function loadWorldcups() {
    try {
        const response = await fetch(`${API_BASE}/API/worldcups.php?type=light`, {
            credentials: 'include',
            headers: { 'Accept': 'application/json' }
        });

        const raw = await response.text();
        let json;
        try {
            const i = raw.indexOf('{'), j = raw.lastIndexOf('}');
            json = JSON.parse(i >= 0 && j >= i ? raw.slice(i, j + 1) : raw);
        } catch {
            console.error('[Navbar] Error parsing worldcups JSON');
            return;
        }

        if (!json.ok || !Array.isArray(json.data)) {
            console.warn('[Navbar] No se pudieron cargar los mundiales');
            return;
        }

        const worldcupSelect = document.getElementById('filter-worldcup');
        if (!worldcupSelect) return;

        // Limpiar opciones excepto "Todos"
        worldcupSelect.querySelectorAll('option:not([value=""])').forEach(opt => opt.remove());

        // Agregar mundiales
        json.data.forEach(wc => {
            const option = document.createElement('option');
            option.value = wc.id;
            option.textContent = `${wc.name} ${wc.year}`;
            worldcupSelect.appendChild(option);
        });

        console.log('[Navbar] Mundiales cargados:', json.data.length);

    } catch (err) {
        console.error('[Navbar] Error loading worldcups:', err);
    }
}

// ========== ACTUALIZAR ESTADO VISUAL DEL BOTÓN ==========
function updateSearchButtonState(active = false) {
    const filterBtn = document.getElementById('openFilterDropdown');
    if (!filterBtn) return;

    if (active) {
        filterBtn.classList.remove('btn-secondary');
        filterBtn.classList.remove('btn-warning');
        filterBtn.classList.add('btn-primary'); // azul
        filterBtn.title = 'Filtros aplicados';
    } else {
        filterBtn.classList.remove('btn-primary');
        filterBtn.classList.remove('btn-warning');
        filterBtn.classList.add('btn-secondary'); // gris
        filterBtn.title = 'Sin filtros aplicados';
    }
}

// ========== LIMPIAR FILTROS ==========
function clearFilters() {
    navbarFilters.category = '';
    navbarFilters.worldcup = '';
    navbarFilters.order = 'cronologico';
    navbarFilters.active = false;

    const categorySelect = document.getElementById('filter-category');
    const worldcupSelect = document.getElementById('filter-worldcup');
    const orderSelect = document.getElementById('filter-order');

    if (categorySelect) categorySelect.value = '';
    if (worldcupSelect) worldcupSelect.value = '';
    if (orderSelect) orderSelect.value = 'cronologico';

    //updateSearchButtonState();

    console.log('[Navbar] Filtros limpiados');
}

// ========== LEER FILTROS DE LA UI ==========
function readFiltersFromUI() {
    const categorySelect = document.getElementById('filter-category');
    const worldcupSelect = document.getElementById('filter-worldcup');
    const orderSelect = document.getElementById('filter-order');

    if (categorySelect) navbarFilters.category = categorySelect.value;
    if (worldcupSelect) navbarFilters.worldcup = worldcupSelect.value;
    if (orderSelect) navbarFilters.order = orderSelect.value || 'cronologico';

    // Determinar si hay filtros activos (además de la búsqueda)
    navbarFilters.active = !!(
        navbarFilters.category ||
        navbarFilters.worldcup ||
        navbarFilters.order !== 'cronologico'
    );

    updateSearchButtonState();
}

// ========== BÚSQUEDA GLOBAL ==========
function initNavbarSearch() {
    const mainSearchForm = document.getElementById('mainSearchForm');
    const mainSearchInput = document.getElementById('main-search-input');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    const openFilterBtn = document.getElementById('openFilterDropdown');


    if (openFilterBtn) {
        openFilterBtn.addEventListener('click', (ev) => {
            const currentPath = window.location.pathname;
            const isHome = currentPath.includes('/home') || currentPath.endsWith('/FootBook/') || currentPath.endsWith('/FootBook');

            // Intentamos localizar el botón real que aplica filtros en el homepage
            const applyBtn = document.getElementById('filter-apply');

            if (isHome && applyBtn) {
                console.log('[Navbar] Delegando a #filter-apply en home');
                navbarFilters.applied = true;
                updateSearchButtonState(true);
                applyBtn.click();
                return;
            }

            console.log('[Navbar] No hay #filter-apply; guardando filtros en navbarFilters');

            const categorySelect = document.getElementById('filter-category');
            const worldcupSelect = document.getElementById('filter-worldcup');
            const orderSelect = document.getElementById('filter-order');

            navbarFilters.category = categorySelect ? categorySelect.value : '';
            navbarFilters.worldcup = worldcupSelect ? worldcupSelect.value : '';
            navbarFilters.order = orderSelect ? orderSelect.value : 'cronologico';

            navbarFilters.active = !!(
                navbarFilters.category ||
                navbarFilters.worldcup ||
                navbarFilters.order !== 'cronologico'
            );

            updateSearchButtonState(navbarFilters.active);
            console.log('[Navbar] Filtros guardados manualmente:', navbarFilters);
        });
    }


    if (!mainSearchForm || !mainSearchInput) {
        console.warn('[Navbar] No se encontró el formulario de búsqueda');
        return;
    }

    // Cargar categorías y mundiales
    loadCategories();
    loadWorldcups();

    // Limpiar filtros
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', (e) => {
            e.preventDefault();
            clearFilters();

            // Cerrar dropdown
            //if (filterDropdown) {
            //    filterDropdown.classList.remove('show');
            //}
        });
    }

    // Cerrar dropdown al hacer clic afuera
    //document.addEventListener('click', function(e) {
    //    if (!filterDropdown || !openFilterBtn) return;
    //    if (!filterDropdown.contains(e.target) && !openFilterBtn.contains(e.target)) {
    //        filterDropdown.classList.remove('show');
    //    }
    //});

    // Actualizar estado de filtros cuando cambien
    const categorySelect = document.getElementById('filter-category');
    const worldcupSelect = document.getElementById('filter-worldcup');
    const orderSelect = document.getElementById('filter-order');

    [categorySelect, worldcupSelect, orderSelect].forEach(select => {
        if (select) {
            select.addEventListener('change', () => {
                updateSearchButtonState(false);
            });
        }
    });


    // Submit del formulario de búsqueda
    mainSearchForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Leer texto de búsqueda
        navbarFilters.searchText = mainSearchInput.value.trim();

        // Leer filtros de la UI
        //readFiltersFromUI();

        const currentPath = window.location.pathname;

        console.log('[Navbar] Búsqueda con filtros:', navbarFilters);

        // Si estamos en home, disparar evento personalizado
        if (currentPath.includes('/home') || currentPath.endsWith('/FootBook/') || currentPath.endsWith('/FootBook')) {
            console.log('[Navbar] Disparando evento feedSearch');

            const searchEvent = new CustomEvent('feedSearch', {
                detail: {
                    searchText: navbarFilters.searchText,
                    category: navbarFilters.category,
                    worldcup: navbarFilters.worldcup,
                    order: navbarFilters.order,
                    filtersActive: navbarFilters.active
                },
                bubbles: true,
                cancelable: true
            });
            document.dispatchEvent(searchEvent);

            // Cerrar dropdown
            //if (filterDropdown) {
            //    filterDropdown.classList.remove('show');
            //}
        } else {
            // Redirigir a home con parámetros
            console.log('[Navbar] Redirigiendo a home con búsqueda');

            const params = new URLSearchParams();
            if (navbarFilters.searchText) params.set('q', navbarFilters.searchText);
            if (navbarFilters.category) params.set('category_id', navbarFilters.category);
            if (navbarFilters.worldcup) params.set('worldcup_id', navbarFilters.worldcup);
            if (navbarFilters.order && navbarFilters.order !== 'cronologico') {
                params.set('order', navbarFilters.order);
            }

            const queryString = params.toString();
            window.location.href = `${API_BASE}/home${queryString ? '?' + queryString : ''}`;
        }
    });

    // Leer parámetros de la URL al cargar (si estamos en home)
    const currentPath = window.location.pathname;
    if (currentPath.includes('/home') || currentPath.endsWith('/FootBook/') || currentPath.endsWith('/FootBook')) {
        const urlParams = new URLSearchParams(window.location.search);

        const searchText = urlParams.get('q');
        const categoryId = urlParams.get('category_id');
        const worldcupId = urlParams.get('worldcup_id');
        const order = urlParams.get('order');

        if (searchText) {
            mainSearchInput.value = searchText;
            navbarFilters.searchText = searchText;
        }

        // Esperar a que los selects se llenen antes de establecer valores
        setTimeout(() => {
            if (categoryId && categorySelect) {
                categorySelect.value = categoryId;
                navbarFilters.category = categoryId;
            }
            if (worldcupId && worldcupSelect) {
                worldcupSelect.value = worldcupId;
                navbarFilters.worldcup = worldcupId;
            }
            if (order && orderSelect) {
                orderSelect.value = order;
                navbarFilters.order = order;
            }

            //readFiltersFromUI();
        }, 500);
    }
}

// Exponer funciones globalmente para que home.js pueda acceder
window.navbarFilters = navbarFilters;
window.clearNavbarFilters = clearFilters;

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNavbarSearch);
} else {
    initNavbarSearch();
}