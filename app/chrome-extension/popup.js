document.addEventListener('DOMContentLoaded', function() {
  // Populate the header.
  var header = document.querySelector('#header');
  header.innerHTML = chrome.i18n.getMessage('popupHeader');

  // copy the HTML and the selected index from background.html
  var bg = chrome.extension.getBackgroundPage();
  var bgSelect = bg.document.querySelector('#destination');
  var select = document.querySelector('#destination');

  select.innerHTML = bgSelect.innerHTML;
  select.selectedIndex = bgSelect.selectedIndex;

  // notify background.js whenever our selected index changes
  select.addEventListener('change', function(e) {
    bg.changeDestination(e.target.selectedIndex);
  });
});
