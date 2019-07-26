// Adapted from https://www.w3schools.com/howto/howto_css_modal_images.asp
$(function() {
  $('body').append('<div id="imageModal">' +
                   '<span class="close">&times;</span>' +
                   '<img>' +
                   '<div class="caption"></div>' +
                   '</div>');

  $('body').on('click', 'a.expand', function() {
    var href = $(this).attr('href');

    if (href.endsWith('.pdf')) {
      // open PDFs in separate tab
      window.open(href, '_blank');
    } else {
      $('#imageModal').show();
      $('#imageModal img').attr('src', href);
      $('#imageModal div.caption').html($(this).find('img').attr('alt'));
    }

    return false;
  });

  $('#imageModal .close').click(function() {
    $('#imageModal').hide();
  });

  $(document).keydown(function(event) {
    if (event.keyCode == 27) {
      $('#imageModal').hide();
    }
  });

});
