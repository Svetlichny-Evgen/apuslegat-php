(function () {
  'use strict';

  var form = document.getElementById('infra-form');
  if (!form) return;

  var I18N = window.PARKING_I18N || {};
  var FORM_NAME = form.dataset.formName || 'parking';
  var THANK = form.dataset.thankyou || 'thank-you.html';

  var phoneInput = document.getElementById('user-phone');
  var nameInput = document.getElementById('user-name');
  var btnSend = document.getElementById('btn-send');
  var fErr = document.getElementById('f-err');
  var sending = false;

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

  // маска телефона
  phoneInput.addEventListener('input', function (e) {
    var v = e.target.value.replace(/[^\d+]/g, '');
    if (v.length > 0 && v.indexOf('+380') !== 0) v = '+380';
    if (v.length > 13) v = v.substring(0, 13);
    e.target.value = v;
  });

  // фильтр цифр в имени (как в оригинале)
  nameInput.addEventListener('input', function () {
    this.value = this.value.replace(/[0-9]/g, '');
  });

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
    setTimeout(go, 500);
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (sending) return;

    var clean = phoneInput.value.replace(/\D/g, '');
    if (clean.length < 12) {
      fErr.textContent = I18N.errPhone || '';
      phoneInput.focus();
      return;
    }

    fErr.textContent = '';
    sending = true;
    fillUtm();

    var original = btnSend.innerText;
    btnSend.disabled = true;
    btnSend.innerText = I18N.sending || '…';

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
      .catch(function (err) {
        console.error('Form submit error:', err);
        sending = false;
        btnSend.disabled = false;
        btnSend.innerText = original;
        fErr.textContent = I18N.errSend || '';
      });
  });
})();
