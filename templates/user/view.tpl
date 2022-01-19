{extends "layout.tpl"}

{block "title"}{t}title-user{/t} {$user}{/block}

{block "content"}
  <div class="container my-5">
    <div class="row">
      <div class="col-12 col-md-3 mt-2 text-center">
        <div>
          {if $user->fileExtension}
            {include "bits/image.tpl"
              obj=$user
              geometry=Config::THUMB_USER_PROFILE
              imgClass="rounded-circle img-fluid"
              link=true}
          {else}
            {include "bits/icon.tpl" i=person class='surface rounded-circle generic-avatar'}
          {/if}
        </div>

        <div class="mt-3">
          {if $user->moderator}
            <h5 class="d-inline">
              <span class="badge bg-primary">
                {t}label-moderator{/t}
              </span>
            </h5>
          {/if}
          {if $user->getAccuracy() !== null}
            <h5
              class="d-inline"
              data-bs-toggle="tooltip"
              title="{t}info-accuracy{/t}">
              <span class="badge {$user->getAccuracyClass()}">
                {$user->getAccuracy()|number_format:0}%
                {t}label-accuracy{/t}
              </span>
            </h5>
          {/if}
        </div>
      </div>

      <div class="col-12 col-md-9 mt-2">
        <h1 class="fw-bold mb-5 center-mobile user-capitalise">
          {$user}
        </h1>

        <dl class="row">
          <dd class="col-7 col-md-4 capitalize-first-word">{t}label-reputation{/t}</dd>
          <dt class="col-5 col-md-8">
            {$user->getReputation()|nf}
            {$url=LocaleUtil::getHelpUrl('privileges')}
            {if $url}
              <span class="ms-2">
                <a href="{$url}"
                  title="{t}link-privilege-details{/t}"
                  target="_blank">
                  {include "bits/icon.tpl" i=help class="align-top"}
                </a>
              </span>
            {/if}
          </dt>
          <dd class="col-7 col-md-4 capitalize-first-word">{t}label-statements{/t}</dd>
          <dt class="col-5 col-md-8">{$statements}</dt>
          <dd class="col-7 col-md-4 capitalize-first-word">{t}label-answers{/t}</dd>
          <dt class="col-5 col-md-8">{$answers}</dt>
          <dd class="col-7 col-md-4 capitalize-first-word">{t}label-member-since{/t}</dd>
          <dt class="col-5 col-md-8">{$user->createDate|lt:false}</dt>
          {if $user->getLastSeen()}
            <dd class="col-7 col-md-4 capitalize-first-word">{t}label-last-seen{/t}</dd>
            <dt class="col-5 col-md-8">{include 'bits/moment.tpl' t=$user->getLastSeen()}</dt>
          {/if}
        </dl>

        {if $user->aboutMe}
          <h5 class="capitalize-first-word fw-bold mt-5">{t}title-about-me{/t}</h5>
          <div class="archivable">
            {$user->aboutMe|md}
          </div>
        {/if}

        {if count($bans)}
          <h5 class="capitalize-first-word fw-bold mt-5">{t}title-user-bans{/t}</h5>

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
          <div class="mt-4 user-actions text-end">
            {if User::isModerator()}
              <a
                href="{Router::link('user/ban')}/{$user->id}"
                class="btn btn-sm btn-outline-danger col-sm-4 col-lg-2 me-2 mb-2">
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
          <h5 class="capitalize-first-word fw-bold mt-5">
            {t}actions{/t}
          </h5>

          <div id="action-wrapper">
            {include "bits/actions.tpl"}
          </div>

          {include "bits/paginationWrapper.tpl"
            n=$actionPages
            url="{Config::URL_PREFIX}ajax/action-log/{$user->id}"
            target="#action-wrapper"}
        {/if}

      </div>
    </div>

  </div>

{/block}
