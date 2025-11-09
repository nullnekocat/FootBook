// /views/js/profile.js
// Variable global para guardar datos del usuario (FUERA de la función async)
let currentUser = null;

(async () => {
  const BASE = (window.__BASE_URL__ || '/FootBook/').replace(/\/+$/, '') + '/';
  const url  = BASE + 'api/users/me';
  const FALLBACK_AVATAR = BASE + 'img/default-avatar.png';

  // ---- helpers ----
  const setText = (id, v) => {
    const el = document.getElementById(id);
    if (!el) { console.warn('[PROFILE] faltó id:', id); return; }
    ('value' in el) ? el.value = (v ?? '') : el.textContent = (v ?? '');
  };
  
  const setImgByIds = (src, ...ids) => {
    ids.forEach(id => {
      const el = document.getElementById(id);
      if (!el) return;
      el.addEventListener?.('error', () => { if (el.src !== FALLBACK_AVATAR) el.src = FALLBACK_AVATAR; });
      el.addEventListener?.('load',  () => console.log('[PROFILE] avatar loaded:', el.src));
      el.src = src;
    });
  };

  // Detecta MIME por prefijo de base64
  const guessMimeFromB64 = (b64 = '') => {
    if (b64.startsWith('/9j/'))   return 'image/jpeg';
    if (b64.startsWith('iVBOR'))  return 'image/png';
    if (b64.startsWith('UklGR'))  return 'image/webp';
    if (b64.startsWith('R0lGO'))  return 'image/gif';
    return 'image/jpeg';
  };
  
  const buildAvatarSrc = (u) => {
    if (u.avatar_url) return u.avatar_url;
    const b64 = u.avatar_b64 || u.avatarB64 || u.avatar;
    if (b64 && b64.length > 10) {
      const mime = guessMimeFromB64(b64);
      return `data:${mime};base64,${b64}`;
    }
    return FALLBACK_AVATAR;
  };

  const mapGenderToValue = (gender) => {
    if (gender === 1 || gender === 'F' || gender === 'Femenino') return 'F';
    if (gender === 2 || gender === 'M' || gender === 'Masculino') return 'M';
    return 'O';
  };

  const mapGenderToText = (gender) => {
    const map = {1:'Femenino', 2:'Masculino', 3:'Otro', 'F':'Femenino', 'M':'Masculino', 'O':'Otro'};
    return map[gender] || (gender ?? '');
  };

  // ---- fetch user data ----
  try {
    const res = await fetch(url, {
      credentials: 'include',
      cache: 'no-store',
      headers: { 'Accept': 'application/json' }
    });
    const raw = await res.text();
    if (!raw.trim()) { 
      alert(`La API respondió vacío (status ${res.status}).`); 
      return; 
    }

    let json;
    try {
      const i0 = raw.indexOf('{'), j0 = raw.lastIndexOf('}');
      json = JSON.parse(i0 >= 0 && j0 >= i0 ? raw.slice(i0, j0+1) : raw);
    } catch (e) {
      console.error('[PROFILE] JSON parse error', e, raw);
      alert('Respuesta no válida del API.');
      return;
    }

    if (!res.ok || (json && json.ok === false)) {
      alert((json && (json.error || json.message)) || `Error ${res.status}`);
      return;
    }

    // Guardar datos del usuario EN LA VARIABLE GLOBAL
    currentUser = (json && (json.data || json.user)) || json || {};
    
    // ---- render texto ----
    setText('profileName',   currentUser.fullname || '');
    setText('profileUser',   currentUser.username ? '@' + currentUser.username : '');
    setText('fullname',      currentUser.fullname);
    setText('username',      currentUser.username);
    setText('email',         currentUser.email);
    setText('birthday',      currentUser.birthday);
    setText('gender',        mapGenderToText(currentUser.gender));
    setText('birth_country', currentUser.birth_country);
    setText('country',       currentUser.country);

    // ---- render avatar ----
    const avatarSrc = buildAvatarSrc(currentUser);
    setImgByIds(avatarSrc, 'profileAvatar', 'avatarImg');

  } catch (err) {
    console.error('[PROFILE] Error loading user data:', err);
    alert('Error al cargar datos del usuario');
    return;
  }

  // ============================================
  // MODAL DE EDICIÓN - Event Listeners
  // ============================================

  const editModal = document.getElementById('editProfileModal');
  if (editModal) {
    // Cuando se abre el modal, rellenar con datos actuales
    editModal.addEventListener('show.bs.modal', () => {
      if (!currentUser) {
        console.error('[PROFILE] currentUser no está disponible');
        return;
      }

      setText('edit_fullname',     currentUser.fullname || '');
      setText('edit_username',     currentUser.username || '');
      setText('edit_email',        currentUser.email || '');
      setText('edit_birthdate',    currentUser.birthday || '');
      setText('edit_birthcountry', currentUser.birth_country || '');
      setText('edit_nationality',  currentUser.country || '');
      
      // Select de género
      const genderSelect = document.getElementById('edit_gender');
      if (genderSelect) {
        genderSelect.value = mapGenderToValue(currentUser.gender);
      }
      
      // Limpiar campo de password y foto
      setText('edit_password', '');
      const photoInput = document.getElementById('edit_photo');
      if (photoInput) photoInput.value = '';
    });
  }

  // ============================================
  // SUBMIT DEL FORMULARIO DE EDICIÓN
  // ============================================

  const editForm = editModal?.querySelector('form');
  if (editForm) {
    editForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const fullname = document.getElementById('edit_fullname')?.value?.trim() || '';
      const username = document.getElementById('edit_username')?.value?.trim() || '';
      const email = document.getElementById('edit_email')?.value?.trim() || '';
      const birthday = document.getElementById('edit_birthdate')?.value || '';
      const gender = document.getElementById('edit_gender')?.value || '';
      const birthcountry = document.getElementById('edit_birthcountry')?.value?.trim() || '';
      const nationality = document.getElementById('edit_nationality')?.value?.trim() || '';
      const password = document.getElementById('edit_password')?.value?.trim() || '';
      const photoInput = document.getElementById('edit_photo');

      // Validaciones básicas
      if (!fullname || !username || !email || !birthday || !gender || !birthcountry || !nationality) {
        alert('Por favor completa todos los campos requeridos.');
        return;
      }

      // Mapear género a número
      const genderMap = { 'F': 1, 'M': 2, 'O': 3 };
      const genderNum = genderMap[gender] || 3;

      // Construir payload
      const payload = {
        fullname,
        username,
        email,
        birthday,
        gender: genderNum,
        birth_country: birthcountry,
        country: nationality
      };

      // Solo incluir password si se escribió algo
      if (password) {
        // Validar password
        const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/;
        if (!pwdRegex.test(password)) {
          alert('La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, un número y un carácter especial.');
          return;
        }
        payload.password = password;
      }

      // Avatar (si se seleccionó uno nuevo)
      if (photoInput?.files?.length) {
        const file = photoInput.files[0];
        const avatarBase64 = await new Promise(resolve => {
          const reader = new FileReader();
          reader.onload = () => resolve(reader.result.split(',')[1] || null);
          reader.onerror = () => resolve(null);
          reader.readAsDataURL(file);
        });
        if (avatarBase64) payload.avatar = avatarBase64;
      }

      try {
        // Enviar actualización
        const updateRes = await fetch(BASE + 'api/users/update', {
          method: 'POST',
          credentials: 'include',
          headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        const updateRaw = await updateRes.text();
        let updateJson;
        try {
          const i0 = updateRaw.indexOf('{'), j0 = updateRaw.lastIndexOf('}');
          updateJson = JSON.parse(i0 >= 0 && j0 >= i0 ? updateRaw.slice(i0, j0+1) : updateRaw);
        } catch {
          alert('Error al procesar la respuesta del servidor');
          return;
        }

        if (!updateRes.ok || updateJson?.ok === false) {
          alert(updateJson?.error || 'Error al actualizar el perfil');
          return;
        }

        // Éxito - cerrar modal y recargar página
        alert('Perfil actualizado correctamente');
        const modal = bootstrap.Modal.getInstance(editModal);
        if (modal) modal.hide();
        window.location.reload();

      } catch (err) {
        console.error('[PROFILE] Error updating:', err);
        alert('Error al actualizar: ' + (err.message || err));
      }
    });
  }

})();