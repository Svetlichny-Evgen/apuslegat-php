(function () {
  'use strict';

  // Мобильное меню
  window.toggleMenu = function () {
    const m = document.getElementById('mm');
    if (!m) return;
    m.classList.toggle('active');
    document.body.style.overflow = m.classList.contains('active')
      ? 'hidden'
      : '';
  };

  // «Тахограф» — часы + статус
  const I18N = window.I18N || { on: '', off: '' };
  function tick() {
    const clk = document.getElementById('clk');
    const dt = document.getElementById('dt');
    const stt = document.getElementById('stt');
    const tacho = document.getElementById('tacho');
    if (!clk || !dt || !stt || !tacho) return;

    const now = new Date();
    const f = (o) =>
      new Intl.DateTimeFormat('en-GB', {
        timeZone: 'Europe/Kyiv',
        ...o,
      }).format(now);

    const hh = +f({ hour: '2-digit', hour12: false }) % 24;
    const wd = f({ weekday: 'short' });
    const open = !['Sat', 'Sun'].includes(wd) && hh >= 8 && hh < 18;

    clk.textContent = f({ hour: '2-digit', minute: '2-digit', hour12: false });
    dt.textContent = f({
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
    }).replace(/\//g, '.');
    tacho.classList.toggle('open', open);
    stt.textContent = open ? I18N.on : I18N.off;
  }

  tick();
  setInterval(tick, 15000);
})();
