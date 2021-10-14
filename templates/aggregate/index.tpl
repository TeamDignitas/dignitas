{extends "layout.tpl"}

{block "title"}Dignitas{/block}

{block "content"}

  {foreach $staticResourcesTop as $sr}
    {$sr->getContents()}
  {/foreach}

  {if User::getActive()}
    <div class="hero">
      <div class="f-container py-5">
        <div class="col-12 col-lg-3 mt-3 mb-5">
          <div class="row align-items-center me-3">
            <span class="col-6 col-xl-8 text-uppercase text-butter meta-heading">
              {t}label-contribute-index{/t}
            </span>
            <span class="col-3 col-xl-3 border-butter meta-line"></span>
          </div>
        </div>

        <div class="col-12 col-lg-9 mt-3">
          <div class="row mx-0">
            {if User::may(User::PRIV_ADD_STATEMENT)}
              <a
                href="{Router::link('statement/edit')}"
                class="btn btn-primary me-3 mb-3 col-12 col-lg-4">
                {include "bits/icon.tpl" i=mode_edit class="pe-2"}
                {t}link-add-statement{/t}
              </a>
            {/if}

            {if User::may(User::PRIV_ADD_ENTITY)}
              <a
                href="{Router::link('entity/edit')}"
                class="btn btn-secondary me-3 mb-3 col-12 col-lg-4">
                {include "bits/icon.tpl" i=person_add_alt_1 class="pe-2"}
                {t}link-add-entity{/t}
              </a>
            {/if}

            {if User::may(User::PRIV_ADD_ANSWER)}
              <a
                href="{Router::link('statement/unanswered')}"
                class="btn btn-secondary me-3 mb-3 col-12 col-lg-4">
                {include "bits/icon.tpl" i=mode_edit class="pe-2"}
                {t}link-add-answer{/t}
              </a>
            {/if}
          </div>
        </div>
      </div>
    </div>
  {/if}

  <div class="f-container py-5">
    <div class="col-12 col-lg-3 mt-3 mb-5">
      <div class="row align-items-center me-3">
        <span class="col-6 col-xl-8 text-uppercase meta-heading">
          {t}title-recent-statements{/t}
        </span>
        <span class="col-3 col-xl-3 meta-line"></span>
      </div>
    </div>

    <div class="col-12 col-lg-9 mt-3">

      {include "bits/statementFilters.tpl"}

      <div id="statement-list-wrapper">
        {include "bits/statementList.tpl"}
      </div>

      {include "bits/paginationWrapper.tpl"
        n=$numStatementPages
        url="{Config::URL_PREFIX}ajax/search-statements"
        target="#statement-list-wrapper"}
    </div>
  </div>

  {foreach $staticResourcesBottom as $sr}
    {$sr->getContents()}
  {/foreach}

{/block}
