(function () {
  'use strict';

  var I18N = window.HEATERS_I18N || {};
  var FORM_NAME = 'heaters_install';
  var THANK = window.HEATERS_THANKYOU || 'thank-you.html';

  var form = document.getElementById('heaterForm');
  if (!form) return;

  var nameInput = document.getElementById('formName');
  var phoneInput = document.getElementById('formPhone');
  var nameError = document.getElementById('nameError');
  var phoneError = document.getElementById('phoneError');
  var formOk = document.getElementById('formOk');
  var formErr = document.getElementById('formErr');
  var submitBtn = document.getElementById('formSubmit');
  var serviceSel = form.querySelector('[name=service]');
  var sending = false;

  /* Клик по строке прайса → выбрать услугу в селекте */
  document.querySelectorAll('[data-service]').forEach(function (row) {
    row.addEventListener('click', function () {
      if (serviceSel) serviceSel.value = row.dataset.service;
    });
  });

  /* Имя: только буквы, пробел, дефис, апостроф */
  nameInput.addEventListener('input', function () {
    this.value = this.value.replace(/[^a-zA-Zа-яА-ЯїЇєЄґҐіІ\s\-']/g, '');
    if (this.value.trim().length >= 2) {
      this.classList.remove('error');
      nameError.classList.remove('show');
    }
  });

  /* Телефон: только цифры и «+», всегда +380… */
  phoneInput.addEventListener('input', function () {
    var v = this.value.replace(/[^\d+]/g, '');
    if (v.indexOf('+') > 0) {
      v =
        v.charAt(0) === '+' ? '+' + v.replace(/\+/g, '') : v.replace(/\+/g, '');
    }
    if (v && v.charAt(0) !== '+') {
      v = '+380' + v.replace(/\D/g, '');
    }
    if (v.length > 13) v = v.slice(0, 13);
    this.value = v;

    var digits = v.replace(/\D/g, '');
    if (digits.length >= 12) {
      this.classList.remove('error');
      phoneError.classList.remove('show');
    }
  });

  phoneInput.addEventListener('focus', function () {
    if (this.value.trim() === '') this.value = '+380';
  });

  phoneInput.addEventListener('keydown', function (e) {
    var allowed = [
      'Backspace',
      'Delete',
      'Tab',
      'Escape',
      'Enter',
      'ArrowLeft',
      'ArrowRight',
      'ArrowUp',
      'ArrowDown',
      'Home',
      'End',
    ];
    if (allowed.indexOf(e.key) !== -1) return;
    if (
      e.ctrlKey &&
      ['v', 'V', 'c', 'C', 'a', 'A', 'x', 'X'].indexOf(e.key) !== -1
    )
      return;
    if (!/^[\d+]$/.test(e.key)) e.preventDefault();
  });

  function fillUtm() {
    /* UTM уже в localStorage — они придут с полями формы; ничего не делаем */
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
    formOk.style.display = 'none';
    formErr.style.display = 'none';

    var valid = true;

    var name = nameInput.value.trim();
    if (name.length < 2) {
      nameError.textContent = I18N.errNameEmpty || '';
      nameError.classList.add('show');
      nameInput.classList.add('error');
      valid = false;
    } else if (!/^[a-zA-Zа-яА-ЯїЇєЄґҐіІ\s\-']+$/.test(name)) {
      nameError.textContent = I18N.errNameChars || '';
      nameError.classList.add('show');
      nameInput.classList.add('error');
      valid = false;
    }

    var phone = phoneInput.value.trim();
    if (!/^\+380\d{9}$/.test(phone)) {
      phoneError.textContent = I18N.errPhone || '';
      phoneError.classList.add('show');
      phoneInput.classList.add('error');
      valid = false;
    }

    if (!valid) return;

    sending = true;
    var originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = I18N.sending || '…';

    var formData = {
      form_name: FORM_NAME,
      service: serviceSel ? serviceSel.value : '',
      name: name,
      phone: phone,
      utm_source: localStorage.getItem('utm_source') || '',
      utm_medium: localStorage.getItem('utm_medium') || '',
      utm_campaign: localStorage.getItem('utm_campaign') || '',
      utm_term: localStorage.getItem('utm_term') || '',
      utm_content: localStorage.getItem('utm_content') || '',
    };

    fetch(form.action, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: new URLSearchParams(formData),
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
        console.error('Send error:', err);
        sending = false;
        formErr.style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      });
  });
})();
