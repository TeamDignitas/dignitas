{* optional argument: $items: array of UrlTrait objects *}
<div class="form-group">
  <label>{$labelText}</label>

  <table class="table table-sm">
    <tbody id="urlContainer">
      {include "bits/urlEditorRow.tpl" rowId="urlStem"}
      {foreach $items as $item}
        {include "bits/urlEditorRow.tpl"}
      {/foreach}
    </tbody>
  </table>

  {include "bits/fieldErrors.tpl" errors=$errors|default:null}

  <div>
    <button class="addUrlButton btn btn-light btn-sm" type="button">
      <i class="icon icon-plus"></i>
      {$addButtonText}
    </button>
  </div>
</div>
