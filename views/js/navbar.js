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
document.getElementById('openFilterDropdown').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('filterDropdownMenu').classList.toggle('show');
});