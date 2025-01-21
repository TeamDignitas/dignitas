{$term=$term|default:''}
{$verdicts=$verdicts|default:[]}
<form
  class="text-center"
  data-url="{Config::URL_PREFIX}ajax/get-entity-mentions/{$entity->id}">

  <div class="form-check form-check-inline">
    <input
      checked
      class="form-check-input actionable"
      id="mention-radio-1"
      name="mentionType"
      type="radio"
      value="{Involvement::TYPE_OWN}"
    >
    <label class="form-check-label" for="mention-radio-1">
      {t}label-mentions-party{/t}
    </label>
  </div>

  <div class="form-check form-check-inline">
    <input
      class="form-check-input actionable"
      id="mention-radio-2"
      name="mentionType"
      type="radio"
      value="{Involvement::TYPE_MEMBER}"
    >
    <label class="form-check-label" for="mention-radio-2">
      {t}label-mentions-member{/t}
    </label>
  </div>

</form>
