{$term=$term|default:''}
<form data-url="{Config::URL_PREFIX}ajax/search-statements">
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
        <option value="{Ct::SORT_CREATE_DATE_DESC}">{t}sort-create-date-desc{/t}</option>
        <option value="{Ct::SORT_CREATE_DATE_ASC}">{t}sort-create-date-asc{/t}</option>
        <option value="{Ct::SORT_DATE_MADE_DESC}">{t}sort-date-made-desc{/t}</option>
        <option value="{Ct::SORT_DATE_MADE_ASC}">{t}sort-date-made-asc{/t}</option>
      </select>
    </div>

    <div class="mr-2">
      <label class="col-form-label">
        {t}label-filter{/t}:
      </label>
    </div>

    <div class="mr-2">
      <select
        name="entityId"
        class="form-control form-control-sm actionable"
        data-placeholder="{t}label-author{/t}"
        data-width="200px">
      </select>
    </div>

    <div class="mr-2">
      <select
        name="verdicts[]"
        class="form-control form-control-sm actionable"
        multiple
        data-placeholder="{t}label-verdict{/t}">
        {for $v = 0 to Ct::NUM_VERDICTS - 1}
          <option value="{$v}">
            {Statement::verdictName($v)}
          </option>
        {/for}
      </select>
    </div>

    <div class="mr-2">
      <a class="btn btn-sm text-nowrap extra-filters"
        data-toggle="collapse"
        href="#more-filters">
        {t}label-more-filters{/t}
        <i class="icon icon-sliders"></i>
      </a>
    </div>
  </div>

  <div id="more-filters" class="collapse {if $term}show{/if}">
    <div class="d-flex mb-2 small">
      <div class="mr-2">
        <input
          type="text"
          name="term"
          class="form-control form-control-sm actionable"
          value="{$term}"
          placeholder="{t}label-term{/t}">
      </div>

      <div class="mr-2">
        <input
          type="text"
          id="field-min-date"
          class="form-control form-control-sm date-picker"
          placeholder="{t}label-start-date{/t}">
        <input type="hidden" name="minDate" class="actionable">
      </div>

      <div class="mr-2">
        <input
          type="text"
          id="field-max-date"
          class="form-control form-control-sm date-picker"
          placeholder="{t}label-end-date{/t}">
        <input type="hidden" name="maxDate" class="actionable">
      </div>
    </div>
  </div>

</form>
