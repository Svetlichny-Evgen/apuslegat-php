(function () {
  'use strict';

  var I18N = window.LOGISTICS_I18N || {};
  var CONF = window.LOGISTICS_CONF || {};
  var FORM_NAME = CONF.formName || 'logistics';
  var THANK = CONF.thankyou || 'thank-you.html';
  var WA_PHONE = CONF.waPhone || '380730779009';

  /* ===== Select2 init ===== */
  if (window.jQuery) {
    window.jQuery('#from, #to').select2({
      placeholder: I18N.placeholder || '—',
      allowClear: true,
      width: '100%',
    });
  }

  /* ===== Калькулятор ===== */
  var btnCalc = document.getElementById('btn-calc-text');
  if (btnCalc) {
    btnCalc.addEventListener('click', function () {
      var $ = window.jQuery;
      if (!$) return;
      var f = $('#from').val();
      var t = $('#to').val();
      if (!f || !t || f === t) return;

      var c1 = f.split(',');
      var c2 = t.split(',');
      var dist = Math.round(
        Math.sqrt(Math.pow(c1[0] - c2[0], 2) + Math.pow(c1[1] - c2[1], 2)) *
          111 *
          1.3
      );

      $('#total').text((dist * 55).toLocaleString());
      $('#km').text(dist + ' ' + (I18N.kmLabel || 'km'));
      $('#res-block').fadeIn();

      var fromText = $('#from option:selected').text();
      var toText = $('#to option:selected').text();
      var waUrl =
        'https://wa.me/' +
        WA_PHONE +
        '?text=' +
        encodeURIComponent(
          (I18N.waPrefix || '') + ' ' + fromText + ' - ' + toText
        );
      $('#wa').attr('href', waUrl);
    });
  }

  /* ===== Форма ===== */
  var form = document.getElementById('callbackForm');
  if (!form) return;

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
