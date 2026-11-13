(function () {
  'use strict';

  var CFG = window.CART_CFG || {};
  var PRODUCTS = CFG.products || [];
  var I18N = CFG.i18n || {};
  var FORM_NAME = CFG.formName || 'cart_order';
  var THANK = CFG.thankyou || 'thank-you.html';

  /* Быстрый lookup по id */
  var PRODUCTS_MAP = new Map();
  PRODUCTS.forEach(function (p) {
    if (p && p.id) PRODUCTS_MAP.set(String(p.id), p);
  });

  /* ================================================================
   * КОРЗИНА
   * ================================================================ */
  function getCart() {
    try {
      var raw = JSON.parse(localStorage.getItem('cart') || '[]');
      if (!Array.isArray(raw)) return [];
      return raw
        .map(function (item) {
          if (typeof item === 'string' || typeof item === 'number') {
            return { productId: String(item), quantity: 1 };
          }
          if (item && typeof item === 'object') {
            return Object.assign({}, item, {
              quantity: Number(item.quantity) || 1,
            });
          }
          return null;
        })
        .filter(Boolean);
    } catch (e) {
      return [];
    }
  }
  function saveCart(cart) {
    try {
      localStorage.setItem('cart', JSON.stringify(cart));
    } catch (e) {}
  }
  function updateCartBadge() {
    var badge = document.getElementById('cart-count');
    if (!badge) return;
    var total = getCart().reduce(function (s, i) {
      return s + (i.quantity || 1);
    }, 0);
    badge.textContent = total;
  }

  /* ================================================================
   * LOOKUP товара для item
   * ================================================================ */
  function findProduct(item) {
    if (!item) return null;
    var keys = [item.variationId, item.productId, item.id, item.sku];
    for (var i = 0; i < keys.length; i++) {
      if (keys[i] && PRODUCTS_MAP.has(String(keys[i]))) {
        return PRODUCTS_MAP.get(String(keys[i]));
      }
    }
    return null;
  }

  function getItemName(item, product) {
    if (item.name) return item.name;
    if (product && product.name) {
      return product.name[CFG.lang] || product.name.uk || '';
    }
    return 'Товар';
  }

  function getItemImage(item, product) {
    return (
      item.image ||
      (product && product.image_url) ||
      window.CART_NO_PHOTO ||
      'img/public/no-photo.png'
    );
  }

  function getItemPrice(item, product) {
    var p = Number(item.price);
    if (p > 0) return p;
    if (product && product.price > 0) return Number(product.price);
    return 0;
  }

  /* ================================================================
   * РЕНДЕР
   * ================================================================ */
  function escapeHtml(s) {
    return String(s == null ? '' : s).replace(/[&<>]/g, function (c) {
      return c === '&' ? '&amp;' : c === '<' ? '&lt;' : '&gt;';
    });
  }
  function formatPrice(n) {
    if (!n) return I18N.priceAsk || '—';
    return (
      String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') +
      ' ' +
      (I18N.currency || 'грн')
    );
  }

  function render() {
    var cart = getCart();
    var content = document.getElementById('cart-content');
    var empty = document.getElementById('empty-view');
    var list = document.getElementById('items-list');
    if (!content || !empty || !list) return;

    if (!cart.length) {
      content.style.display = 'none';
      empty.style.display = 'block';
      updateCartBadge();
      return;
    }

    content.style.display = 'grid';
    empty.style.display = 'none';
    list.innerHTML = '';

    var total = 0;
    var count = 0;

    cart.forEach(function (item, idx) {
      var product = findProduct(item);
      var name = getItemName(item, product);
      var image = getItemImage(item, product);
      var price = getItemPrice(item, product);
      var qty = item.quantity || 1;
      var subtotal = price * qty;
      total += subtotal;
      count += qty;

      var variation = '';
      if (item.tachoModel)
        variation += (I18N.tachograph || 'Тахограф') + ': ' + item.tachoModel;
      if (item.voltage) variation += (variation ? ', ' : '') + item.voltage;

      var html =
        '' +
        '<div class="cart-item" data-index="' +
        idx +
        '">' +
        '<div class="item-img">' +
        '<img src="' +
        escapeHtml(image) +
        '" alt="' +
        escapeHtml(name) +
        '" ' +
        'onerror="this.onerror=null;this.src=\'' +
        escapeHtml(window.CART_NO_PHOTO || '') +
        '\'">' +
        '</div>' +
        '<div class="item-info">' +
        '<h3>' +
        escapeHtml(name) +
        '</h3>' +
        (variation
          ? '<div class="item-variation">' + escapeHtml(variation) + '</div>'
          : '') +
        (item.sku
          ? '<div class="item-sku">SKU: ' + escapeHtml(item.sku) + '</div>'
          : '') +
        '</div>' +
        '<div class="item-price">' +
        formatPrice(price) +
        '</div>' +
        '<div style="display:flex;align-items:center;gap:10px">' +
        '<div class="item-quantity">' +
        '<button type="button" data-act="dec" data-idx="' +
        idx +
        '">−</button>' +
        '<span>' +
        qty +
        '</span>' +
        '<button type="button" data-act="inc" data-idx="' +
        idx +
        '">+</button>' +
        '</div>' +
        '<button type="button" class="remove-btn" data-act="del" data-idx="' +
        idx +
        '">×</button>' +
        '</div>' +
        '</div>';

      list.insertAdjacentHTML('beforeend', html);
    });

    var sumCount = document.getElementById('sum-count');
    var totalEl = document.getElementById('total-price');
    if (sumCount) sumCount.textContent = count;
    if (totalEl) totalEl.textContent = formatPrice(total);

    updateCartBadge();
  }

  /* ================================================================
   * Обработчики +/−/×  (делегирование)
   * ================================================================ */
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-act]');
    if (!btn) return;
    var act = btn.dataset.act;
    var idx = parseInt(btn.dataset.idx, 10);
    if (isNaN(idx)) return;

    var cart = getCart();
    if (!cart[idx]) return;

    if (act === 'inc') {
      cart[idx].quantity = (cart[idx].quantity || 1) + 1;
    } else if (act === 'dec') {
      cart[idx].quantity = (cart[idx].quantity || 1) - 1;
      if (cart[idx].quantity <= 0) cart.splice(idx, 1);
    } else if (act === 'del') {
      cart.splice(idx, 1);
    } else {
      return;
    }

    saveCart(cart);
    render();
  });

  /* ================================================================
   * Маска телефона + фильтр имени
   * ================================================================ */
  var nameInp = document.getElementById('inp-name');
  var phoneInp = document.getElementById('inp-phone');

  if (nameInp) {
    nameInp.addEventListener('input', function () {
      this.value = this.value.replace(/[0-9]/g, '');
    });
  }
  if (phoneInp) {
    phoneInp.addEventListener('focus', function () {
      if (!this.value) this.value = '+380';
    });
    phoneInp.addEventListener('input', function () {
      var v = this.value.replace(/[^\d+]/g, '');
      if (!v.startsWith('+380')) v = '+380';
      if (v.length > 13) v = v.substring(0, 13);
      this.value = v;
    });
  }

  /* ================================================================
   * Отправка заказа
   * ================================================================ */
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

  var form = document.getElementById('order-form');
  var submitBtn = document.getElementById('checkout-submit-btn');
  var sending = false;

  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (sending) return;

      var address = document.getElementById('inp-address').value.trim();
      var phone = phoneInp.value.replace(/[^\d+]/g, '');

      if (nameInp.value.match(/[0-9]/)) {
        alert(I18N.errNameDigits || 'Ім’я не може містити цифри');
        nameInp.focus();
        return;
      }
      if (!/^\+380\d{9}$/.test(phone)) {
        alert(I18N.errPhone || 'Введіть коректний номер');
        phoneInp.focus();
        return;
      }
      if (!address) {
        alert(I18N.errAddress || 'Вкажіть адресу');
        document.getElementById('inp-address').focus();
        return;
      }

      var cart = getCart();
      if (!cart.length) {
        alert(I18N.empty || 'Кошик порожній');
        return;
      }

      /* Собираем данные для Telegram */
      var cartItems = [];
      var cartTotal = 0;
      var cartCount = 0;

      cart.forEach(function (item) {
        var product = findProduct(item);
        var name = getItemName(item, product);
        var price = getItemPrice(item, product);
        var qty = item.quantity || 1;
        cartItems.push({
          id: item.variationId || item.productId || item.id || null,
          name: name,
          price: price,
          quantity: qty,
          price_text: formatPrice(price),
          tacho_model: item.tachoModel || null,
          voltage: item.voltage || null,
          sku: item.sku || (product && product.sku) || null,
        });
        cartTotal += price * qty;
        cartCount += qty;
      });

      document.getElementById('cart_items').value = JSON.stringify(cartItems);
      document.getElementById('cart_total').value = formatPrice(cartTotal);
      document.getElementById('cart_count').value = cartCount;
      document.getElementById('lang').value = CFG.lang;

      sending = true;
      submitBtn.disabled = true;
      var originalText = submitBtn.textContent;
      submitBtn.textContent = I18N.sending || '…';

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
            /* Успех — очищаем корзину и переходим */
            localStorage.removeItem('cart');
            goToThankYou();
          });
        })
        .catch(function (err) {
          console.error('Order submit error:', err);
          sending = false;
          submitBtn.disabled = false;
          submitBtn.textContent = originalText;
          alert(I18N.errSend || '');
        });
    });
  }

  /* ================================================================
   * UTM (если пришли в URL, а utm.js не отработал)
   * ================================================================ */
  function fillUtm() {
    ['utm_source', 'utm_medium', 'utm_campaign'].forEach(function (k) {
      var el = document.getElementById(k);
      if (el) el.value = localStorage.getItem(k) || '';
    });
  }
  fillUtm();

  /* ================================================================
   * Init
   * ================================================================ */
  render();
})();
