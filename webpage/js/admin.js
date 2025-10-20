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