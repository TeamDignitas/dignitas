/* Custom code built on top of select2.min.js */

$.fn.select2.defaults.set('language', 'ro');

/**
 * Resolves a select element whose <option>s contain only IDs.
 * Fetches the display value and possibly other attributes.
 * obj = jQuery object
 * url = Ajax URL used to resolve IDs to objects
 **/
function resolveSelect(obj, url) {
  var values = [];
  obj.find('option').each(function() {
    values.push(Number($(this).val()));
  });

  return $.ajax({
    url: url + '/' + JSON.stringify(values),
  }).done(function(data) {
    for (var i = 0; i < data.length; i++) {
      var o = obj.find('option').eq(i);
      o.html(data[i].text);
    }
  });
}

/**
 * Builds a Deferred around resolveSelect() that runs when all the objects are initialized.
 **/
function resolveSelectDeferred(sel, url) {
  var deferreds = [];

  $(sel).each(function() {
    var obj = $(this);
    deferreds.push(
      resolveSelect(obj, url)
    );
  });

  return $.when.apply($, deferreds);
}

/**
 * Initialize select2 objects whose <option>s contain only IDs.
 * sel = CSS selector
 * url = Ajax URL used to resolve IDs to objects
 * options = options passed to select2
 *
 * Returns a Deferred object that runs when all objects are initialized.
 **/
function initSelect2(sel, url, options) {
  return resolveSelectDeferred(sel, url)
    .done(function() {
      var s = $(sel);
      console.log(options);
      s.select2(options);
    });
}
