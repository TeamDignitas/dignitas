{extends "layout.tpl"}

{block "title"}Dignitas{/block}

{block "content"}

  {foreach $staticResourcesTop as $sr}
    {$sr->getContents()}
  {/foreach}

  {if User::getActive()}
    <div class="bg-dark hero">
      <div class="f-container py-5">
        <div class="col-12 col-sm-12 col-lg-3 mt-3 mb-5">
          <div>
            <span class="col-6 text-uppercase ivory meta-heading pl-0">
              {t}label-contribute-index{/t}
            </span>
            <span class="col-2 ivory meta-line"></span>
          </div>
        </div>

        <div class="col-12 col-sm-12 col-lg-9 mt-3">
          {if User::may(User::PRIV_ADD_STATEMENT)}
            <a
              href="{Router::link('statement/edit')}"
              class="btn btn-primary mr-3 mb-2 px-2 col-8 col-sm-8 col-lg-4">
              <i class="icon icon-pencil pr-2"></i>{t}link-add-statement{/t}
            </a>
          {/if}

          {if User::may(User::PRIV_ADD_ENTITY)}
            <a
              href="{Router::link('entity/edit')}"
              class="btn btn-secondary mb-2 px-2 col-8 col-sm-8 col-lg-4">
              <i class="icon icon-user-plus pr-2"></i>{t}link-add-entity{/t}
            </a>
          {/if}

          {if User::may(User::PRIV_ADD_ANSWER)}
            <a
              href="{Router::link('statement/unanswered')}"
              class="btn btn-secondary mb-2 px-2 col-8 col-sm-8 col-lg-4">
              <i class="icon icon-pencil pr-2"></i>{t}link-add-answer{/t}
            </a>
          {/if}
        </div>
      </div>
    </div>
  {/if}

  <div class="f-container py-5">
    <div class="col-lg-3 col-sm-12 mt-3 mb-5">
      <div>
        <span class="col-6 text-uppercase meta-heading pl-0">{t}title-recent-statements{/t}</span>
        <span class="col-2 meta-line"></span>
      </div>
    </div>

    <div class="col-lg-9 col-sm-12 mt-3">

      {include "bits/statementFilters.tpl"}

      <div id="statement-list-wrapper">
        {include "bits/statementList.tpl"}
      </div>

      {include "bits/paginationWrapper.tpl"
        n=$numStatementPages
        k=1
        url="{Config::URL_PREFIX}ajax/search-statements"
        target="#statement-list-wrapper"}
    </div>
  </div>

  {foreach $staticResourcesBottom as $sr}
    {$sr->getContents()}
  {/foreach}

{/block}
