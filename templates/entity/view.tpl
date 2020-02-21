{extends "layout.tpl"}

{block "title"}{$entity->name|escape}{/block}

{block "content"}
  {if isset($pendingEditReview)}
    <div class="alert alert-warning">
      {t 1=$pendingEditReview->getUrl()}link-entity-review-pending-edit{/t}
    </div>
  {/if}

  {include "bits/entity.tpl"}

  {if count($statements)}
    <h4>{cap}{t}statements{/t}{/cap}</h4>
    {include "bits/statementList.tpl" entityImages=false}
  {/if}

  {include "bits/flagModal.tpl"}

{/block}
