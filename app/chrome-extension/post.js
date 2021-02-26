function messageHandler(data) {
  // ensure it is run only once, as we will try to message twice
  chrome.runtime.onMessage.removeListener(messageHandler);

  // populate the title and header
  var redirectMsg = chrome.i18n.getMessage('redirecting');
  document.title = redirectMsg;
  document.querySelector('#header').innerHTML = redirectMsg;

  // create and populate a form
  var form = document.createElement('form');
  form.setAttribute('method', 'post');
  form.setAttribute('action', data.url);
  for (var key in data.params) {
    var field = document.createElement('input');
    field.setAttribute('type', 'hidden');
    field.setAttribute('name', key);
    field.setAttribute('value', data.params[key]);
    form.appendChild(field);
  }

  document.body.appendChild(form);
  form.submit();
}

chrome.runtime.onMessage.addListener(messageHandler);
