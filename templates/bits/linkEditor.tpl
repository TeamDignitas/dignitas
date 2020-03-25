{* optional argument: $links: array of Link objects *}
<div class="form-group">
  <label>{$labelText}</label>

  <table class="table table-sm sortable">
    <tbody id="link-container">
      {include "bits/linkEditorRow.tpl" rowId="linkStem"}
      {foreach $links as $link}
        {include "bits/linkEditorRow.tpl"}
      {/foreach}
    </tbody>
  </table>

  {include "bits/fieldErrors.tpl" errors=$errors|default:null}

  <div>
    <button class="add-link btn btn-outline-secondary btn-sm" type="button">
      <i class="icon icon-plus"></i>
      {$addButtonText}
    </button>
  </div>
</div>
