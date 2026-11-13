(function () {
  'use strict';

  var form = document.getElementById('extra-form');
  if (!form) return;

  var I18N = window.EXTRA_I18N || {};
  var FORM_NAME = form.dataset.formName || 'service_extra';
  var THANK = form.dataset.thankyou || 'thank-you.html';

  var nameInput = document.getElementById('f-name');
  var phoneInput = document.getElementById('f-phone');
  var fErr = document.getElementById('f-err');
  var fBtn = document.getElementById('f-btn');
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

  // фильтр цифр в имени (как в оригинале)
  nameInput.addEventListener('input', function () {
    this.value = this.value.replace(/[0-9]/g, '');
  });

  // маска телефона
  phoneInput.addEventListener('focus', function () {
    if (!this.value) this.value = '+380';
  });
  phoneInput.addEventListener('input', function () {
    var v = this.value.replace(/[^\d+]/g, '');
    if (!v.startsWith('+380')) v = '+380';
    if (v.length > 13) v = v.substring(0, 13);
    this.value = v;
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

    var cleanPhone = phoneInput.value.replace(/\D/g, '');
    if (cleanPhone.length < 12) {
      fErr.textContent = I18N.errPhone || '';
      phoneInput.focus();
      return;
    }

    fErr.textContent = '';
    sending = true;
    fillUtm();

    var original = fBtn.innerText;
    fBtn.disabled = true;
    fBtn.innerText = I18N.sending || '…';

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
        fBtn.disabled = false;
        fBtn.innerText = original;
        fErr.textContent = I18N.errSend || '';
      });
  });
})();
