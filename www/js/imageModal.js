// Adapted from https://www.w3schools.com/howto/howto_css_modal_images.asp
$(function() {
  $('body').append('<div id="image-modal">' +
                   '<span class="close">&times;</span>' +
                   '<img>' +
                   '<div class="caption"></div>' +
                   '</div>');

  $('body').on('click', 'a.expand', function() {
    var href = $(this).attr('href');
    var noHash = href.split('#')[0]; // strip the fragment

    if (noHash.endsWith('.pdf')) {
      // open PDFs in separate tab
      window.open(href, '_blank');
    } else {
      $('#image-modal').show();
      $('#image-modal img').attr('src', href);
      $('#image-modal div.caption').html($(this).find('img').attr('alt'));
    }

    return false;
  });

  $('#image-modal .close').click(function() {
    $('#image-modal').hide();
  });

  $(document).keydown(function(event) {
    if (event.keyCode == 27) {
      $('#image-modal').hide();
    }
  });

});
