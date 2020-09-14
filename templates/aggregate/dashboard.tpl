{extends "layout.tpl"}

{block "title"}{cap}{t}title-dashboard{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-5">{cap}{t}title-dashboard{/t}{/cap}</h1>

    <div class="card-columns">
      {if User::may(User::PRIV_ADD_STATEMENT)}
        <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
          <h3 class="card-title">
            <i class="icon icon-pencil"></i>
          </h3>
          <a href="{Router::link('statement/edit')}" class="stretched-link">
            {t}link-add-statement{/t}
          </a>
        </div>
      {/if}

      {if User::may(User::PRIV_ADD_ENTITY)}
        <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
          <h3 class="card-title">
            <i class="icon icon-user-plus"></i>
          </h3>
          <a href="{Router::link('entity/edit')}" class="stretched-link">
            {t}link-add-entity{/t}
          </a>
        </div>
      {/if}

      {if User::isModerator()}
        <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
          <h3 class="card-title">
            <i class="icon icon-link"></i>
          </h3>
          <a href="{Router::link('domain/list')}" class="stretched-link">
            {t}link-domains{/t}
          </a>
        </div>
        <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
          <h3 class="card-title">
            <i class="icon icon-doc-text-inv"></i>
          </h3>
          <a href="{Router::link('cannedResponse/list')}" class="stretched-link">
            {t}link-canned-responses{/t}
          </a>
        </div>
        <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
          <h3 class="card-title">
            <i class="icon icon-id-card-o"></i>
          </h3>
          <a href="{Router::link('invite/list')}" class="stretched-link">
            {t}link-invites{/t}
          </a>
        </div>
        <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
          <h3 class="card-title">
            <i class="icon icon-cubes"></i>
          </h3>
          <a href="{Router::link('entityType/list')}" class="stretched-link">
            {t}link-entity-types{/t}
          </a>
        </div>
        <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
          <h3 class="card-title">
            <i class="icon icon-exchange"></i>
          </h3>
          <a href="{Router::link('relationType/list')}" class="stretched-link">
            {t}link-relation-types{/t}
          </a>
        </div>
        <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
          <h3 class="card-title">
            <i class="icon icon-industry"></i>
          </h3>
          <a href="{Router::link('staticResource/list')}" class="stretched-link">
            {t}link-static-resources{/t}
          </a>
        </div>
      {/if}

      <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
        <h3 class="card-title">
          <i class="icon icon-tags"></i>
        </h3>
        <a href="{Router::link('tag/list')}" class="stretched-link">
          {t}link-tags{/t}
        </a>
      </div>
    </div>

    {if User::may(User::PRIV_REVIEW) && !empty($activeReviewReasons)}
      <h4 class="mt-5">{cap}{t}title-review-queues{/t}{/cap}</h4>

      <ul class="card-columns pl-0">
        {foreach $activeReviewReasons as $r}
          <li class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
            <a href="{Router::link('review/view')}/{Review::getUrlName($r)}" class="capitalize-first-word stretched-link">
              {Review::getDescription($r)}
            </a>
          </li>
        {/foreach}
      </ul>
    {/if}

    {if User::isModerator()}
      {if $numBadVerdicts}
        <h4 class="mt-5">{cap}{t}title-reports{/t}{/cap}</h4>

        <ul class="card-columns pl-0">
          <li class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
            <a href="{Router::link('statement/verdictReport')}" class="capitalize-first-word stretched-link">
              {t}link-verdict-report{/t}
             </a>
             ({$numBadVerdicts})
           </li>
         </ul>
      {/if}
    {/if}
  </div>
{/block}
