{* optional argument: $links: array of Link objects *}
{$errors=$errors|default:[]}

{hf label=$labelText}
  <button class="add-link btn btn-outline-secondary btn-sm my-2" type="button">
    {$addButtonText}
  </button>

  <div class="px-0">
    <table class="table table-sm sortable {if $errors}is-invalid{/if}">
      <tbody id="link-container">
        {include "bits/linkEditorRow.tpl" rowId="linkStem"}
        {foreach $links as $link}
          {include "bits/linkEditorRow.tpl"}
        {/foreach}
      </tbody>
    </table>

    {include "bs/feedback.tpl" errors=$errors|default:null}
  </div>
{/hf}
