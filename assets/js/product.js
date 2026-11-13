(function () {
  'use strict';

  var CFG = window.PRODUCT_CFG || {};
  var product = CFG.product || null;
  if (!product) return;

  var container = document.getElementById('product-view');
  var cartBadge = document.getElementById('cart-count');

  var i18n = CFG.i18n || {};
  var lang = CFG.lang || 'uk';
  var isKit = !!product.is_kit;
  var variations = product.variations || [];

  /* Активная вариация */
  var selectedVariation = CFG.activeVariation || variations[0] || null;

  /* ================================================================
   * КОРЗИНА
   * ================================================================ */
  function getCart() {
    try {
      return JSON.parse(localStorage.getItem('cart') || '[]');
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
    if (!cartBadge) return;
    var total = getCart().reduce(function (sum, item) {
      return sum + (item.quantity || 1);
    }, 0);
    cartBadge.textContent = total;
  }

  function buildCartItem() {
    var displayName =
      (product.name && (product.name[lang] || product.name.uk)) || '';
    var item = {
      productId: product.id,
      name: displayName,
      price: 0,
      variationId: null,
      tachoModel: null,
      voltage: null,
      condition: null,
      sku: null,
      image: product.image_url || 'img/public/no-photo.png',
      quantity: 1,
    };

    if (selectedVariation) {
      item.variationId = selectedVariation.id || null;
      item.price = selectedVariation.price || product.price || 0;
      item.condition =
        selectedVariation.condition || product.condition || 'new';
      item.sku = selectedVariation.sku || null;

      if (isKit) {
        item.tachoModel = selectedVariation.tacho_model || null;
        item.voltage = selectedVariation.voltage || null;
        var parts = [displayName];
        if (item.tachoModel) parts.push('Тахограф ' + item.tachoModel);
        if (item.voltage) parts.push(item.voltage);
        item.name = parts.join(' ');
      } else {
        item.voltage = selectedVariation.voltage || null;
        if (item.voltage) item.name = displayName + ' (' + item.voltage + ')';
      }
    } else {
      item.price = product.price || 0;
      item.condition = product.condition || 'new';
    }
    return item;
  }

  function addToCart(btn) {
    var item = buildCartItem();
    var cart = getCart();

    var idx = cart.findIndex(function (c) {
      if (item.variationId && c.variationId === item.variationId) return true;
      if (!item.variationId && !c.variationId && c.productId === item.productId)
        return true;
      return false;
    });

    if (idx !== -1) {
      cart[idx].quantity = (cart[idx].quantity || 1) + 1;
    } else {
      cart.push(item);
    }

    saveCart(cart);
    updateCartBadge();

    var old = btn.textContent;
    btn.textContent = '✓';
    btn.style.background = '#25d366';
    btn.style.borderColor = '#25d366';
    setTimeout(function () {
      btn.textContent = old;
      btn.style.background = '';
      btn.style.borderColor = '';
    }, 1500);
  }

  /* ================================================================
   * РЕНДЕР (перерисовывает всё, кроме картинки и back-link)
   * ================================================================ */

  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function (m) {
      return m === '&' ? '&amp;' : m === '<' ? '&lt;' : m === '>' ? '&gt;' : m;
    });
  }

  function getDisplayName() {
    var name = (product.name && (product.name[lang] || product.name.uk)) || '';
    if (selectedVariation) {
      var parts = [];
      if (isKit && selectedVariation.tacho_model) {
        parts.push('Тахограф ' + selectedVariation.tacho_model);
      }
      if (selectedVariation.voltage) parts.push(selectedVariation.voltage);
      if (parts.length) return name + ' (' + parts.join(', ') + ')';
    }
    return name;
  }

  function getDisplayDesc() {
    var name = (product.name && (product.name[lang] || product.name.uk)) || '';
    var baseDesc =
      (product.description &&
        (product.description[lang] || product.description.uk)) ||
      '';
    var model = selectedVariation && selectedVariation.tacho_model;

    if (isKit && model) {
      var isDigital = model === '1381';
      var isAnalog = model === '1324' || model === '1318';
      if (lang === 'uk') {
        if (isDigital)
          return 'Монтажний набір для автомобілів. Включає все необхідне для встановлення цифрового тахографа.';
        if (isAnalog)
          return 'Монтажний набір для автомобілів. Включає все необхідне для встановлення аналогового тахографа.';
      } else {
        if (isDigital)
          return 'Монтажный набор для автомобилей. Включает все необходимое для установки цифрового тахографа.';
        if (isAnalog)
          return 'Монтажный набор для автомобилей. Включает все необходимое для установки аналогового тахографа.';
      }
    }

    if (selectedVariation && selectedVariation.description) {
      var vd = selectedVariation.description;
      return vd[lang] || vd.uk || baseDesc;
    }
    return baseDesc;
  }

  function getDisplayPrice() {
    if (selectedVariation && selectedVariation.price > 0) {
      return selectedVariation.price + ' ' + (i18n.currency || 'грн');
    }
    if (product.price > 0)
      return product.price + ' ' + (i18n.currency || 'грн');
    return i18n.priceAsk || '—';
  }

  function getDisplayCondition() {
    var cond =
      (selectedVariation && selectedVariation.condition) ||
      product.condition ||
      'new';
    return cond === 'used' ? i18n.used || 'Б/В' : i18n.new || 'Новий';
  }

  function renderVariationsHtml() {
    if (!variations.length) return '';

    var html =
      '<div class="variations-section"><div class="variations-title">' +
      escapeHtml(i18n.selectVariant || '') +
      '</div>';

    if (isKit) {
      /* Группировка по tacho_model */
      var groups = {};
      variations.forEach(function (v) {
        var m = v.tacho_model || 'unknown';
        if (!groups[m]) groups[m] = [];
        groups[m].push(v);
      });

      Object.keys(groups).forEach(function (m) {
        html += '<div class="tacho-group">';
        html +=
          '<div class="tacho-group-title"><span class="tacho-name">' +
          escapeHtml(m) +
          '</span></div>';
        html += '<div class="variations-grid">';
        groups[m].forEach(function (v) {
          html += variationOptionHtml(v);
        });
        html += '</div></div>';
      });
    } else {
      html += '<div class="variations-grid">';
      variations.forEach(function (v) {
        html += variationOptionHtml(v);
      });
      html += '</div>';
    }

    html += '</div>';
    return html;
  }

  function variationOptionHtml(v) {
    var isActive = selectedVariation && selectedVariation.id === v.id;
    var price =
      v.price > 0
        ? v.price + ' ' + (i18n.currency || 'грн')
        : i18n.priceAsk || '—';

    var html =
      '<div class="variation-option' +
      (isActive ? ' active' : '') +
      '" data-variation-id="' +
      escapeHtml(v.id || '') +
      '">';
    html +=
      '<span class="var-label">' +
      escapeHtml(v.voltage || 'Standard') +
      '</span>';
    html += '<span class="var-price">' + escapeHtml(price) + '</span>';
    if (!isKit && v.condition) {
      html +=
        '<span class="var-condition">' +
        escapeHtml(v.condition === 'used' ? i18n.used || '' : i18n.new || '') +
        '</span>';
    }
    html += '</div>';
    return html;
  }

  function renderKitInfoHtml() {
    if (!isKit || !selectedVariation) return '';
    var name = (product.name && (product.name[lang] || product.name.uk)) || '';

    var html = '<div class="kit-info">';
    html +=
      '<div class="kit-info-item"><span class="kit-info-label">' +
      escapeHtml(i18n.kit || '') +
      ':</span>' +
      '<span class="kit-info-value">' +
      escapeHtml(name) +
      '</span></div>';

    if (selectedVariation.tacho_model) {
      html +=
        '<div class="kit-info-item"><span class="kit-info-label">Тахограф:</span>' +
        '<span class="kit-info-value">' +
        escapeHtml(selectedVariation.tacho_model) +
        '</span></div>';
    }
    if (selectedVariation.voltage) {
      html +=
        '<div class="kit-info-item"><span class="kit-info-label">Напруга:</span>' +
        '<span class="kit-info-value">' +
        escapeHtml(selectedVariation.voltage) +
        '</span></div>';
    }
    if (selectedVariation.sku) {
      html +=
        '<div class="kit-info-item"><span class="kit-info-label">Артикул:</span>' +
        '<span class="kit-info-value">' +
        escapeHtml(selectedVariation.sku) +
        '</span></div>';
    }
    html += '</div>';
    return html;
  }

  function renderProductInfo() {
    var container = document.querySelector('.product-info-side');
    if (!container) return;

    container.innerHTML =
      '<div class="status-badge">' +
      escapeHtml(getDisplayCondition()) +
      '</div>' +
      '<h1 class="product-title">' +
      escapeHtml(getDisplayName()) +
      '</h1>' +
      '<div class="product-price">' +
      escapeHtml(getDisplayPrice()) +
      '</div>' +
      renderKitInfoHtml() +
      renderVariationsHtml() +
      '<div class="product-desc">' +
      escapeHtml(getDisplayDesc()) +
      '</div>' +
      '<div class="info-blocks">' +
      '<div class="info-block"><span class="info-icon">🛡️</span>' +
      '<h4>' +
      escapeHtml(i18n.warrantyTitle || '') +
      '</h4>' +
      '<p>' +
      escapeHtml(i18n.warrantyText || '') +
      '</p></div>' +
      '<div class="info-block"><span class="info-icon">🔄</span>' +
      '<h4>' +
      escapeHtml(i18n.returnTitle || '') +
      '</h4>' +
      '<p>' +
      escapeHtml(i18n.returnText || '') +
      '</p></div>' +
      '<div class="info-block"><span class="info-icon">📦</span>' +
      '<h4>' +
      escapeHtml(i18n.deliveryTitle || '') +
      '</h4>' +
      '<p>' +
      escapeHtml(i18n.deliveryText || '') +
      '</p></div>' +
      '</div>' +
      '<div class="action-btns">' +
      '<button class="pd-btn" type="button" id="pd-add-to-cart">' +
      escapeHtml(i18n.order || '') +
      '</button>' +
      '</div>';

    /* Вешаем обработчики на перерисованный DOM */
    container.querySelectorAll('.variation-option').forEach(function (el) {
      el.addEventListener('click', function () {
        var vid = el.getAttribute('data-variation-id');
        var found = variations.find(function (v) {
          return v.id === vid;
        });
        if (found) selectVariation(found);
      });
    });

    var addBtn = document.getElementById('pd-add-to-cart');
    if (addBtn) {
      addBtn.addEventListener('click', function () {
        addToCart(addBtn);
      });
    }
  }

  function updateURL(variation) {
    var params = new URLSearchParams();
    params.set('id', product.id);
    if (variation) {
      if (variation.tacho_model) params.set('tacho', variation.tacho_model);
      if (variation.voltage) params.set('volt', variation.voltage);
      if (variation.condition) params.set('cond', variation.condition);
    }
    var newUrl = window.location.pathname + '?' + params.toString();
    history.pushState(
      { productId: product.id, variationId: variation && variation.id },
      '',
      newUrl
    );
  }

  function selectVariation(v) {
    selectedVariation = v;
    updateURL(v);
    renderProductInfo();
  }

  /* popstate: пользователь нажал «назад» → переключаем вариацию по URL */
  window.addEventListener('popstate', function () {
    var params = new URLSearchParams(window.location.search);
    var tacho = params.get('tacho');
    var volt = params.get('volt');
    var cond = params.get('cond');

    var found = variations.find(function (v) {
      if (tacho && v.tacho_model !== tacho) return false;
      if (volt && v.voltage !== volt) return false;
      if (cond && v.condition !== cond) return false;
      return true;
    });
    if (found) {
      selectedVariation = found;
      renderProductInfo();
    }
  });

  /* Init */
  updateCartBadge();
  renderProductInfo();
})();
