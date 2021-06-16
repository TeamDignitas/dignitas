{$term=$term|default:''}
{$verdicts=$verdicts|default:[]}
<form data-url="{Config::URL_PREFIX}ajax/search-statements">
  <div class="row gx-1 gx-xl-3 gy-2 mb-2 statement-filters">

    <div class="col-12 col-md-auto">
      <select
        name="order"
        class="form-select form-select-sm actionable">
        <option value="{Ct::SORT_VERDICT_DATE_DESC}">{t}sort-verdict-date-desc{/t}</option>
        <option value="{Ct::SORT_VERDICT_DATE_ASC}">{t}sort-verdict-date-asc{/t}</option>
        <option value="{Ct::SORT_CREATE_DATE_DESC}">{t}sort-create-date-desc{/t}</option>
        <option value="{Ct::SORT_CREATE_DATE_ASC}">{t}sort-create-date-asc{/t}</option>
        <option value="{Ct::SORT_DATE_MADE_DESC}">{t}sort-date-made-desc{/t}</option>
        <option value="{Ct::SORT_DATE_MADE_ASC}">{t}sort-date-made-asc{/t}</option>
      </select>
    </div>

    <div class="col-12 col-md-auto">
      <select
        name="entityId"
        class="form-select form-select-sm actionable"
        data-placeholder="{t}label-author{/t}"
        data-width="200px">
      </select>
    </div>

    <div class="col-12 col-md-auto">
      <select
        name="type"
        class="form-select form-select-sm actionable">
        {for $t = 0 to Statement::NUM_TYPES - 1}
          <option value="{$t}">
            {Statement::typeName($t)}
          </option>
        {/for}
      </select>
    </div>

    <div class="col-12 col-md-auto">
      <select
        class="selectpicker actionable"
        data-selected-text-format="count"
        data-style-base="form-select"
        data-style="form-select-sm"
        data-width="100%"
        id="statement-filters-verdicts"
        multiple
        name="verdicts[]"
        title="{t}label-verdict{/t}">
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

    <div class="col-12 col-md-auto text-center">
      <a class="btn btn-link btn-sm text-nowrap py-1 px-0"
        data-bs-toggle="collapse"
        href="#more-filters">
        {include "bits/icon.tpl" i=filter_list}
        <small>{t}label-other-filters{/t}</small>
      </a>
    </div>
  </div>

  <div id="more-filters" class="collapse {if $term}show{/if}">
    <div class="row gx-1 gx-xl-3 gy-2 mb-2 statement-filters">
      <div class="col-12 col-md-auto">
        <input
          type="text"
          name="term"
          class="form-control d-inline-block form-control-sm actionable"
          value="{$term}"
          placeholder="{t}label-term{/t}"
          size="10">
      </div>

      <div class="col-12 col-md-auto">
        <input
          type="text"
          id="field-min-date"
          class="form-control form-control-sm datepicker"
          placeholder="{t}label-start-date{/t}"
          size="15">
        <input type="hidden" name="minDate" class="actionable">
      </div>

      <div class="col-12 col-md-auto">
        <input
          type="text"
          id="field-max-date"
          class="form-control form-control-sm datepicker"
          placeholder="{t}label-end-date{/t}"
          size="15">
        <input type="hidden" name="maxDate" class="actionable">
      </div>

      <div class="col-12 col-md-auto">
        <select name="regionId" class="form-select form-select-sm actionable">
          <option value="0">{t}label-region{/t}</option>
          {foreach Region::loadAll() as $option}
            <option value="{$option->id}">
              {$option->name}
            </option>
          {/foreach}
        </select>
      </div>

      {$url=LocaleUtil::getSearchUrl()}
      {if $url}
        <div class="col-12 col-md-auto text-center">
          <a href="{$url}"
            title="{t}link-search-details{/t}"
            target="_blank">
            {include "bits/icon.tpl" i=help}
          </a>
        </div>
      {/if}

    </div>
  </div>

</form>
