<nav class="navbar navbar-expand-md navbar-dark bg-dark">

  <button
    class="navbar-toggler"
    type="button"
    data-bs-toggle="collapse"
    data-bs-target="#navbar-left"
    aria-controls="navbar-left"
    aria-expanded="false"
    aria-label="{t}label-toggle-menu{/t}">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse py-2" id="navbar-left">
    {include "bits/searchForm.tpl"}

    <ul class="navbar-nav">

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle pl-2 py-2"
          href="#"
          id="nav-dropdown-info"
          role="button"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false">
          {cap}{t}link-info{/t}{/cap}
        </a>
        <div class="dropdown-menu dropdown-menu-dark py-0" aria-labelledby="nav-dropdown-info">
          <a
            class="dropdown-item pl-2 py-2"
            href="{Router::link('aggregate/about')}">
            {cap}{t}link-about{/t}{/cap}
          </a>
          <a
            class="dropdown-item pl-2 py-2"
            href="{Router::link('aggregate/contact')}">
            {cap}{t}link-contact{/t}{/cap}
          </a>
        </div>
      </li>

    </ul>
  </div>

  <!-- logo -->
  <div class="mx-auto">
    <a class="navbar-brand" href="{Config::URL_PREFIX}">
      <img
        src="{Config::URL_PREFIX}img/logo-white.svg"
        width="151"
        height="24"
        class="align-middle"
        alt="{t}tooltip-dignitas-logo{/t}">
    </a>
  </div>

  <button
    class="navbar-toggler"
    type="button"
    data-bs-toggle="collapse"
    data-bs-target="#navbar-right"
    aria-controls="navbar-right"
    aria-expanded="false"
    aria-label="{t}label-toggle-menu{/t}">
    {include "bits/icon.tpl" i=person}
  </button>

  <div class="collapse navbar-collapse justify-content-end" id="navbar-right">

    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a
          class="nav-link capitalize-first-word pl-2 py-2"
          href="{Router::link('help/index')}"
          title="{t}help-center{/t}"
        >
          {t}help-center{/t}
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link pl-2 py-2"
          href="#"
          id="nav-dropdown-lang"
          role="button"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false">
          {include "bits/icon.tpl" i=language}
        </a>

        {* leverage rel alternates to stay on the page after changing language *}
        <div class="dropdown-menu dropdown-menu-dark py-0" aria-labelledby="nav-dropdown-lang">
          {foreach LocaleUtil::getAll() as $id => $name}
            <a
              class="dropdown-item pl-2 py-2"
              href="{Router::getRelAlternate($id)}">
              <span {if $id != LocaleUtil::getCurrent()}class="invisible"{/if}>
                {include "bits/icon.tpl" i=done}
              </span>
              {$name}
            </a>
          {/foreach}
        </div>
      </li>

      {$u=User::getActive()}
      {if $u}
        {$notCount=$u->countNotifications()}

        <li class="nav-item">
          <a
            class="nav-link pl-2 py-2"
            href="{Router::link('notification/view')}"
            title="{t}link-notifications{/t}">

            <span class="{if $notCount}text-danger{/if}">
              {include "bits/icon.tpl" i=notifications}
            </span>
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle pl-2 py-2"
            href="#"
            id="nav-dropdown-user"
            role="button"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
            {if $u->fileExtension}
              {include "bits/image.tpl"
                obj=$u
                geometry=Config::THUMB_USER_NAVBAR
                imgClass="rounded"}
            {else}
              {include "bits/icon.tpl" i=person}
            {/if}
            {$u}
            <span class="badge bg-secondary align-middle pt-2">
              {$u->getReputation()|nf}
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-dark dropdown-menu-end py-0" aria-labelledby="nav-dropdown-user">
            <a class="dropdown-item py-2" href="{Router::userLink($u)}">
              {include "bits/icon.tpl" i=person}
              {t}link-my-profile{/t}
            </a>
            <a class="dropdown-item py-2" href="{Router::link('aggregate/dashboard')}">
              {include "bits/icon.tpl" i=inventory}
              {t}link-dashboard{/t}
            </a>
            {if Config::DEVELOPMENT_MODE}
              <div class="dropdown-divider"></div>
              <form id="rep-change" class="px-4">
                <small class="mb-2">
                  {t}info-reputation-manual{/t}
                </small>

                <input
                  type="text"
                  class="form-control"
                  id="fakeReputation"
                  value="{$u->getReputation()}">

                <div class="d-flex mt-2 justify-content-between align-items-center">
                  <div id="fake-moderator-wrapper">
                    {capture 'label'}{t}label-moderator{/t}{/capture}
                    {include "bs/checkbox.tpl"
                      checked=User::isModerator()
                      inputId='fakeModerator'
                      label=$smarty.capture.label
                      name=''}
                  </div>
                  <div>
                    <button type="submit" class="btn btn-sm btn-secondary">
                      {t}link-change{/t}
                    </button>
                  </div>
                </div>

              </form>
            {/if}
            <div class="dropdown-divider"></div>
            <a class="dropdown-item py-2" href="{Router::link('auth/logout')}">
              {include "bits/icon.tpl" i=logout}
              {t}link-log-out{/t}
            </a>
          </div>
        </li>
      {else}
        <li class="nav-item">
          <a class="nav-link capitalize-first-word" href="{Router::link('auth/login')}">
            {t}link-log-in{/t}
          </a>
        </li>
      {/if}

    </ul>

  </div>
</nav>
