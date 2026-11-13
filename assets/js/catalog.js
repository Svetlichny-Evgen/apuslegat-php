(function () {
  'use strict';

  var currentCat = 'all';
  var currentCond = 'all';

  /* ===== Корзина ===== */
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
    var badge = document.getElementById('cart-count');
    if (badge) badge.textContent = getCart().length;
  }
  function addToCart(id) {
    var cart = getCart();
    cart.push(id);
    saveCart(cart);
    updateCartBadge();
  }

  /* ===== Фильтры ===== */
  function applyFilters() {
    var visibleCount = 0;
    document.querySelectorAll('.p-link').forEach(function (link) {
      var card = link.querySelector('.p-card');
      if (!card) return;
      var cat = card.dataset.cat;
      var conds = (card.dataset.conditions || '').split(' ').filter(Boolean);

      var catMatch = currentCat === 'all' || cat === currentCat;
      var condMatch =
        currentCond === 'all' || conds.indexOf(currentCond) !== -1;
      var visible = catMatch && condMatch;

      link.classList.toggle('hidden', !visible);
      if (visible) visibleCount++;
    });

    var empty = document.getElementById('empty-state');
    if (empty) empty.classList.toggle('hidden', visibleCount > 0);
  }

  document.querySelectorAll('#cat-filters .f-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('#cat-filters .f-btn').forEach(function (b) {
        b.classList.remove('active');
      });
      btn.classList.add('active');
      currentCat = btn.dataset.cat;
      applyFilters();
    });
  });

  document.querySelectorAll('#cond-filters .f-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('#cond-filters .f-btn').forEach(function (b) {
        b.classList.remove('active');
      });
      btn.classList.add('active');
      currentCond = btn.dataset.cond;
      applyFilters();
    });
  });

  /* ===== Добавить в корзину ===== */
  document.querySelectorAll('.p-buy').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      if (btn.dataset.action === 'add') {
        e.preventDefault();
        e.stopPropagation();
        addToCart(btn.dataset.id);
        var old = btn.textContent;
        btn.textContent = '✓';
        setTimeout(function () {
          btn.textContent = old;
        }, 1000);
      }
      /* если action === 'navigate' — ничего не делаем, <a> сам перейдёт */
    });
  });

  /* ===== Init ===== */
  updateCartBadge();
  applyFilters();
})();
