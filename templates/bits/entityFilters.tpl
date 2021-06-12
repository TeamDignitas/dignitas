{$term=$term|default:''}
<form data-url="{Config::URL_PREFIX}ajax/search-entities">

  <div class="row mb-2 small statement-filters">
    <div class="col-12 col-sm-12 col-md-1 col-lg-1">
      <label class="col-form-label text-capitalize">
        {t}label-sort{/t}:
      </label>
    </div>

    <div class="col-12 col-sm-12 col-md-3 col-lg-3">
      <select
        name="order"
        class="form-select form-select-sm actionable">
        <option value="{Ct::SORT_NAME_ASC}">{t}sort-name-asc{/t}</option>
        <option value="{Ct::SORT_NAME_DESC}">{t}sort-name-desc{/t}</option>
        <option value="{Ct::SORT_CREATE_DATE_DESC}">{t}sort-create-date-desc{/t}</option>
        <option value="{Ct::SORT_CREATE_DATE_ASC}">{t}sort-create-date-asc{/t}</option>
      </select>
    </div>

    <div class="col-12 col-sm-12 col-md-1 col-lg-1">
      <label class="col-form-label text-capitalize">
        {t}label-filter{/t}:
      </label>
    </div>

    <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-2">
      <input
        type="text"
        name="term"
        class="form-control form-control-sm actionable"
        value="{$term}"
        placeholder="{t}label-term{/t}">
    </div>

    <div class="col-12 col-sm-12 col-md-1 col-lg-1">
      <label class="col-form-label text-capitalize">
        {t}label-region{/t}:
      </label>
    </div>

    <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-2">
      <select name="regionId" class="form-select form-select-sm actionable">
        <option value="0"></option>
        {foreach $regions as $option}
          <option value="{$option->id}">
            {$option->name}
          </option>
        {/foreach}
      </select>
    </div>

  </div>

</form>
