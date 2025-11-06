// /views/js/new_post.js
// Solo avatar del usuario activo
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
}