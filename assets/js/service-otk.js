(function () {
  'use strict';

  // --- UTM в скрытые поля ---------------------------------------------
  function fillUtm() {
    [
      'utm_source',
      'utm_medium',
      'utm_campaign',
      'utm_term',
      'utm_content',
    ].forEach(function (k) {
      var el = document.getElementById(k);
      if (el) el.value = localStorage.getItem(k) || '—';
    });
  }
  fillUtm();

  // --- Маска телефона ------------------------------------------------
  var phone = document.getElementById('f-phone');
  if (phone) {
    phone.addEventListener('input', function () {
      var v = this.value.replace(/[^\d+]/g, '');
      if (!v.startsWith('+380')) v = '+380';
      this.value = v.substring(0, 13);
    });
  }

  // --- Форма ---------------------------------------------------------
  var form = document.getElementById('otk-form');
  var btn = document.getElementById('f-btn');
  var err = document.getElementById('f-err');
  if (!form || !btn || !err) return;

  var I18N = window.OTK_I18N || { sending: '…', fail: '…' };
  var THANK = form.dataset.thankyou || 'thank-you.html';
  var FORM_NAME = form.dataset.formName || 'otk';
  var sending = false;

  // Пуш dataLayer + редирект с защитой от двойного срабатывания
  function goToThankYou() {
    var done = false;
    var go = function () {
      if (done) return;
      done = true;
      window.location.href = THANK;
    };

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      event: 'form_submit',
      form_name: FORM_NAME,
      eventCallback: go,
      eventTimeout: 500,
    });

    // Fallback на случай GTM-блокировщика
    setTimeout(go, 500);
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (sending) return;
    sending = true;
    err.textContent = '';
    fillUtm();

    var original = btn.innerText;
    btn.disabled = true;
    btn.innerText = I18N.sending;

    var fd = new FormData(form);
    fd.append('form_name', FORM_NAME);

    fetch(form.action, {
      method: 'POST',
      body: fd,
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
      .then(function (res) {
        return res.json().then(function (data) {
          if (!res.ok || data.status !== 'success') {
            throw new Error(data.message || 'HTTP ' + res.status);
          }
          goToThankYou();
        });
      })
      .catch(function (e) {
        console.error('Form submit error:', e);
        sending = false;
        btn.disabled = false;
        btn.innerText = original;
        err.textContent = I18N.fail;
      });
  });
})();
