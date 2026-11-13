/* Сохраняет UTM-метки из URL в localStorage.
   Подключается в <head> ДО GTM, чтобы аналитика видела источник. */
(function () {
  'use strict';
  var params = new URLSearchParams(window.location.search);
  [
    'utm_source',
    'utm_medium',
    'utm_campaign',
    'utm_term',
    'utm_content',
  ].forEach(function (key) {
    var value = params.get(key);
    if (value && value !== '') {
      try {
        localStorage.setItem(key, value);
      } catch (e) {}
    }
  });
})();
