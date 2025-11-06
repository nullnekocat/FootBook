import { NewPost } from './new_post.js';

document.addEventListener('DOMContentLoaded', () => {
  const baseUrl = '/FootBook';
  document.querySelectorAll('[data-new-post]').forEach((node) => {
    new NewPost(node, { baseUrl });
  });
});
