{extends "layout.tpl"}

{block "title"}{$entity->name|escape}{/block}

{block "content"}
  <div class="container mt-5">
    {if isset($pendingEditReview)}
      <div class="alert alert-warning">
        {t 1=$pendingEditReview->getUrl()}link-entity-review-pending-edit{/t}
      </div>
    {/if}

    {include "bits/entity.tpl"}

    <div class="mr-0 mt-5 pl-3">
      <h6 class="font-weight-bold text-uppercase pl-0">{cap}{t}statements{/t}{/cap}</h6>
      {include "bits/statementList.tpl" showEntity=false}
    </div>

    {include "bits/flagModal.tpl"}
  </div>
{/block}
