{extends "layout.tpl"}

{block "title"}{$entity->name|escape}{/block}

{block "content"}
  <div class="container my-5">
    {if isset($pendingEditReview)}
      <div class="alert alert-warning">
        {t 1=$pendingEditReview->getUrl()}link-entity-review-pending-edit{/t}
      </div>
    {/if}

    {include "bits/entity.tpl"}

    {if count($statements) || count($mentions)}
      <nav class="nav nav-pills mt-5 pt-5 activate-first-tab">
        {if count($statements)}
          <a
            class="nav-link text-capitalize"
            id="statements-tab"
            data-toggle="tab"
            role="tab"
            aria-controls="results-statements"
            href="#results-statements">
            {t}label-statements{/t}
          </a>
        {/if}

        {if count($mentions)}
          <a
            class="nav-link text-capitalize"
            id="mentions-tab"
            data-toggle="tab"
            role="tab"
            aria-controls="results-mentions"
            href="#results-mentions">
            {t}label-involvements{/t}
          </a>
        {/if}
      </nav>
    {/if}

    <div class="tab-content my-5">
      {if count($statements)}
        <div
          id="results-statements"
          class="tab-pane fade"
          role="tabpanel"
          aria-labelledby="statements-tab">
          {include "bits/statementList.tpl" showEntity=false}
        </div>
      {/if}

      {if count($mentions)}
        <div
          id="results-mentions"
          class="tab-pane fade"
          role="tabpanel"
          aria-labelledby="mentions-tab">
          {include "bits/statementList.tpl" statements=$mentions}
        </div>
      {/if}
    </div>

    {include "bits/flagModal.tpl"}
  </div>
{/block}
