{$term=$term|default:''}
{$verdicts=$verdicts|default:[]}
<form data-url="{Config::URL_PREFIX}ajax/search-statements">
  <div class="row mb-2 small statement-filters">

    <div class="col-12 col-sm-12 col-md-3 col-lg-3">
      <select
        name="order"
        class="form-control form-control-sm actionable">
        <option value="{Ct::SORT_CREATE_DATE_DESC}">{t}sort-create-date-desc{/t}</option>
        <option value="{Ct::SORT_CREATE_DATE_ASC}">{t}sort-create-date-asc{/t}</option>
        <option value="{Ct::SORT_DATE_MADE_DESC}">{t}sort-date-made-desc{/t}</option>
        <option value="{Ct::SORT_DATE_MADE_ASC}">{t}sort-date-made-asc{/t}</option>
      </select>
    </div>

    <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-2">
      <select
        name="entityId"
        class="form-control form-control-sm actionable"
        data-placeholder="{t}label-author{/t}"
        data-width="200px">
      </select>
    </div>

    <div class="col-12 col-sm-12 col-md-2 col-lg-2 mb-2">
      <select
        name="type"
        class="form-control form-control-sm actionable">
        {for $t = 0 to Statement::NUM_TYPES - 1}
          <option value="{$t}">
            {Statement::typeName($t)}
          </option>
        {/for}
      </select>
    </div>

    <div class="col-12 col-sm-12 col-md-2 col-lg-2">
      <select
        id="statement-filters-verdicts"
        name="verdicts[]"
        class="form-control form-control-sm selectpicker actionable"
        multiple
        title="{t}label-verdict{/t}"
        data-selected-text-format="count">
        {foreach Statement::getVerdictsByType(Statement::TYPE_ANY) as $v}
          <option
            value="{$v}"
            {if in_array($v, $verdicts)}selected{/if}
            data-content="{include 'bits/selectPickerVerdict.tpl'}">
            {Statement::verdictName($v)}
          </option>
        {/foreach}
      </select>
    </div>

    <div class="col-12 col-sm-12 col-md-2 col-lg-2">
      <a class="btn btn-link btn-lg text-nowrap py-1 px-0"
        data-toggle="collapse"
        href="#more-filters">
        <i class="icon icon-sliders"></i>
        <small>{t}label-other-filters{/t}</small>
      </a>
    </div>
  </div>

  <div id="more-filters" class="collapse {if $term}show{/if}">
    <div class="row mb-2 small statement-filters">
      <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-2">
        <input
          type="text"
          name="term"
          class="form-control form-control-sm actionable"
          value="{$term}"
          placeholder="{t}label-term{/t}">
      </div>

      <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-2">
        <input
          type="text"
          id="field-min-date"
          class="form-control form-control-sm datepicker"
          placeholder="{t}label-start-date{/t}">
        <input type="hidden" name="minDate" class="actionable">
      </div>

      <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-2">
        <input
          type="text"
          id="field-max-date"
          class="form-control form-control-sm datepicker"
          placeholder="{t}label-end-date{/t}">
        <input type="hidden" name="maxDate" class="actionable">
      </div>
    </div>
  </div>

</form>
