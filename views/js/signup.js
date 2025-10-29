// Bootstrap custom validation para todos los campos EN VIVO
(() => {
  'use strict';

  const form = document.getElementById('signupForm');
  if (!form) return; // no hay formulario en esta página

  // Validación en vivo para todos los campos requeridos (excepto password y birthdate)
  form.querySelectorAll('input, select, textarea').forEach(input => {
    if (input.id === 'password' || input.id === 'birthdate') return;
    input.addEventListener('input', function () {
      if (input.checkValidity()) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
      } else {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
      }
    });
  });

  // Mostrar/ocultar contraseña (con guards por si no existe el icono o el botón)
  const toggleBtn = document.getElementById('togglePassword');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', function () {
      const pwd = document.getElementById('password');
      if (!pwd) return;
      const icon = document.getElementById('eyeIcon'); // puede NO existir
      if (pwd.type === 'password') {
        pwd.type = 'text';
        if (icon) { icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash'); }
      } else {
        pwd.type = 'password';
        if (icon) { icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye'); }
      }
    });
  }

  // Validación de contraseña en vivo
  const pwdInput = document.getElementById('password');
  function validatePassword(pwd) {
    // 8+ chars, 1 min, 1 mayus, 1 num, 1 especial
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/;
    return regex.test(pwd);
  }
  if (pwdInput) {
    pwdInput.addEventListener('input', function () {
      const ok = validatePassword(pwdInput.value);
      pwdInput.setCustomValidity(ok ? '' : 'La contraseña no cumple los requisitos');
      pwdInput.classList.toggle('is-valid', ok);
      pwdInput.classList.toggle('is-invalid', !ok);
    });
  }

  // Validación de fecha de nacimiento (mínimo 12 años)
  const birthInput = document.getElementById('birthdate');
  const birthFeedback = document.getElementById('birthdateFeedback');
  function validateBirthdate() {
    if (!birthInput) return true;
    const birthDate = new Date(birthInput.value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
    if (birthInput.value && age < 12) {
      birthInput.setCustomValidity('Debes tener al menos 12 años para registrarte.');
      if (birthFeedback) birthFeedback.textContent = 'Debes tener al menos 12 años para registrarte.';
      birthInput.classList.add('is-invalid'); birthInput.classList.remove('is-valid');
      return false;
    } else if (birthInput.value) {
      birthInput.setCustomValidity('');
      if (birthFeedback) birthFeedback.textContent = 'Debes tener al menos 12 años para registrarte.';
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

  // Normaliza cualquier valor del <select> de género a 1/2/3
  const genderSelect = document.getElementById('gender');
  function mapGender(v) {
    if (v == null) return null;
    const val = String(v).trim();
    if (['1', '2', '3'].includes(val)) return parseInt(val, 10);
    const lc = val.toLowerCase();
    if (lc.startsWith('f')) return 1;         // F, Femenino
    if (lc.startsWith('m')) return 2;         // M, Masculino
    if (lc.startsWith('o')) return 3;         // O, Otro
    if (lc.includes('otro')) return 3;        // “Otro”
    return null;
  }

  // Submit
  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    // Validaciones finales
    const passwordOK = pwdInput ? validatePassword(pwdInput.value) : false;
    if (!passwordOK) {
      if (pwdInput) { pwdInput.setCustomValidity('La contraseña no cumple los requisitos'); pwdInput.classList.add('is-invalid'); }
      return;
    } else {
      if (pwdInput) pwdInput.setCustomValidity('');
    }
    if (!validateBirthdate()) return;

    const genderValue = mapGender(genderSelect ? genderSelect.value : null);

    const photoInput = document.getElementById('photo');
    let avatarBase64 = null;
    if (photoInput && photoInput.files && photoInput.files.length) {
      const file = photoInput.files[0];
      avatarBase64 = await new Promise(resolve => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result.split(',')[1]); // Base64 sin prefijo
        reader.readAsDataURL(file);
      });
    }

    const data = {
      username: document.getElementById('username')?.value ?? '',
      email: document.getElementById('email')?.value ?? '',
      password: pwdInput?.value ?? '',
      fullName: document.getElementById('fullname')?.value ?? '',
      birthday: document.getElementById('birthdate')?.value ?? '',
      gender: genderValue,
      birth_country: document.getElementById('birthcountry')?.value ?? '',
      country: document.getElementById('nationality')?.value ?? '',
      avatar: avatarBase64,
      admin: 0
    };

    try {
      const res = await fetch('/FootBook/api/users.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });

      // lee texto primero para depurar si no es JSON
      const raw = await res.text();
      let resp;
      try { resp = JSON.parse(raw); } catch {
        alert('La API no devolvió JSON válido:\n' + raw.slice(0, 300));
        return;
      }

      if (!res.ok) {
        // el controlador manda "Falta el campo: X" si falta algo
        alert(resp.error || ('Error ' + res.status));
        return;
      }

      if (resp.message) {
        document.getElementById('signupSuccess')?.classList.remove('d-none');
        setTimeout(() => window.location.href = 'router.php?page=login', 1200);
      } else {
        alert(resp.error || 'Error desconocido');
      }
    } catch (err) {
      alert('Error: ' + err.message);
    }
  });
})();
