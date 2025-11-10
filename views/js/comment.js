// /views/js/comment.js

export class Comments {
  constructor({ baseUrl = '/FootBook' } = {}) {
    this.baseUrl = baseUrl;
    this.apiUrl  = `${this.baseUrl}/api/comments`;
    this.$modal  = document.getElementById('commentsModal');
    this.$list   = document.getElementById('comments-list');
    this.$empty  = document.getElementById('comments-empty');

    this.$form   = document.getElementById('comment-form');
    this.$input  = document.getElementById('comment-input');
    this.$submit = this.$form?.querySelector('button[type="submit"]');

    this.currentPostId = null;

    this.bind();
  }

  bind() {
    this.$modal.addEventListener('show.bs.modal', (ev) => {
      const triggerBtn = ev.relatedTarget;
      const postId = parseInt(triggerBtn?.getAttribute('data-post-id') || '0', 10);
      this.currentPostId = Number.isFinite(postId) ? postId : 0;
      this.load();
    });

    this.$modal.addEventListener('hidden.bs.modal', () => {
      this.$list.innerHTML = '';
      this.$empty.classList.add('d-none');
      this.currentPostId = null;
      if (this.$input) this.$input.value = '';
    });

    if (this.$form) {
      this.$form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.postComment();
      });
    }
  }

    bind() {
    this.$modal.addEventListener('show.bs.modal', (ev) => {
      const triggerBtn = ev.relatedTarget;
      const postId = parseInt(triggerBtn?.getAttribute('data-post-id') || '0', 10);
      this.currentPostId = Number.isFinite(postId) ? postId : 0;
      this.load();
    });

    this.$modal.addEventListener('hidden.bs.modal', () => {
      this.$list.innerHTML = '';
      this.$empty.classList.add('d-none');
      this.currentPostId = null;
      if (this.$input) this.$input.value = '';
    });

    if (this.$form) {
      this.$form.addEventListener('submit', (e) => {
        e.preventDefault();
        this.postComment();
      });
    }
  }
   async postComment() {
    if (!this.currentPostId) return;
    const content = (this.$input?.value || '').trim();
    if (!content) return;

    // UI lock
    if (this.$submit) { this.$submit.disabled = true; }
    if (this.$input)  { this.$input.disabled  = true; }

    try {
      const res = await this.ajaxPOST(this.apiUrl, {
        post_id: this.currentPostId,
        content
      });

      const created = res?.comment;
      if (created) {
        // Si la API no trae username, al menos muestra el user_id
        const enriched = {
          ...created,
          username: created.username ?? 'Tú',
        };

        // Oculta placeholder y agrega al inicio
        this.$empty.classList.add('d-none');
        this.$list.prepend(this.renderComment(enriched));

        // Limpia input
        if (this.$input) this.$input.value = '';
      }
    } catch (err) {
      console.error('[Comments] POST error:', err);
      const msg = err?.message || 'No se pudo publicar';
      alert(msg); // reemplaza por tu sistema de toasts si lo tienes
    } finally {
      if (this.$submit) { this.$submit.disabled = false; }
      if (this.$input)  { this.$input.disabled  = false; this.$input.focus(); }
    }
  }

  ajaxPOST(url, payload) {
    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', url, true);
      xhr.withCredentials = true;
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.setRequestHeader('Accept', 'application/json');
      xhr.onreadystatechange = () => {
        if (xhr.readyState !== 4) return;
        const raw = xhr.responseText || '';
        try {
          const i = raw.indexOf('{'), j = raw.lastIndexOf('}');
          const json = JSON.parse(i >= 0 && j >= i ? raw.slice(i, j + 1) : raw);
          if (json?.ok) resolve(json);
          else reject(new Error(json?.error || 'Error API'));
        } catch {
          reject(new Error('Respuesta no-JSON del API'));
        }
      };
      xhr.onerror = () => reject(new Error('Error de red'));
      xhr.send(JSON.stringify(payload));
    });
  }

  async load() {
    this.$list.innerHTML = ''; // reset
    if (!this.currentPostId) {
      this.$empty.textContent = 'Sé el primero en comentar';
      this.$empty.classList.remove('d-none');
      return;
    }

    try {
      const url = `${this.apiUrl}?post_id=${this.currentPostId}`;
      const res = await this.ajaxGET(url);

      const rows = res?.data || [];
      if (!rows.length) {
        this.$empty.textContent = 'Sé el primero en comentar';
        this.$empty.classList.remove('d-none');
        return;
      }

      this.$empty.classList.add('d-none');
      rows.forEach(c => this.$list.appendChild(this.renderComment(c)));

    } catch (err) {
      this.$list.innerHTML = '';
      this.$empty.textContent = 'No se pudieron cargar los comentarios';
      this.$empty.classList.remove('d-none');
      console.error('[Comments] Error:', err);
    }
  }

    renderComment(c) {
    const el = document.createElement('div');
    el.className = 'd-flex align-items-center mb-3';

    const username = c.username ?? `Usuario ${c.user_id ?? ''}`.trim();
    const when     = this.formatWhen(c.created_at);
    const src      = c.avatar_b64
        ? `data:image/jpeg;base64,${c.avatar_b64}`
        : `/FootBook/img/default.jpg`;

    el.innerHTML = `
        <img src="${src}" class="rounded-circle me-2" width="36" height="36" alt="User">
        <div>
        <strong>${this.escape(username)}</strong>
        <span class="text-muted small ms-1">${this.escape(c.content ?? '')}</span>
        <div class="text-muted small">${when}</div>
        </div>
    `;
    return el;
    }


  formatWhen(iso) {
    if (!iso) return '';
    const d = new Date(iso.replace(' ', 'T'));
    if (Number.isNaN(d.getTime())) return '';
    const mins = Math.floor((Date.now() - d.getTime()) / 60000);
    if (mins < 1) return 'Justo ahora';
    if (mins < 60) return `Hace ${mins} min`;
    const hours = Math.floor(mins / 60);
    if (hours < 24) return `Hace ${hours} h`;
    const days = Math.floor(hours / 24);
    return `Hace ${days} d`;
  }

  escape(s) {
    return String(s)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  ajaxGET(url) {
    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest();
      xhr.open('GET', url, true);
      xhr.withCredentials = true;
      xhr.setRequestHeader('Accept', 'application/json');
      xhr.onreadystatechange = () => {
        if (xhr.readyState !== 4) return;
        const raw = xhr.responseText || '';
        try {
          const i = raw.indexOf('{'), j = raw.lastIndexOf('}');
          const json = JSON.parse(i >= 0 && j >= i ? raw.slice(i, j + 1) : raw);
          if (json?.ok) resolve(json);
          else reject(new Error(json?.error || 'Error API'));
        } catch {
          reject(new Error('Respuesta no-JSON del API'));
        }
      };
      xhr.onerror = () => reject(new Error('Error de red'));
      xhr.send();
    });
  }
}
