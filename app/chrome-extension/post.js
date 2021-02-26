// these should match the sites defined in manifest.json
// const DIGNITAS_ADD_STATEMENT_URL = 'https://dignitas.ro/editeaza-afirmatie';
const DIGNITAS_ADD_STATEMENT_URL = 'http://localhost/dignitas/www/editeaza-afirmatie';

function messageHandler(data) {
  // ensure it is run only once, as we will try to message twice
  chrome.runtime.onMessage.removeListener(messageHandler);

  // create and populate a form
  var form = document.createElement('form');
  form.setAttribute('method', 'post');
  form.setAttribute('action', DIGNITAS_ADD_STATEMENT_URL);
  for (var key in data) {
    var field = document.createElement('input');
    field.setAttribute('type', 'hidden');
    field.setAttribute('name', key);
    field.setAttribute('value', data[key]);
    form.appendChild(field);
  }

  document.body.appendChild(form);
  form.submit();
}

chrome.runtime.onMessage.addListener(messageHandler);
