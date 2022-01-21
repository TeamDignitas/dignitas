{$term=$term|default:''}
<form data-url="{Config::URL_PREFIX}ajax/search-relations">

  <div class="row gx-2 gx-xl-3 gy-2 mb-2 align-items-center statement-filters">
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
      <label class="col-form-label col-form-label-sm capitalize-first-word">
        {t}label-active-on{/t}:
      </label>
    </div>

    <div class="col-12 col-sm-8 col-lg-auto">
      <input
        class="form-control form-control-sm datepicker"
        data-allow-partial="false"
        size="15"
        type="text">
      <input type="hidden" name="activeDate" class="actionable">
    </div>

    <div class="col-12 col-sm-8 offset-sm-4 offset-lg-0 col-lg-auto">
      {include "bs/checkbox.tpl"
        inputClass="actionable"
        label="{t}label-active-relation{/t}"
        labelClass="capitalize-first-word small"
        name="active"}
    </div>

  </div>

</form>
