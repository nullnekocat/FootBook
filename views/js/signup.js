// signup.js
(() => {
  'use strict';

  const form = document.getElementById('signupForm');
  if (!form) return;

  // ===== helpers (idénticos a los que ya tenías) =====
  const pwdInput = document.getElementById('password');
  // --- Mostrar / ocultar contraseña ---
  const toggleBtn = document.getElementById('togglePassword');
  if (toggleBtn && pwdInput) {
    // accesibilidad
    toggleBtn.setAttribute('aria-label', 'Mostrar u ocultar contraseña');
    toggleBtn.setAttribute('aria-pressed', 'false');

    const EYE = `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Z"/></svg>`;
    const EYE_OFF = `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M776-68 650-194q-36 11-77 17.5T480-170q-146 0-266-81.5T40-500q31-78 89-140.5T271-760L92-939l56-56 684 684-56 56L832-68l-56 56Zm-88-200-64-64q18-27 28-58t10-66q0-83-58.5-141.5T480-656q-35 0-66 10t-58 28l-64-64q45-29 95.5-44.5T480-742q146 0 266 81.5T920-500q-29 73-81.5 134T688-268ZM480-314q26 0 49-8t43-22l-62-62q-6 3-13 4.5t-17 1.5q-45 0-76.5-31.5T372-500q0-10 1.5-17t4.5-13l-62-62q-14 20-22 43t-8 49q0 75 52.5 127.5T480-314Z"/></svg>`;

    // estado inicial (oculta)
    toggleBtn.dataset.visible = 'false';

    toggleBtn.addEventListener('click', () => {
      const showing = toggleBtn.dataset.visible === 'true';
      pwdInput.type = showing ? 'password' : 'text';
      toggleBtn.dataset.visible = showing ? 'false' : 'true';
      toggleBtn.setAttribute('aria-pressed', (!showing).toString());

      // opcional: cambia el ícono
      toggleBtn.innerHTML = showing ? EYE : EYE_OFF;
    });
  }
  const birthInput = document.getElementById('birthdate');
  const genderSelect = document.getElementById('gender');

  function validatePassword(pwd) {
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/;
    return regex.test(pwd);
  }
  if (pwdInput) {
    pwdInput.addEventListener('input', () => {
      const ok = validatePassword(pwdInput.value);
      pwdInput.setCustomValidity(ok ? '' : 'La contraseña no cumple los requisitos');
      pwdInput.classList.toggle('is-valid', ok);
      pwdInput.classList.toggle('is-invalid', !ok);
    });
  }

  function validateBirthdate() {
    if (!birthInput) return true;
    const birthDate = new Date(birthInput.value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
    if (birthInput.value && age < 12) {
      birthInput.setCustomValidity('Debes tener al menos 12 años para registrarte.');
      birthInput.classList.add('is-invalid'); birthInput.classList.remove('is-valid');
      return false;
    } else if (birthInput.value) {
      birthInput.setCustomValidity('');
      birthInput.classList.remove('is-invalid'); birthInput.classList.add('is-valid');
      return true;
    } else {
      birthInput.classList.remove('is-valid', 'is-invalid');
      return false;
    }
  }
  if (birthInput) {
    birthInput.addEventListener('change', validateBirthdate);
    birthInput.addEventListener('input', validateBirthdate);
  }

  function mapGender(v) {
    if (v == null) return null;
    const val = String(v).trim();
    if (['1','2','3'].includes(val)) return parseInt(val, 10);
    const lc = val.toLowerCase();
    if (lc.startsWith('f')) return 1;
    if (lc.startsWith('m')) return 2;
    return 3; // otro
  }

  // ===== submit =====
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const passwordOK = pwdInput ? validatePassword(pwdInput.value) : false;
    if (!passwordOK || !validateBirthdate()) return;

    const genderValue = mapGender(genderSelect ? genderSelect.value : null);

    // Avatar a Base64 (sin prefijo)
    const photoInput = document.getElementById('photo');
    let avatarBase64 = null;
    if (photoInput?.files?.length) {
      const file = photoInput.files[0];
      avatarBase64 = await new Promise(resolve => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result.split(',')[1] || null);
        reader.readAsDataURL(file);
      });
    }

    // 👇 claves EXACTAS que tu backend espera / SP usa
    const payload = {
      username: document.getElementById('username')?.value ?? '',
      email: document.getElementById('email')?.value ?? '',
      password: pwdInput?.value ?? '',
      fullname: document.getElementById('fullname')?.value ?? '',
      birthday: document.getElementById('birthdate')?.value ?? '',
      gender: genderValue,
      birth_country: document.getElementById('birthcountry')?.value ?? '',
      country: document.getElementById('nationality')?.value ?? '',
      avatar: avatarBase64,
      admin: 0
    };

    try {
      const res = await fetch('/FootBook/api/users/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const raw = await res.text();
      let resp;
      try { resp = JSON.parse(raw); } catch {
        alert('La API no devolvió JSON válido:\n' + raw.slice(0, 300));
        return;
      }

      if (!res.ok) {
        alert(resp.error || ('Error ' + res.status));
        return;
      }

      // ok
      document.getElementById('signupSuccess')?.classList.remove('d-none');
      // Redirige a login (usa redirect del backend si prefieres)
      setTimeout(() => window.location.href = '/FootBook/login', 1200);

    } catch (err) {
      alert('Error: ' + (err?.message || err));
    }
  });
})();
