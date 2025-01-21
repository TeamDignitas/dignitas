{extends "layout.tpl"}

{block "title"}{$entity->name|esc}{/block}

{block "content"}
  <div class="container my-5">
    {if isset($pendingEditReview)}
      {notice icon=info}
        {t 1=$pendingEditReview->getUrl()}link-entity-review-pending-edit{/t}
      {/notice}
    {/if}

    {include "bits/entity.tpl"}

    {if count($statements) || $numMentions || $numMemberMentions || count($members)}
      <nav class="nav nav-pills mt-5 pt-5 activate-first-tab">
        {if count($members)}
          <a
            class="nav-link text-capitalize"
            id="members-tab"
            data-bs-toggle="tab"
            role="tab"
            aria-controls="results-members"
            href="#results-members">
            {t}label-members{/t} ({$members|count})
          </a>
        {/if}

        {if count($statements)}
          <a
            class="nav-link text-capitalize"
            id="statements-tab"
            data-bs-toggle="tab"
            role="tab"
            aria-controls="results-statements"
            href="#results-statements">
            {t}label-statements{/t} ({$numStatements})
          </a>
        {/if}

        {if $numMentions || $numMemberMentions}
          <a
            class="nav-link text-capitalize"
            id="mentions-tab"
            data-bs-toggle="tab"
            role="tab"
            aria-controls="results-mentions"
            href="#results-mentions">
            {t}label-involvements{/t} ({$numMentions + $numMemberMentions})
          </a>
        {/if}
      </nav>
    {/if}

    <div class="tab-content my-5">
      {if count($members)}
        <div
          id="results-members"
          class="tab-pane fade"
          role="tabpanel"
          aria-labelledby="members-tab">
          <ul>
            {foreach $members as $m}
              <li>
                {include "bits/entityLink.tpl" e=$m}
              </li>
            {/foreach}
          </ul>
        </div>
      {/if}

      {if count($statements)}
        <div
          id="results-statements"
          class="tab-pane fade"
          role="tabpanel"
          aria-labelledby="statements-tab">
          <div id="statement-wrapper">
            {include "bits/statementList.tpl"}
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

          {if $numMemberMentions}
            {include "bits/mentionFilters.tpl"}
          {/if}

          <div id="mention-wrapper">
            {include "bits/statementList.tpl" statements=$mentions}
          </div>

          {include "bits/paginationWrapper.tpl"
            n=$mentionPages
            url="{Config::URL_PREFIX}ajax/get-entity-mentions/{$entity->id}"
            target="#mention-wrapper"}
        </div>
      {/if}
    </div>

    {include "bits/flagModal.tpl"}
  </div>
{/block}
