$('.off-canvas-toggle').on('click', function(event) {
    event.preventDefault();
    $('body').toggleClass('off-canvas-active');
  });
  
  $(document).on('mouseup touchend', function(event) {
    var offCanvas = $('.off-canvas')
    if (!offCanvas.is(event.target) && offCanvas.has(event.target).length === 0) {
      $('body').removeClass('off-canvas-active')
    }
  });