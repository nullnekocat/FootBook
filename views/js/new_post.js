// Solo avatar del usuario activo
export class NewPost {
  constructor(root, opts = {}) {
    if (!root) throw new Error('NewPost: root inválido');
    if (root.dataset.wired === 'true') return;
    root.dataset.wired = 'true';

    this.root = root;
    this.base = (opts.baseUrl || '/FootBook').replace(/\/+$/,'') + '/';
    this.avatar = root.querySelector('#avatarImg') || document.getElementById('avatarImg');

    this.setAvatar();
  }

  setAvatar() {
    if (!this.avatar) return;

    const avatarUrl = this.base + 'api/users/me/avatar?ts=' + Date.now();
    const fallback  = this.base + 'img/default-avatar.png';

    this.avatar.addEventListener('error', () => {
      console.warn('[NewPost] avatar failed:', this.avatar.src);
      this.avatar.src = fallback;
    });
    this.avatar.addEventListener('load', () => {
      console.log('[NewPost] avatar loaded:', this.avatar.src);
    });

    // ✅ Cargar directamente el binario del avatar
    this.avatar.src = avatarUrl;
  }
}
