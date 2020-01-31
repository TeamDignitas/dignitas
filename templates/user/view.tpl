{extends "layout.tpl"}

{block "title"}{t}user{/t} {$user}{/block}

{block "content"}
  <div class="clearfix">
    {include "bits/image.tpl"
      obj=$user
      geometry=Config::THUMB_USER_PROFILE
      imgClass="pic float-right"}

    <h3>
      {t}user{/t} {$user}
      {if $user->moderator}
        <span class="badge badge-info">
          {t}moderator{/t}
        </span>
      {/if}
    </h3>

    <dl class="row">
      <dd class="col-2">{t}reputation{/t}</dd>
      <dt class="col-10">{$user->reputation|nf}</dt>
      <dd class="col-2">{t}statements{/t}</dd>
      <dt class="col-10">{$statements}</dt>
      <dd class="col-2">{t}answers{/t}</dd>
      <dt class="col-10">{$answers}</dt>
      <dd class="col-2">{t}member since{/t}</dd>
      <dt class="col-10">{$user->createDate|lt:false}</dt>
      {if $user->lastSeen}
        <dd class="col-2">{t}last seen{/t}</dd>
        <dt class="col-10">{include 'bits/moment.tpl' t=$user->lastSeen}</dt>
      {/if}
    </dl>
  </div>

  {if $user->aboutMe}
    <h4>{t}about me{/t}</h4>

    <div>
      {$user->aboutMe|md}
    </div>
  {/if}

  {if $user->id == User::getActiveId()}
    <hr>
    <a href="{Router::link('user/edit')}" class="btn btn-light">
      <i class="icon icon-edit"></i>
      {t}edit{/t}
    </a>
  {/if}
{/block}
