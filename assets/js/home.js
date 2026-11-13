window.rvScroll = function (dir) {
  const t = document.getElementById('rv-track');
  if (!t) return;
  const card = t.querySelector('.rv-card');
  const step = card ? card.offsetWidth + 20 : 340;
  t.scrollBy({ left: dir * step, behavior: 'smooth' });
};
