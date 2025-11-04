
(() => {
  'use strict';

  const THEME_KEY = 'footbook-theme';

  function getSavedTheme() {
    return localStorage.getItem(THEME_KEY) || 'light';
  }

  function saveTheme(theme) {
    localStorage.setItem(THEME_KEY, theme);
  }

  function applyTheme(theme) {
    document.documentElement.setAttribute('data-bs-theme', theme);
    document.body.setAttribute('data-theme', theme);
    
    updateThemeIcon(theme);
  }

  function updateThemeIcon(theme) {
    const themeItems = document.querySelectorAll('[data-theme-value]');
    themeItems.forEach(item => {
      if (item.dataset.themeValue === theme) {
        item.classList.add('active');
      } else {
        item.classList.remove('active');
      }
    });
  }

  function initTheme() {
    const savedTheme = getSavedTheme();
    applyTheme(savedTheme);
  }

  function handleThemeChange(e) {
    e.preventDefault();
    const newTheme = e.target.closest('[data-theme-value]').dataset.themeValue;
    
    if (newTheme) {
      saveTheme(newTheme);
      applyTheme(newTheme);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTheme);
  } else {
    initTheme();
  }

  document.addEventListener('click', (e) => {
    if (e.target.closest('[data-theme-value]')) {
      handleThemeChange(e);
    }
  });
})();