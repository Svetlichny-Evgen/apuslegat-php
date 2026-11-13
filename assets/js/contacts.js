(function () {
  'use strict';

  // --- Устройство -----------------------------------------------------
  function isMobileDevice() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Windows Phone/i.test(
      navigator.userAgent
    );
  }

  // --- Открытие Telegram ---------------------------------------------
  // Пытаемся открыть desktop-приложение через tg://, при неудаче — веб-версию.
  function openTelegram(username) {
    if (!username) return;
    const deepLink = 'tg://resolve?domain=' + encodeURIComponent(username);
    const webLink = 'https://t.me/' + encodeURIComponent(username);

    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = deepLink;
    document.body.appendChild(iframe);

    let done = false;
    const cleanup = () => {
      if (done) return;
      done = true;
      clearTimeout(timer);
      if (iframe.parentNode) iframe.parentNode.removeChild(iframe);
      window.removeEventListener('blur', onBlur);
    };
    const onBlur = () => cleanup();
    window.addEventListener('blur', onBlur);

    const timer = setTimeout(() => {
      if (done) return;
      cleanup();
      window.open(webLink, '_blank', 'noopener');
    }, 2000);
  }

  // --- Телефонные ссылки ---------------------------------------------
  // На мобильных оставляем tel: (звонок).
  // На ПК (если в data-tg есть username) подменяем на открытие Telegram.
  function setupPhoneLinks() {
    if (isMobileDevice()) return;

    document.querySelectorAll('a.phone-link[data-tg]').forEach(function (link) {
      const tg = link.dataset.tg;
      if (!tg) return;

      link.href = 'javascript:void(0)';
      link.classList.add('pc-telegram-mode');

      link.addEventListener('click', function (e) {
        e.preventDefault();
        openTelegram(tg);
      });

      if (!link.querySelector('.tg-pc-icon')) {
        const icon = document.createElement('span');
        icon.className = 'tg-pc-icon';
        icon.textContent = ' ✈️';
        icon.title = 'Написати в Telegram (@' + tg + ')';
        link.appendChild(icon);
      }
      link.title = 'Написати в Telegram (@' + tg + ')';
    });
  }

  // --- Плавающая кнопка (если она есть в HTML) ------------------------
  // В текущей разметке её нет — оставлено «на будущее».
  function setupCallButton() {
    const btn = document.getElementById('callFixedButton');
    if (!btn) return;

    const tg = btn.dataset.tg;
    if (isMobileDevice() || !tg) return;

    btn.href = 'javascript:void(0)';
    btn.classList.add('pc-telegram-mode');
    btn.style.animation = 'none';
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      openTelegram(tg);
    });
    btn.title = 'Написати в Telegram (@' + tg + ')';
  }

  setupPhoneLinks();
  setupCallButton();
})();
