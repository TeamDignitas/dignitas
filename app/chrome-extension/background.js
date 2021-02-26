// Adds a right-click context menu option.
chrome.contextMenus.create({
  title: 'Submit to Dignitas',
  contexts: ['selection'],
  onclick: submitStatement,
});

function responseListener () {
  console.log(this.responseText);
}

// Opens a new tab and POSTs in it. Based on https://stackoverflow.com/a/23687543/6022817
function submitStatement(sel) {
  var data = {
    'linkUrl': sel.pageUrl,
    'summary': sel.selectionText,
    'extensionSubmit': true,
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
