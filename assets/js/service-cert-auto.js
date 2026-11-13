(function () {
  'use strict';

  var form = document.getElementById('callbackForm');
  if (!form) return;

  var I18N = window.CERT_AUTO_I18N || {};
  var FORM_NAME = form.dataset.formName || 'cert_auto';
  var THANK = form.dataset.thankyou || 'thank-you.html';

  var nameInput = document.getElementById('name');
  var phoneInput = document.getElementById('phone');
  var nameError = document.getElementById('nameError');
  var phoneError = document.getElementById('phoneError');
  var submitBtn = document.getElementById('submitBtnText');
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
  phoneInput.addEventListener('focus', function () {
    if (!this.value) this.value = '+380';
  });
  phoneInput.addEventListener('input', function () {
    var v = this.value.replace(/[^\d+]/g, '');
    if (!v.startsWith('+380')) v = '+380';
    if (v.length > 13) v = v.substring(0, 13);
    this.value = v;
  });
  phoneInput.addEventListener('blur', function () {
    if (this.value === '+380') this.value = '';
    phoneError.innerText = '';
  });

  nameInput.addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[0-9]/g, '');
    nameError.innerText = '';
  });

  function validate() {
    var ok = true;
    var nv = nameInput.value.trim();
    var pv = phoneInput.value.trim();

    if (!nv) {
      nameError.innerText = I18N.errName || '';
      ok = false;
    } else if (/[0-9]/.test(nv)) {
      nameError.innerText = I18N.errNameDigits || '';
      ok = false;
    } else {
      nameError.innerText = '';
    }

    if (!pv || pv === '+380') {
      phoneError.innerText = I18N.errPhone || '';
      ok = false;
    } else if (pv.length !== 13) {
      phoneError.innerText = I18N.errPhoneFormat || '';
      ok = false;
    } else {
      phoneError.innerText = '';
    }
    return ok;
  }

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
    if (!validate()) return;

    sending = true;
    fillUtm();

    var original = submitBtn.innerText;
    submitBtn.innerText = I18N.sending || '…';
    submitBtn.disabled = true;

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
        submitBtn.innerText = original;
        submitBtn.disabled = false;
        alert(I18N.errSendFailed || '');
      });
  });
})();
