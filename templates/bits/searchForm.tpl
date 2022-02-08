<form id="form-search" action="{Router::link('aggregate/search')}">

  <div id="search-field-container" class="input-group">
    {* the <select> element for Select2 is created in main.js *}

    <noscript>
      <input type="text" class="form-control" name="q" aria-label="search">
    </noscript>

    <button type="submit" class="btn">
      {include "bits/icon.tpl" i=search}
    </button>
  </div>
</form>
