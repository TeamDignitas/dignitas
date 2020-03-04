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
    <div class="row statement-list mr-0 mt-5">
      <h6 class="col-md-12 font-weight-bold text-uppercase pb-2 pl-0">{cap}{t}statements{/t}{/cap}</h6>
      {include "bits/statementList.tpl" entityImages=false}
    </div>
  {/if}

  {include "bits/flagModal.tpl"}

{/block}
