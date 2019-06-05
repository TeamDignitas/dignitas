{* expose the localized search URL *}
<script>
  const SEARCH_URL = '{Router::link('aggregate/search')}';
</script>

<form
  id="searchForm"
  action="{Router::link('aggregate/search')}"
  class="d-inline w-100 mx-3">
  <div class="input-group">
    <select
      id="searchField"
      class="form-control"
      name="q"
      multiple
      aria-label="search">
    </select>
    <div class="input-group-append">
      <button type="submit" class="btn btn-primary">
        <i class="icon icon-search"></i>
      </button>
    </div>
  </div>
</form>
