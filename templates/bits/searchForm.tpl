{* expose the localized search URL *}
<script>
  const SEARCH_URL = '{Router::link('aggregate/search')}';
</script>

<form
  id="form-search"
  action="{Router::link('aggregate/search')}"
  class="d-inline w-50">

  <div id="search-field-container" class="input-group">
    {* the <select> element for Select2 is created in main.js *}

    <noscript>
      <input type="text" class="form-control" name="q" aria-label="search">
    </noscript>

    <span class="input-group-append">
      <button type="submit" class="btn btn-primary">
        <i class="icon icon-search"></i>
      </button>
    </span>
  </div>
</form>
