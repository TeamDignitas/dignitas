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
          <dd class="col-sm-5 col-md-4 capitalize-first-word">{t}label-reputation{/t}</dd>
          <dt class="col-sm-7 col-md-8">{$user->getReputation()|nf}</dt>
          <dd class="col-sm-5 col-md-4 capitalize-first-word">{t}label-statements{/t}</dd>
          <dt class="col-sm-7 col-md-8">{$statements}</dt>
          <dd class="col-sm-5 col-md-4 capitalize-first-word">{t}label-answers{/t}</dd>
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
                    <i class="icon icon-trash"></i>
                  </a>
                {/if}
              </div>
            </div>
          {/foreach}
          </dl>
        {/if}

        {if $user->id == User::getActiveId() || User::isModerator()}
          <hr class="mb-2">
          <div class="user-actions text-left">
            {if $user->id == User::getActiveId()}
              <a href="{Router::link('user/edit')}" class="btn btn-sm btn-outline-primary">
                <i class="icon icon-pencil"></i>
                {t}link-edit{/t}
              </a>
            {/if}

            {if User::isModerator()}
              <a
                href="{Router::link('user/ban')}/{$user->id}"
                class="btn btn-sm btn-outline-danger">
                {t}link-ban{/t}
              </a>
            {/if}
          </div>
        {/if}

        {if count($actions)}
          <h5 class="capitalize-first-word font-weight-bold mt-5">
            {t}actions{/t}
          </h5>

          <div id="action-wrapper">
            <table class="table table-hover">
              <thead>
                <tr class="d-flex small">
                  <th class="col-sm-3 col-md-2 border-0">{t}label-date{/t}</th>
                  <th class="col-sm-9 col-md-10 border-0">{t}label-action{/t}</th>
                </tr>
              </thead>
              {include "bits/actions.tpl"}
            </table>
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
