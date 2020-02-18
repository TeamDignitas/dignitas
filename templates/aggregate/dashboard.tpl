{extends "layout.tpl"}

{block "title"}{cap}{t}title-dashboard{/t}{/cap}{/block}

{block "content"}
  <h3>{cap}{t}title-dashboard{/t}{/cap}</h3>

  {if User::may(User::PRIV_ADD_STATEMENT)}
    <div>
      <a href="{Router::link('statement/edit')}">
        {t}link-add-statement{/t}
      </a>
    </div>
  {/if}

  {if User::may(User::PRIV_ADD_ENTITY)}
    <div>
      <a href="{Router::link('entity/edit')}">
        {t}link-add-author{/t}
      </a>
    </div>
  {/if}

  {if User::isModerator()}
    <div>
      <a href="{Router::link('domain/list')}">
        {t}link-domains{/t}
      </a>
    </div>
  {/if}

  <div>
    <a href="{Router::link('tag/list')}">
      {t}link-tags{/t}
    </a>
  </div>

  {if User::may(User::PRIV_REVIEW) && !empty($activeReviewReasons)}
    <h4>{cap}{t}title-review-queues{/t}{/cap}</h4>

    <ul>
      {foreach $activeReviewReasons as $r}
        <li>
          <a href="{Router::link('review/view')}/{Review::getUrlName($r)}">
            {Review::getDescription($r)}
          </a>
        </li>
      {/foreach}
    </ul>
  {/if}
{/block}
