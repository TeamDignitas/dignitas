{extends "layout.tpl"}

{block "title"}{t}title-user{/t} {$user}{/block}

{block "content"}
  <div class="container my-5">
    <div class="row">
      <div class="col-md-3 col-sm-12 mt-2 text-center">
        <div>
          {include "bits/image.tpl"
            obj=$user
            geometry=Config::THUMB_USER_PROFILE
            imgClass="pic rounded-circle img-fluid no-outline"}
        </div>
        {if $user->moderator}
          <h4 class="user-badge mt-3">
            <span class="badge badge-info">
              {t}label-moderator{/t}
            </span>
          </h4>
        {/if}
      </div>

      <div class="col-md-9 col-sm-12 mt-2">
        <h1 class="font-weight-bold mb-5 center-mobile">
          {t}label-user{/t} {$user}
        </h1>

        <dl class="row">
          <dd class="col-7 col-sm-7 col-md-4 capitalize-first-word">{t}label-reputation{/t}</dd>
          <dt class="col-5 col-sm-5 col-md-8">{$user->getReputation()|nf}</dt>
          <dd class="col-7 col-sm-7 col-md-4 capitalize-first-word">{t}label-statements{/t}</dd>
          <dt class="col-5 col-sm-5 col-md-8">{$statements}</dt>
          <dd class="col-7 col-sm-7 col-md-4 capitalize-first-word">{t}label-answers{/t}</dd>
          <dt class="col-5 col-sm-5 col-md-8">{$answers}</dt>
          <dd class="col-7 col-sm-7 col-md-4 capitalize-first-word">{t}label-member-since{/t}</dd>
          <dt class="col-5 col-sm-5 col-md-8">{$user->createDate|lt:false}</dt>
          {if $user->getLastSeen()}
            <dd class="col-7 col-sm-7 col-md-4 capitalize-first-word">{t}label-last-seen{/t}</dd>
            <dt class="col-5 col-sm-5 col-md-8">{include 'bits/moment.tpl' t=$user->getLastSeen()}</dt>
          {/if}
        </dl>

        {if $user->aboutMe}
          <h5 class="capitalize-first-word font-weight-bold mt-5">{t}title-about-me{/t}</h5>
          <div class="archivable">
            {$user->aboutMe|md}
          </div>
        {/if}

        {if count($bans)}
          <h5 class="capitalize-first-word font-weight-bold mt-5">{t}title-user-bans{/t}</h5>

          {foreach $bans as $ban}
            <div class="row">
              <div class="col-sm-5">{$ban->getTypeName()}</div>
              <div class="col-sm-6">
                {if $ban->isPermanent()}
                  {t}ban-permanent{/t}
                {else}
                  {t}label-ban-until{/t}
                  {$ban->expiration|lt}
                {/if}
              </div>
              <div class="col-sm-1">
                {if User::isModerator()}
                  <a
                    href="{Router::link('user/ban')}?deleteId={$ban->id}"
                    class="btn btn-sm btn-link"
                    title="info-ban-delete">
                    {include "bits/icon.tpl" i=delete_forever}
                  </a>
                {/if}
              </div>
            </div>
          {/foreach}
        {/if}

        {if $user->id == User::getActiveId() || User::isModerator()}
          <hr class="mb-2">
          <div class="mt-4 user-actions text-right">
            {if User::isModerator()}
              <a
                href="{Router::link('user/ban')}/{$user->id}"
                class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 mr-2 mb-2">
                {t}link-ban{/t}
              </a>
            {/if}

            {if $user->id == User::getActiveId()}
              <a href="{Router::link('user/edit')}" class="btn btn-sm btn-primary col-sm-4 col-lg-2 mb-2">
                {include "bits/icon.tpl" i=mode_edit}
                {t}link-edit{/t}
              </a>
            {/if}
          </div>
        {/if}

        {if count($actions)}
          <h5 class="capitalize-first-word font-weight-bold mt-5">
            {t}actions{/t}
          </h5>

          <div id="action-wrapper">
            {include "bits/actions.tpl"}
          </div>

          {include "bits/paginationWrapper.tpl"
            n=$actionPages
            k=1
            url="{Config::URL_PREFIX}ajax/action-log/{$user->id}"
            target="#action-wrapper"}
        {/if}

      </div>
    </div>

  </div>

{/block}
