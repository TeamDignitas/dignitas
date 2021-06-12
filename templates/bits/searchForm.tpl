<form
  id="form-search"
  action="{Router::link('aggregate/search')}"
  class="d-inline w-50">

  <div id="search-field-container" class="input-group">
    {* the <select> element for Select2 is created in main.js *}

    <noscript>
      <input type="text" class="form-control" name="q" aria-label="search">
    </noscript>

    <button type="submit" class="btn btn-darkish">
      {include "bits/icon.tpl" i=search}
    </button>
  </div>
</form>
