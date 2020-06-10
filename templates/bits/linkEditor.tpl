{* optional argument: $links: array of Link objects *}
<div class="form-group row">
  <label class="col-sm-4 col-lg-2 mt-1 pl-0">{$labelText}</label>
  <div class="col-sm-8 col-lg-10 mb-2 pl-0">
    <button class="add-link btn btn-outline-secondary btn-sm" type="button">
      <i class="icon icon-plus"></i>
      {$addButtonText}
    </button>
  </div>

  <table class="table table-sm sortable col-md-10 offset-md-2">
    <tbody id="link-container">
      {include "bits/linkEditorRow.tpl" rowId="linkStem"}
      {foreach $links as $link}
        {include "bits/linkEditorRow.tpl"}
      {/foreach}
    </tbody>
  </table>

  {include "bits/fieldErrors.tpl" errors=$errors|default:null}
</div>
