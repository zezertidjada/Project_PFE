/**
 * B-Project Manager — gestion centralisée du thème clair / sombre
 */
(function () {
  'use strict';

  var STORAGE_KEY = 'bp_theme';

  function apply(theme) {
    if (theme === 'dark') {
      document.documentElement.setAttribute('data-theme', 'dark');
    } else {
      document.documentElement.removeAttribute('data-theme');
    }
    localStorage.setItem(STORAGE_KEY, theme);

    document.querySelectorAll('[data-theme-label]').forEach(function (el) {
      el.textContent = theme === 'dark' ? 'Sombre' : 'Clair';
    });
  }

  function get() {
    return localStorage.getItem(STORAGE_KEY) || 'light';
  }

  function toggle() {
    var current = document.documentElement.getAttribute('data-theme');
    apply(current === 'dark' ? 'light' : 'dark');
  }

  function init() {
    apply(get());
    document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
      btn.addEventListener('click', toggle);
    });
  }

  window.BPTheme = { apply: apply, get: get, toggle: toggle, init: init };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
