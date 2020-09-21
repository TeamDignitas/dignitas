{$term=$term|default:''}
<form
  class="d-flex mb-2 small"
  data-url="{Config::URL_PREFIX}ajax/search-statements">

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

  <div class="mr-2">
    <select
      name="entityId"
      class="form-control form-control-sm actionable"
      data-placeholder="{t}label-author{/t}"
      data-width="200px">
    </select>
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

</form>
