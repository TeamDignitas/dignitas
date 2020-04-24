{extends "layout.tpl"}

{block "title"}{t}title-user{/t} {$user}{/block}

{block "content"}
  <div class="row">
    <div class="col-md-3 col-sm-12 mt-2 text-center">
      <div>
        {include "bits/image.tpl"
          obj=$user
          geometry=Config::THUMB_USER_PROFILE
          imgClass="pic rounded-circle img-fluid no-outline"}
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
      <h1 class="font-weight-bold mb-5 center-mobile">
        {t}label-user{/t} {$user}
      </h1>

      <dl class="row">
        <dd class="col-sm-5 col-md-4 text-capitalize">{t}label-reputation{/t}</dd>
        <dt class="col-sm-7 col-md-8">{$user->getReputation()|nf}</dt>
        <dd class="col-sm-5 col-md-4 text-capitalize">{t}label-statements{/t}</dd>
        <dt class="col-sm-7 col-md-8">{$statements}</dt>
        <dd class="col-sm-5 col-md-4 text-capitalize">{t}label-answers{/t}</dd>
        <dt class="col-sm-7 col-md-8">{$answers}</dt>
        <dd class="col-sm-5 col-md-4 capitalize-first-word">{t}label-member-since{/t}</dd>
        <dt class="col-sm-7 col-md-8">{$user->createDate|lt:false}</dt>
        {if $user->getLastSeen()}
          <dd class="col-sm-5 col-md-4 capitalize-first-word">{t}label-last-seen{/t}</dd>
          <dt class="col-sm-7 col-md-8">{include 'bits/moment.tpl' t=$user->getLastSeen()}</dt>
        {/if}
      </dl>

      {if $user->aboutMe}
        <h5 class="capitalize-first-word font-weight-bold mt-5">{t}title-about-me{/t}</h5>
        <div>
          {$user->aboutMe|md}
        </div>
      {/if}

      {if $user->id == User::getActiveId()}
        <hr class="mb-2">
        <div class="user-actions text-right">
          <a href="{Router::link('user/edit')}" class="btn btn-sm btn-outline-primary">
            <i class="icon icon-edit"></i>
            {t}link-edit{/t}
          </a>
        </div>
      {/if}

    </div>
  </div>
{/block}
