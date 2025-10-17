const API_URL = '/FootBook/api/worldcups.php';
const container = document.getElementById('worldcupContainer');

async function loadWorldCups() {
    try {
        const res = await fetch(API_URL);
        const data = await res.json();

        if (!Array.isArray(data)) {
            console.error('Unexpected API response:', data);
            return;
        }

        container.innerHTML = '';

        data.forEach(cup => {
            const card = document.createElement('div');
            card.className = 'col-md-4 col-lg-3';

            const imgSrc = cup.banner || '../../img/default_banner.jpg';

            card.innerHTML = `
                <div class="card shadow-sm h-100">
                    <img src="${imgSrc}" class="card-img-top" alt="${cup.name}">
                    <div class="card-body text-center">
                        <h5 class="card-title">${cup.name}</h5>
                        <button class="btn btn-primary btn-sm" data-id="${cup.id}" data-bs-toggle="modal" data-bs-target="#worldcupModal">View Details</button>
                    </div>
                </div>
            `;

            container.appendChild(card);
        });

        document.querySelectorAll('[data-bs-target="#worldcupModal"]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const id = e.target.getAttribute('data-id');
                await loadModalData(id);
            });
        });

    } catch (err) {
        console.error('Error fetching World Cups:', err);
    }
}

async function loadModalData(id) {
    try {
        const res = await fetch(`${API_URL}?id=${id}`);
        const [data] = await res.json();

        if (!data) return;

        document.getElementById('worldcupModalLabel').textContent = data.name;
        document.getElementById('modalCountry').textContent = data.country;
        document.getElementById('modalYear').textContent = data.year;
        document.getElementById('modalDescription').textContent = data.description || 'No description available.';
        document.getElementById('modalBanner').src = data.banner || '../../img/default_banner.jpg';
    } catch (err) {
        console.error('Error loading modal data:', err);
    }
}

document.addEventListener('DOMContentLoaded', loadWorldCups);
