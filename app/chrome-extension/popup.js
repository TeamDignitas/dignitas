document.addEventListener('DOMContentLoaded', function() {
  var bg = chrome.extension.getBackgroundPage();
  var bgSelect = bg.document.querySelector('#destination');
  var select = document.querySelector('#destination');

  // copy the HTML and the selected index from background.html
  select.innerHTML = bgSelect.innerHTML;
  select.selectedIndex = bgSelect.selectedIndex;

  // notify background.js whenever our selected index changes
  select.addEventListener('change', function(e) {
    bg.changeDestination(e.target.selectedIndex);
  });
});
