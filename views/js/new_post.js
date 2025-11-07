// /views/js/new_post.js
export class NewPost {
  constructor(root, opts = {}) {
    if (!root) throw new Error('NewPost: root inválido');
    if (root.dataset.wired === 'true') return;
    root.dataset.wired = 'true';

    this.root   = root;
    this.base   = (opts.baseUrl || '/FootBook').replace(/\/+$/, '') + '/';
    this.img    = document.getElementById('avatarImg');
    this.fallback = this.base + 'img/default.jpg';

    this.init();
  }

  async init() {
    if (!this.img) return;

    this.img.addEventListener('error', () => {
      if (this.img.src !== this.fallback) this.img.src = this.fallback;
    });

    try {
      const me = await this.fetchMe();
      const src = this.buildAvatarSrc(me);
      this.img.src = src;
    } catch (e) {
      console.warn('[NewPost] no se pudo cargar avatar:', e);
      this.img.src = this.fallback;
    }

    this.loadData(); // cargar categorías y mundiales
    this.setupFormListener(); // preparar envío del formulario
  }

  async fetchMe() {
    const url = this.base + 'api/users/me?ts=' + Date.now();
    const res = await fetch(url, {
      method: 'GET',
      credentials: 'include',
      cache: 'no-store',
      headers: { 'Accept': 'application/json' }
    });
    const txt = await res.text();
    let json;
    try {
      const i0 = txt.indexOf('{'), j0 = txt.lastIndexOf('}');
      json = JSON.parse(i0 >= 0 && j0 >= i0 ? txt.slice(i0, j0 + 1) : txt);
    } catch {
      throw new Error('Respuesta no-JSON del API');
    }
    if (!res.ok || json?.ok === false) {
      throw new Error(json?.error || ('HTTP ' + res.status));
    }
    // Acepta {ok:true,data:{...}} o {user:{...}} o plano
    return (json.data || json.user || json);
  }

  // Detecta MIME por prefijo base64: jpg/png/webp/gif
  guessMimeFromB64(b64 = '') {
    if (b64.startsWith('/9j/'))   return 'image/jpeg'; // JPG
    if (b64.startsWith('iVBOR'))  return 'image/png';  // PNG
    if (b64.startsWith('UklGR'))  return 'image/webp'; // WEBP
    if (b64.startsWith('R0lGO'))  return 'image/gif';  // GIF
    return 'image/jpeg';
  }

  buildAvatarSrc(u = {}) {
    if (u.avatar_url) return u.avatar_url;

    const b64 = u.avatar_b64 || u.avatarB64 || u.avatar;
    if (b64 && b64.length > 10) {
      const mime = this.guessMimeFromB64(b64);
      return `data:${mime};base64,${b64}`;
    }
    return this.fallback;
  }
  /* ---------------------------- Metodos para Cargar Datos ------------------------- */
  async loadData() {
    await this.loadCategories();
    await this.loadWorldCups();
  }

  async loadCategories() {
    const select = document.getElementById('post-category');
    if (!select) return;

    // estado de carga
    this.setSelectLoading(select, 'Cargando categorías…');

    try {
      const url = this.base + 'api/categories?ts=' + Date.now();
      const res = await fetch(url, {
        method: 'GET',
        credentials: 'include',
        cache: 'no-store',
        headers: { 'Accept': 'application/json' }
      });

      const txt = await res.text();
      let json;
      try {
        const i0 = txt.indexOf('{'), j0 = txt.lastIndexOf('}');
        json = JSON.parse(i0 >= 0 && j0 >= i0 ? txt.slice(i0, j0 + 1) : txt);
      } catch {
        throw new Error('Respuesta no-JSON del API de categorías');
      }

      if (!res.ok || json?.ok === false) {
        throw new Error(json?.error || ('HTTP ' + res.status));
      }

      const list = Array.isArray(json?.data) ? json.data : [];
      this.fillCategories(select, list);
    } catch (err) {
      console.error('[NewPost] Categorías:', err);
      this.setSelectError(select, 'No se pudieron cargar categorías');
    }
  }

  fillCategories(select, items) {
    if (!Array.isArray(items) || !items.length) {
      select.innerHTML = `<option value="">No hay categorías</option>`;
      select.disabled = true;
      return;
    }
    // pintar opciones
    select.innerHTML = [
      `<option value="">Selecciona una categoría</option>`,
      ...items.map(c =>
        `<option value="${this.e(c.id)}">${this.e(c.name ?? c.category ?? '')}</option>`
      )
    ].join('');
    select.disabled = false;
  }

  setSelectLoading(select, label) {
    select.innerHTML = `<option value="">${this.e(label || 'Cargando…')}</option>`;
    select.disabled = true;
  }

  setSelectError(select, label) {
    select.innerHTML = `<option value="">${this.e(label || 'Error al cargar')}</option>`;
    select.disabled = true;
  }

  e(s = '') {
    return String(s)
      .replaceAll('&','&amp;').replaceAll('<','&lt;')
      .replaceAll('>','&gt;').replaceAll('"','&quot;')
      .replaceAll("'",'&#039;');
  }

  async loadWorldCups() {
    const select = document.getElementById('post-worldcup');
    if (!select) return;

    // estado de carga
    this.setSelectLoading(select, 'Cargando mundiales…');

    try {
      const url = this.base + 'api/worldcups/light?ts=' + Date.now();
      const res = await fetch(url, {
        method: 'GET',
        credentials: 'include',
        cache: 'no-store',
        headers: { 'Accept': 'application/json' }
      });

      const txt = await res.text();
      let json;
      try {
        const i0 = txt.indexOf('{'), j0 = txt.lastIndexOf('}');
        json = JSON.parse(i0 >= 0 && j0 >= i0 ? txt.slice(i0, j0 + 1) : txt);
      } catch {
        throw new Error('Respuesta no-JSON del API de mundiales');
      }

      if (!res.ok || json?.ok === false) {
        throw new Error(json?.error || ('HTTP ' + res.status));
      }

      const list = Array.isArray(json?.data) ? json.data : [];
      this.fillWorldCups(select, list);

    } catch (err) {
      console.error('[NewPost] Mundiales:', err);
      this.setSelectError(select, 'No se pudieron cargar mundiales');
    }
  }

  fillWorldCups(select, items) {
    if (!Array.isArray(items) || !items.length) {
      select.innerHTML = `<option value="">No hay mundiales</option>`;
      select.disabled = true;
      return;
    }

    // Texto: "Nombre del mundial + año"
    select.innerHTML = [
      `<option value="">Selecciona un mundial</option>`,
      ...items.map(wc => {
        const id = this.e(wc.id);
        const name = this.e(wc.name ?? '');
        return `<option value="${id}">${name}</option>`;
      })
    ].join('');

    select.disabled = false;
  }
  /* ---------------------------- Metodos para Publicar ------------------------- */
  setupFormListener() {
    const form = document.querySelector('#modal-post form');
    if (!form) return;

    form.addEventListener('submit', async (ev) => {
      ev.preventDefault();
      try {
        const payload = await this.collectPostData();
        await this.sendNewPost(payload);
        alert('Publicación creada correctamente.');
        form.reset();
        const modal = bootstrap.Modal.getInstance(document.getElementById('modal-post'));
        if (modal) modal.hide();
      } catch (err) {
        console.error('[NewPost] Error al publicar:', err);
        alert(err.message || 'Error al crear la publicación');
      }
    });
  }

  async collectPostData() {
    const categoryId = document.getElementById('post-category')?.value || '';
    const worldcupId = document.getElementById('post-worldcup')?.value || '';
    const team = document.getElementById('seleccion')?.value?.trim() || null;
    const description = document.getElementById('contenido')?.value?.trim() || '';
    const fileInput = document.getElementById('media');
    const title = document.getElementById('titulo')?.value?.trim() || '';
    let media = null;

    if (fileInput && fileInput.files && fileInput.files[0]) {
      const file = fileInput.files[0];
      media = await this.fileToBase64(file);
    }

    return {
      category_id: parseInt(categoryId, 10) || null,
      worldcup_id: parseInt(worldcupId, 10) || null,
      team,
      title,
      description,
      media
    };
  }

  async fileToBase64(file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = () => {
        const result = reader.result;
        // Limpiar header data:URL si existe
        const base64 = result.replace(/^data:(.*?);base64,/, '');
        resolve(base64);
      };
      reader.onerror = reject;
      reader.readAsDataURL(file);
    });
  }

  async sendNewPost(payload) {
    const url = this.base + 'api/posts';
    const res = await fetch(url, {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(payload)
    });

    const txt = await res.text();
    let json;
    try {
      const i0 = txt.indexOf('{'), j0 = txt.lastIndexOf('}');
      json = JSON.parse(i0 >= 0 && j0 >= i0 ? txt.slice(i0, j0 + 1) : txt);
    } catch {
      throw new Error('Respuesta no-JSON del API');
    }

    if (!res.ok || json?.ok === false) {
      throw new Error(json?.error || ('HTTP ' + res.status));
    }

    return json;
  }

}