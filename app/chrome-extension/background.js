// Adds a right-click context menu option.
chrome.contextMenus.create({
  title: 'Submit to Dignitas',
  contexts: ['selection'],
  onclick: submitStatement,
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
