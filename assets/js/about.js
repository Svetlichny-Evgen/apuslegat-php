(function () {
  'use strict';
  const slider = document.getElementById('slider-container');
  const track = document.getElementById('slider-track');
  if (!slider || !track) return;

  let isDown = false,
    startX = 0,
    xProgress = 0,
    scrollLeft = 0;

  function autoMove() {
    if (!isDown) {
      xProgress -= 0.5;
      if (Math.abs(xProgress) >= track.scrollWidth / 2) xProgress = 0;
      track.style.transform = 'translateX(' + xProgress + 'px)';
    }
    requestAnimationFrame(autoMove);
  }
  autoMove();

  function dragStart(clientX) {
    isDown = true;
    startX = clientX - track.offsetLeft;
    scrollLeft = xProgress;
  }
  function dragMove(clientX) {
    if (!isDown) return;
    const x = clientX - track.offsetLeft;
    xProgress = scrollLeft + (x - startX);
    track.style.transform = 'translateX(' + xProgress + 'px)';
  }
  function dragEnd() {
    isDown = false;
  }

  slider.addEventListener('mousedown', (e) => dragStart(e.pageX));
  slider.addEventListener('mousemove', (e) => dragMove(e.pageX));
  window.addEventListener('mouseup', dragEnd);

  // touch: добавлен touchmove — в оригинале его не было (drag не работал на телефоне)
  slider.addEventListener('touchstart', (e) => dragStart(e.touches[0].pageX), {
    passive: true,
  });
  slider.addEventListener('touchmove', (e) => dragMove(e.touches[0].pageX), {
    passive: true,
  });
  window.addEventListener('touchend', dragEnd);
})();
