{extends "layout.tpl"}

{block "title"}{t}title-user{/t} {$user}{/block}

{block "content"}
  <div class="row">
    <div class="col-md-3 col-sm-12 mt-2 text-center">
      <span>
        {include "bits/image.tpl"
          obj=$user
          geometry=Config::THUMB_USER_PROFILE
          imgClass="pic rounded-circle img-fluid"}
      </span>
      <div class="user-badge">
        {if $user->moderator}
          <span class="badge badge-info">
            {t}label-moderator{/t}
          </span>
        {/if}
      </div>
    </div>

    <div class="col-md-9 col-sm-12 mt-2">
      <h2 class="font-weight-bold">
        {t}label-user{/t} {$user}
      </h2>

      <dl class="row">
        <dd class="col-2 text-capitalize">{t}label-reputation{/t}</dd>
        <dt class="col-10">{$user->getReputation()|nf}</dt>
        <dd class="col-2 text-capitalize">{t}label-statements{/t}</dd>
        <dt class="col-10">{$statements}</dt>
        <dd class="col-2 text-capitalize">{t}label-answers{/t}</dd>
        <dt class="col-10">{$answers}</dt>
        <dd class="col-2 text-capitalize">{t}label-member-since{/t}</dd>
        <dt class="col-10">{$user->createDate|lt:false}</dt>
        {if $user->getLastSeen()}
          <dd class="col-2 text-capitalize">{t}label-last-seen{/t}</dd>
          <dt class="col-10">{include 'bits/moment.tpl' t=$user->getLastSeen()}</dt>
        {/if}
      </dl>

      {if $user->aboutMe}
        <h4 class="text-capitalize">{t}title-about-me{/t}</h4>

        <div>
          {$user->aboutMe|md}
        </div>
      {/if}

      {if $user->id == User::getActiveId()}
        <hr>
        <a href="{Router::link('user/edit')}" class="btn btn-light">
          <i class="icon icon-edit"></i>
          {t}link-edit{/t}
        </a>
      {/if}

    </div>
  </div>
{/block}
