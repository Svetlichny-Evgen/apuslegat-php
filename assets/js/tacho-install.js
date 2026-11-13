(function () {
  'use strict';

  var CFG = window.TACHO_INSTALL_CONFIG || {};
  var DATA = window.TACHO_INSTALL_DATA || { prices: {} };
  var I18N = window.TACHO_INSTALL_I18N || {};

  var endpoint = CFG.endpoint || 'php/telegram.php';
  var thankyou = CFG.thankyou || 'thank-you.html';
  var prices = DATA.prices || {};

  /* ---------- dataLayer + redirect ---------- */
  function goToThankYou(formName) {
    var done = false;
    var go = function () {
      if (done) return;
      done = true;
      window.location.href = thankyou;
    };
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      event: 'form_submit',
      form_name: formName,
      eventCallback: go,
      eventTimeout: 500,
    });
    setTimeout(go, 500);
  }

  /* ---------- калькулятор ---------- */
  var brandSelect = document.getElementById('taho-brand');
  var voltageRadios = document.querySelectorAll('input[name="taho-voltage"]');
  var totalPrice = document.getElementById('taho-total-price');
  var orderBtn = document.getElementById('taho-order-btn');
  if (!brandSelect || !totalPrice || !orderBtn) return;

  function updatePrice() {
    var brand = brandSelect.value;
    var voltage = null;
    voltageRadios.forEach(function (r) {
      if (r.checked) voltage = r.value;
    });

    totalPrice.classList.remove('not-supported', 'hint');
    orderBtn.disabled = true;
    delete orderBtn.dataset.brand;
    delete orderBtn.dataset.voltage;
    delete orderBtn.dataset.price;

    if (!brand) {
      totalPrice.textContent = I18N.selectBrandHint || '';
      totalPrice.classList.add('hint');
      return;
    }
    if (voltage === null) {
      totalPrice.textContent = I18N.selectVoltageHint || '';
      totalPrice.classList.add('hint');
      return;
    }

    var map = prices[brand] || {};
    if (map[voltage] !== undefined) {
      var price = map[voltage];
      var formatted = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
      totalPrice.textContent = formatted + ' грн';
      orderBtn.disabled = false;
      orderBtn.dataset.brand = brand;
      orderBtn.dataset.voltage = voltage + 'V';
      orderBtn.dataset.price = formatted + ' грн';
      return;
    }

    totalPrice.textContent = I18N.notSupported || '—';
    totalPrice.classList.add('not-supported');
  }

  brandSelect.addEventListener('change', updatePrice);
  voltageRadios.forEach(function (r) {
    r.addEventListener('change', updatePrice);
  });

  /* ---------- модалка ---------- */
  var modal = document.getElementById('consultModal');
  var modalName = document.getElementById('modalName');
  var modalPhone = document.getElementById('modalPhone');
  var nameError = document.getElementById('modalNameError');
  var phoneError = document.getElementById('modalPhoneError');
  var modalSubmit = document.getElementById('modalSubmitBtn');
  var smartBtn = document.getElementById('smartOrderBtn');

  function clearModalErrors() {
    nameError.classList.remove('show');
    phoneError.classList.remove('show');
    modalName.classList.remove('error');
    modalPhone.classList.remove('error');
  }
  function openModal(kind) {
    clearModalErrors();
    if (kind === 'calculator') {
      modal.dataset.brand = orderBtn.dataset.brand;
      modal.dataset.voltage = orderBtn.dataset.voltage;
      modal.dataset.price = orderBtn.dataset.price;
    } else {
      delete modal.dataset.brand;
      delete modal.dataset.voltage;
      delete modal.dataset.price;
    }
    if (!modalPhone.value.trim()) modalPhone.value = '+380';
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
  }
  function closeModal() {
    modal.classList.remove('show');
    document.body.style.overflow = '';
  }
  window.closeModal = closeModal;

  orderBtn.addEventListener('click', function () {
    if (!this.dataset.brand || !this.dataset.voltage) return;
    openModal('calculator');
  });
  if (smartBtn)
    smartBtn.addEventListener('click', function () {
      openModal('smart');
    });

  document.querySelector('.modal-close').addEventListener('click', closeModal);
  modal.addEventListener('click', function (e) {
    if (e.target === modal) closeModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal.classList.contains('show')) closeModal();
  });

  /* ---------- маски полей ---------- */
  modalName.addEventListener('input', function () {
    this.value = this.value
      .replace(/[0-9]/g, '')
      .replace(/[^a-zA-Zа-яА-ЯёЁїЇєЄґҐіІ\s\-'’ʼ]/g, '');
  });
  modalPhone.addEventListener('input', function () {
    var d = this.value.replace(/\D/g, '');
    if (!d.length) {
      this.value = '';
      return;
    }
    if (!d.startsWith('380')) {
      if (d.length <= 2 && '38'.startsWith(d)) d = '380';
      else if (d.startsWith('0')) d = '38' + d;
      else d = '380' + d;
    }
    this.value = '+' + d.slice(0, 12);
  });
  modalPhone.addEventListener('focus', function () {
    if (!this.value.trim()) this.value = '+380';
  });

  /* ---------- отправка ---------- */
  function sendOrder(payload) {
    return fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: new URLSearchParams(payload),
    }).then(function (res) {
      if (!res.ok) throw new Error('HTTP ' + res.status);
      return res.json();
    });
  }

  modalSubmit.addEventListener('click', function () {
    var name = modalName.value.trim();
    var phone = modalPhone.value.trim();

    clearModalErrors();
    var ok = true;

    if (!name || name.length < 2) {
      nameError.textContent = I18N.errNameShort || '';
      nameError.classList.add('show');
      modalName.classList.add('error');
      ok = false;
    } else if (!/^[a-zA-Zа-яА-ЯёЁїЇєЄґҐіІ\s\-'’ʼ]+$/.test(name)) {
      nameError.textContent = I18N.errNameChars || '';
      nameError.classList.add('show');
      modalName.classList.add('error');
      ok = false;
    }

    var cleaned = phone.replace(/[^0-9+]/g, '');
    if (!phone || cleaned.length < 10) {
      phoneError.textContent = I18N.errPhoneEmpty || '';
      phoneError.classList.add('show');
      modalPhone.classList.add('error');
      ok = false;
    } else if (!/^\+380\d{9}$/.test(cleaned)) {
      phoneError.textContent = I18N.errPhoneFormat || '';
      phoneError.classList.add('show');
      modalPhone.classList.add('error');
      ok = false;
    }

    if (!ok) return;

    var isCalculator = !!modal.dataset.brand;
    var formName = isCalculator ? 'tacho_install' : 'tacho_install_smart';

    var original = modalSubmit.textContent;
    modalSubmit.disabled = true;
    modalSubmit.classList.add('loading');
    modalSubmit.textContent = I18N.sending || '…';

    sendOrder({
      form_name: formName,
      name: name,
      phone: cleaned,
      calculator_brand: modal.dataset.brand || '',
      calculator_voltage: modal.dataset.voltage || '',
      calculator_price: modal.dataset.price || '',
      utm_source: localStorage.getItem('utm_source') || '',
      utm_medium: localStorage.getItem('utm_medium') || '',
      utm_campaign: localStorage.getItem('utm_campaign') || '',
      utm_term: localStorage.getItem('utm_term') || '',
      utm_content: localStorage.getItem('utm_content') || '',
    })
      .then(function (data) {
        if (!data || data.status !== 'success')
          throw new Error((data && data.message) || 'send failed');
        closeModal();
        modalName.value = '';
        modalPhone.value = '+380';
        goToThankYou(formName);
      })
      .catch(function (err) {
        console.error('Send order error:', err);
        modalSubmit.disabled = false;
        modalSubmit.classList.remove('loading');
        modalSubmit.textContent = original;
        alert(I18N.errSendFailed || '');
      });
  });

  /* ---------- bfcache ---------- */
  window.addEventListener('pageshow', function (e) {
    if (modalSubmit) {
      modalSubmit.disabled = false;
      modalSubmit.classList.remove('loading');
      modalSubmit.textContent = I18N.modalSubmitBtn || 'Надіслати';
    }
    if (e.persisted) closeModal();
  });
})();
