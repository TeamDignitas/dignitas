{$term=$term|default:''}
<form data-url="{Config::URL_PREFIX}ajax/search-entities">

  <div class="row gx-2 gx-xl-3 gy-2 mb-2 statement-filters">
    <div class="col-12 col-sm-4 col-lg-auto">
      <label class="col-form-label col-form-label-sm text-capitalize">
        {t}label-sort{/t}:
      </label>
    </div>

    <div class="col-12 col-sm-8 col-lg-auto">
      <select
        name="order"
        class="form-select form-select-sm actionable">
        <option value="{Ct::SORT_NAME_ASC}">{t}sort-name-asc{/t}</option>
        <option value="{Ct::SORT_NAME_DESC}">{t}sort-name-desc{/t}</option>
        <option value="{Ct::SORT_CREATE_DATE_DESC}">{t}sort-create-date-desc{/t}</option>
        <option value="{Ct::SORT_CREATE_DATE_ASC}">{t}sort-create-date-asc{/t}</option>
      </select>
    </div>

    <div class="col-12 col-sm-4 col-lg-auto">
      <label class="col-form-label col-form-label-sm text-capitalize">
        {t}label-term{/t}:
      </label>
    </div>

    <div class="col-12 col-sm-8 col-lg-auto">
      <input
        type="text"
        name="term"
        class="form-control form-control-sm actionable"
        value="{$term}"
        placeholder="{t}label-term{/t}"
        size="10">
    </div>

    <div class="col-12 col-sm-4 col-lg-auto">
      <label class="col-form-label col-form-label-sm text-capitalize">
        {t}label-region{/t}:
      </label>
    </div>

    <div class="col-12 col-sm-8 col-lg-auto">
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
