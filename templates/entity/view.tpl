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
            data-bs-toggle="tab"
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
            data-bs-toggle="tab"
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
          <div id="statement-wrapper">
            {include "bits/statementList.tpl" showEntity=false}
          </div>
          {include "bits/paginationWrapper.tpl"
            n=$statementPages
            url="{Config::URL_PREFIX}ajax/get-entity-statements/{$entity->id}/0"
            target="#statement-wrapper"}
        </div>
      {/if}

      {if count($mentions)}
        <div
          id="results-mentions"
          class="tab-pane fade"
          role="tabpanel"
          aria-labelledby="mentions-tab">
          <div id="mention-wrapper">
            {include "bits/statementList.tpl" statements=$mentions}
          </div>
          {include "bits/paginationWrapper.tpl"
            n=$mentionPages
            url="{Config::URL_PREFIX}ajax/get-entity-statements/{$entity->id}/1"
            target="#mention-wrapper"}
        </div>
      {/if}
    </div>

    {include "bits/flagModal.tpl"}
  </div>
{/block}
