<nav class="navbar navbar-expand-md navbar-dark">

  <div class="container-fluid"> {* see https://getbootstrap.com/docs/5.0/migration/#navbars *}

    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbar-left"
      aria-controls="navbar-left"
      aria-expanded="false"
      aria-label="{t}label-toggle-menu{/t}">
      {* Do not use navbar-toggler-icon. That gives the two togglers different heights. *}
      {include "bits/icon.tpl" i=search}
    </button>

    <div class="collapse navbar-collapse py-1" id="navbar-left">
      {include "bits/searchForm.tpl"}
    </div>

    <!-- logo -->
    <a class="navbar-brand mx-auto" href="{Config::URL_PREFIX}">
      <img
        src="{Config::URL_PREFIX}img/logo-white.svg"
        width="151"
        height="24"
        class="align-middle"
        alt="{t}tooltip-dignitas-logo{/t}">
    </a>

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

    <div class="collapse navbar-collapse justify-content-end h-100" id="navbar-right">

      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link py-1"
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
                class="dropdown-item py-1"
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
          <li class="nav-item">
            <a
              class="nav-link py-1"
              href="{Router::link('notification/view')}"
              title="{t}link-notifications{/t}">

              <span class="{if $u->countNotifications()}text-danger{/if}">
                {include "bits/icon.tpl" i=notifications}
              </span>
            </a>
          </li>

          <li class="nav-item">
            <a
              class="nav-link py-1"
              href="{Router::link('aggregate/dashboard')}"
              title="{t}link-dashboard{/t}">

              <span class="{if $u->countAvailableReviews()}text-danger{/if}">
                {include "bits/icon.tpl" i=inventory}
              </span>
            </a>
          </li>
        {else}
          {if count(Config::COLOR_SCHEMES) > 1}
            <li class="nav-item">
              <a
                class="nav-link py-1 light-mode-toggle"
                data-mode="light"
                href="#"
                title="{t}link-light-mode{/t}">

                {include "bits/icon.tpl" i=light_mode}
              </a>
              <a
                class="nav-link py-1 dark-mode-toggle"
                data-mode="dark"
                href="#"
                title="{t}link-dark-mode{/t}">

                {include "bits/icon.tpl" i=dark_mode}
              </a>
            </li>
          {/if}
        {/if}

        <li class="nav-item">
          <a
            class="nav-link capitalize-first-word py-1"
            href="{Router::link('help/index')}"
            title="{t}help-center{/t}">
            {t}help-center{/t}
          </a>
        </li>

        {if $u}
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle py-1"
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
              <a class="dropdown-item py-1" href="{Router::userLink($u)}">
                {include "bits/icon.tpl" i=person}
                {t}link-my-profile{/t}
              </a>

              {if count(Config::COLOR_SCHEMES) > 1}
                <a
                  class="dropdown-item py-1 light-mode-toggle"
                  data-mode="light"
                  href="#">
                  {include "bits/icon.tpl" i=light_mode}
                  {t}link-light-mode{/t}
                </a>
                <a
                  class="dropdown-item py-1 dark-mode-toggle"
                  data-mode="dark"
                  href="#">
                  {include "bits/icon.tpl" i=dark_mode}
                  {t}link-dark-mode{/t}
                </a>
              {/if}

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
                      {include "bs/checkbox.tpl"
                        checked=User::isModerator()
                        inputId='fakeModerator'
                        label="{t}label-moderator{/t}"
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
              <a class="dropdown-item py-1" href="{Router::link('auth/logout')}">
                {include "bits/icon.tpl" i=logout}
                {t}link-log-out{/t}
              </a>
            </div>
          </li>
        {else}
          <li class="nav-item">
            <a
              class="nav-link capitalize-first-word py-1"
              href="{Router::link('auth/login')}">
              {t}link-log-in{/t}
            </a>
          </li>
        {/if}

      </ul>

    </div>
  </div>
</nav>
