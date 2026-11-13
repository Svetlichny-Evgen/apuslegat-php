(function () {
  'use strict';

  const tiles = Array.from(document.querySelectorAll('.tile'));
  const chips = Array.from(document.querySelectorAll('.chip'));
  const cntEl = document.getElementById('cnt');
  if (!tiles.length || !chips.length) return;

  function filt(f) {
    let n = 0;
    tiles.forEach(function (t) {
      const ok = f === 'all' || (t.dataset.n || '').split(' ').includes(f);
      t.classList.toggle('off', !ok);
      if (ok) n++;
    });
    chips.forEach(function (c) {
      c.classList.toggle('on', c.dataset.f === f);
    });
    if (cntEl) cntEl.textContent = n;
  }

  chips.forEach(function (c) {
    c.addEventListener('click', function () {
      const f = c.dataset.f;
      filt(f);
      const target =
        f === 'all' ? location.pathname + location.search : '#' + f;
      history.replaceState(null, '', target);
    });
  });

  const init = /^#n\d+$/.test(location.hash) ? location.hash.slice(1) : 'all';
  filt(init);
})();
