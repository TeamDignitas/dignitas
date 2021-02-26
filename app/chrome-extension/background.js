/**
 * The possible destinations are stored here. When the extension initializes,
 * we copy these into the background #destination select. When the popup
 * opens, popup.js copies these values to popup.html. When the users selects a
 * different option in popup.html, popup.js relays this information to
 * background.js, which updates the background select.
 *
 * The values should match the sites defined in manifest.json.
 */
const DESTINATIONS = [
  {
    'url': 'https://dignitas.ro/editeaza-afirmatie',
    'text': 'dignitas.ro',
  },
  {
    'url': 'http://localhost/dignitas/www/editeaza-afirmatie',
    'text': 'localhost (' + chrome.i18n.getMessage('forDevelopersOnly') + ')',
  },
];

document.addEventListener('DOMContentLoaded', function() {
  // Populate the destination select.
  var select = document.querySelector('#destination');
  for (var i = 0; i < DESTINATIONS.length; i++) {
    var opt = document.createElement('option');
    opt.value = DESTINATIONS[i].url;
    opt.innerHTML = DESTINATIONS[i].text;
    select.appendChild(opt);
  }

  // Add a right-click context menu option.
  chrome.contextMenus.create({
    title: chrome.i18n.getMessage('submit'),
    contexts: ['selection'],
    onclick: submitStatement,
  });
});

// Receives notifications when the user selected a different index in the popup.
function changeDestination(index) {
  var select = document.querySelector('#destination');
  select.selectedIndex = index;
}

// Opens a new tab and messages it with the data to POST.
// Based on https://stackoverflow.com/a/23687543/6022817.
function submitStatement(sel) {
  var url = document.querySelector('#destination').value;
  var data = {
    'url': url,
    'params': {
      'linkUrl': sel.pageUrl,
      'summary': sel.selectionText,
      'extensionSubmit': true,
    },
  };

  chrome.tabs.create(
    { url: chrome.runtime.getURL('post.html') },
    function(tab) {
      var handler = function(tabId, changeInfo) {
        if (tabId === tab.id && changeInfo.status === 'complete') {
          chrome.tabs.onUpdated.removeListener(handler);
          chrome.tabs.sendMessage(tabId, data);
        }
      }

      // in case we're faster than page load (usually):
      chrome.tabs.onUpdated.addListener(handler);
      // just in case we're too late with the listener:
      chrome.tabs.sendMessage(tab.id, data);
    }
  );
}
