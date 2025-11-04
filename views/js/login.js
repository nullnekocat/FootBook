(() => {
  'use strict';
  const form = document.getElementById('loginForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const identity = document.getElementById('identity')?.value?.trim() ?? '';
    const password = document.getElementById('password')?.value ?? '';

    if (!identity || !password) {
      alert('Ingresa usuario/email y contraseña');
      return;
    }

    try {
      const res = await fetch('/FootBook/api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ identity, password })
      });

      const raw = await res.text();
      let json;
      try { json = JSON.parse(raw); } catch {
        alert('La API no devolvió JSON válido:\n' + raw.slice(0, 300));
        return;
      }

      if (!res.ok) {
        alert(json.error || ('Error ' + res.status));
        return;
      }

      // éxito
      if (json.redirect) {
        window.location.href = json.redirect;
      } else {
        alert('Login correcto');
      }

    } catch (err) {
      alert('Error: ' + err.message);
    }
  });
})();
