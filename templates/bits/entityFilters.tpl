{$term=$term|default:''}
<form
  class="d-flex mb-2 small"
  data-url="{Config::URL_PREFIX}ajax/search-entities">

  <div class="mr-2">
    <label class="col-form-label">
      {t}label-filter{/t}:
    </label>
  </div>

  <div class="mr-2">
    <input
      type="text"
      name="term"
      class="form-control form-control-sm actionable"
      value="{$term}"
      placeholder="{t}label-term{/t}">
  </div>

</form>
