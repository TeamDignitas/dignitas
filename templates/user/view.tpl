{extends "layout.tpl"}

{block "title"}{t}title-user{/t} {$user}{/block}

{block "content"}
  <div class="row">
    <div class="col-md-3 col-sm-12 mt-2 text-center">
      <div>
        {include "bits/image.tpl"
          obj=$user
          geometry=Config::THUMB_USER_PROFILE
          imgClass="pic rounded-circle img-fluid"}
      </div>
      <h4 class="user-badge mt-3">
        {if $user->moderator}
          <span class="badge badge-info">
            {t}label-moderator{/t}
          </span>
        {/if}
      </h4>
    </div>

    <div class="col-md-9 col-sm-12 mt-2">
      <h2 class="font-weight-bold mb-5">
        {t}label-user{/t} {$user}
      </h2>

      <dl class="row">
        <dd class="col-3 text-capitalize">{t}label-reputation{/t}</dd>
        <dt class="col-9">{$user->getReputation()|nf}</dt>
        <dd class="col-3 text-capitalize">{t}label-statements{/t}</dd>
        <dt class="col-9">{$statements}</dt>
        <dd class="col-3 text-capitalize">{t}label-answers{/t}</dd>
        <dt class="col-9">{$answers}</dt>
        <dd class="col-3 capitalize-first-word">{t}label-member-since{/t}</dd>
        <dt class="col-9">{$user->createDate|lt:false}</dt>
        {if $user->getLastSeen()}
          <dd class="col-3 capitalize-first-word">{t}label-last-seen{/t}</dd>
          <dt class="col-9">{include 'bits/moment.tpl' t=$user->getLastSeen()}</dt>
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
