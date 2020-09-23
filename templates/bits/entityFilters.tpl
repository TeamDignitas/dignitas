{$term=$term|default:''}
<form data-url="{Config::URL_PREFIX}ajax/search-entities">

  <div class="d-flex mb-2 small">
    <div class="mr-2">
      <label class="col-form-label">
        {t}label-sort{/t}:
      </label>
    </div>

    <div class="mr-4">
      <select
        name="order"
        class="form-control form-control-sm actionable">
        <option value="{Ct::SORT_NAME_ASC}">{t}sort-name-asc{/t}</option>
        <option value="{Ct::SORT_NAME_DESC}">{t}sort-name-desc{/t}</option>
        <option value="{Ct::SORT_CREATE_DATE_DESC}">{t}sort-create-date-desc{/t}</option>
        <option value="{Ct::SORT_CREATE_DATE_ASC}">{t}sort-create-date-asc{/t}</option>
      </select>
    </div>

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
  </div>

</form>
