// views/js/navbar.js

// Cerrar dropdown al hacer click afuera
document.addEventListener('click', function(e) {
    const filterDropdown = document.getElementById('filterDropdownMenu');
    const filterBtn = document.getElementById('openFilterDropdown');
    if (!filterDropdown || !filterBtn) return;
    if (!filterDropdown.contains(e.target) && !filterBtn.contains(e.target)) {
        filterDropdown.classList.remove('show');
    }
});

// Toggle dropdown manual
const openFilterBtn = document.getElementById('openFilterDropdown');
if (openFilterBtn) {
    openFilterBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const dropdown = document.getElementById('filterDropdownMenu');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    });
}

// ========== BÚSQUEDA GLOBAL ==========
function initNavbarSearch() {
    const mainSearchForm = document.getElementById('mainSearchForm');
    const mainSearchInput = document.getElementById('main-search-input');

    if (!mainSearchForm || !mainSearchInput) {
        console.warn('[Navbar] No se encontró el formulario de búsqueda');
        return;
    }

    mainSearchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const searchText = mainSearchInput.value.trim();
        const currentPath = window.location.pathname;
        
        console.log('[Navbar] Búsqueda:', searchText, 'Path:', currentPath);
        
        // Si estamos en la página de inicio, disparar evento personalizado
        if (currentPath.includes('/home')) {
            console.log('[Navbar] Disparando evento feedSearch');
            
            // Disparar evento personalizado que home.js escuchará
            const searchEvent = new CustomEvent('feedSearch', { 
                detail: { searchText },
                bubbles: true,
                cancelable: true
            });
            document.dispatchEvent(searchEvent);
        } else {
            // Si no estamos en home, redirigir a home con parámetro de búsqueda
            console.log('[Navbar] Redirigiendo a home con búsqueda');
            window.location.href = `/FootBook/home${searchText ? '?q=' + encodeURIComponent(searchText) : ''}`;
        }
    });
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNavbarSearch);
} else {
    // DOM ya está listo
    initNavbarSearch();
}