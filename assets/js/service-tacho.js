(function () {
  'use strict';

  var FORM = document.getElementById('callbackForm');
  if (!FORM) return;

  var I18N = window.TACHO_I18N || {};
  var FORM_NAME = FORM.dataset.formName || 'tacho_service';
  var THANK = FORM.dataset.thankyou || 'thank-you.html';

  var nameInput = document.getElementById('name');
  var phoneInput = document.getElementById('phone');
  var nameError = document.getElementById('nameError');
  var phoneError = document.getElementById('phoneError');
  var errBox = document.getElementById('f-err');
  var submitBtn = document.getElementById('submitBtnText');
  var sending = false;

  /* Маска телефона */
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

  function validateForm() {
    var ok = true;
    var nv = nameInput.value.trim();
    var pv = phoneInput.value.trim();

    if (!nv) {
      nameError.innerText = I18N.eName || '';
      ok = false;
    } else if (/[0-9]/.test(nv)) {
      nameError.innerText = I18N.eDigits || '';
      ok = false;
    } else nameError.innerText = '';

    var clean = pv.replace(/[^\d+]/g, '');
    if (!pv || pv === '+380') {
      phoneError.innerText = I18N.ePhone || '';
      ok = false;
    } else if (clean.length !== 13) {
      phoneError.innerText = I18N.ePhone2 || '';
      ok = false;
    } else phoneError.innerText = '';

    return ok;
  }

  function fillUtmFields() {
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
  fillUtmFields();

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

  FORM.addEventListener('submit', function (e) {
    e.preventDefault();
    if (sending) return;
    if (!validateForm()) return;

    sending = true;
    errBox.innerText = '';
    fillUtmFields();

    var original = submitBtn.innerText;
    submitBtn.innerText = I18N.sending || '...';
    submitBtn.disabled = true;

    var fd = new FormData(FORM);
    fd.append('form_name', FORM_NAME);

    fetch(FORM.action, {
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
        errBox.innerText = I18N.fail || '';
      });
  });
})();
