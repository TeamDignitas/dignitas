{extends "layout.tpl"}

{block "title"}{cap}{t}title-dashboard{/t}{/cap}{/block}

{block "content"}
  <h2 class="mb-4">{cap}{t}title-dashboard{/t}{/cap}</h2>

  <div class="card-columns">
    {if User::may(User::PRIV_ADD_STATEMENT)}
      <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
        <h5 class="card-title">
          <i class="icon icon-plus"></i>
        </h5>
        <a href="{Router::link('statement/edit')}">
          {t}link-add-statement{/t}
        </a>
      </div>
    {/if}

    {if User::may(User::PRIV_ADD_ENTITY)}
      <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
        <h5 class="card-title">
          <i class="icon icon-user"></i>
        </h5>
        <a href="{Router::link('entity/edit')}">
          {t}link-add-entity{/t}
        </a>
      </div>
    {/if}

    {if User::isModerator()}
      <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
        <h5 class="card-title">
          <i class="icon icon-link"></i>
        </h5>
        <a href="{Router::link('domain/list')}">
          {t}link-domains{/t}
        </a>
      </div>
      <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
        <h5 class="card-title">
          <i class="icon icon-plus"></i>
        </h5>
        <a href="{Router::link('cannedResponse/list')}">
          {t}link-canned-responses{/t}
        </a>
      </div>
      <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
        <h5 class="card-title">
          <i class="icon icon-plus"></i>
        </h5>
        <a href="{Router::link('invite/list')}">
          {t}link-invites{/t}
        </a>
      </div>
    {/if}

    <div class="card py-4 px-2 m-2 border-secondary text-center fix-min-height">
      <h5 class="card-title">
        <i class="icon icon-plus"></i>
      </h5>
      <a href="{Router::link('tag/list')}">
        {t}link-tags{/t}
      </a>
    </div>
  </div>

  {if User::may(User::PRIV_REVIEW) && !empty($activeReviewReasons)}
    <h4 class="mt-5">{cap}{t}title-review-queues{/t}{/cap}</h4>

    <ul class="pl-0">
      {foreach $activeReviewReasons as $r}
        <li class="card py-4 px-2 m-2 border-secondary text-center w-100">
          <a href="{Router::link('review/view')}/{Review::getUrlName($r)}" class="capitalize-first-word">
            {Review::getDescription($r)}
          </a>
        </li>
      {/foreach}
    </ul>
  {/if}

  {if User::isModerator()}
    {if $numBadVerdicts}
      <h4 class="mt-5">{cap}{t}title-reports{/t}{/cap}</h4>

      <ul class="pl-0">
        <li class="card py-4 px-2 m-2 border-secondary text-center">
          <a href="{Router::link('statement/verdictReport')}" class="capitalize-first-word">
            {t}link-verdict-report{/t}
           </a>
           ({$numBadVerdicts})
         </li>
       </ul>
    {/if}
  {/if}
{/block}
