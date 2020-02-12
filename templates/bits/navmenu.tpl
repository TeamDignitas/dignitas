<nav class="navbar navbar-expand-md navbar-dark bg-dark">

  <button
    class="navbar-toggler"
    type="button"
    data-toggle="collapse"
    data-target="#navbarLeft"
    aria-controls="navbarLeft"
    aria-expanded="false"
    aria-label="{t}toggle menu{/t}">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse bg-dark to-left" id="navbarLeft">
    {include "bits/searchForm.tpl"}
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">About us</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Team</a>
      </li>
    </ul>
  </div>

  <!-- logo -->
  <div class="mx-auto">
    <a class="navbar-brand" href="{Config::URL_PREFIX}">
      <img src="{Config::URL_PREFIX}img/dignitas-white-logo-svg.svg" width="163" height="26" alt="DIGNITAS logo">
    </a>
  </div>

  <button
    class="navbar-toggler"
    type="button"
    data-toggle="collapse"
    data-target="#navbarRight"
    aria-controls="navbarRight"
    aria-expanded="false"
    aria-label="{t}toggle menu{/t}">
    <span class="icon icon-user"></span>
  </button>

  <div class="collapse navbar-collapse bg-dark to-right" id="navbarRight">

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle"
          href="#"
          id="navbarLangDropdown"
          role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false">
          <i class="icon icon-globe"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarLangDropdown">
          {foreach LocaleUtil::getAll() as $id => $name}
            <a class="dropdown-item" href="{Router::link('helpers/changeLocale')}?id={$id}">
              <i class="icon icon-ok {if $id != LocaleUtil::getCurrent()}invisible{/if}"></i>
              {$name}
            </a>
          {/foreach}
        </div>
      </li>

      {$u=User::getActive()}
      {if $u}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle"
            href="#"
            id="navbarUserDropdown"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
            {if $u->fileExtension}
              {include "bits/image.tpl"
                obj=$u
                geometry=Config::THUMB_USER_NAVBAR
                imgClass="rounded"}
            {else}
              <i class="icon icon-user"></i>
            {/if}
            {$u}
            <span class="badge badge-secondary align-text-top">
              {$u->reputation|nf}
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUserDropdown">
            <a class="dropdown-item" href="{Router::userLink($u)}">
              <i class="icon icon-user"></i>
              {t}my profile{/t}
            </a>
            <a class="dropdown-item" href="{Router::link('aggregate/dashboard')}">
              <i class="icon icon-gauge"></i>
              {t}dashboard{/t}
            </a>
            {if Config::DEVELOPMENT_MODE}
              <div class="dropdown-divider"></div>
              <div class="dropdown-item">
                <form id="repChange" class="form-inline">
                  <div class="input-group">
                    <label class="icon icon-award"></label>
                    <input
                      type="text"
                      class="form-control"
                      placeholder="{t}reputation{/t}">
                  </div>
                  <small class="form-text text-muted">
                    {t}change reputation manually (will refresh the page){/t}
                  </small>
                </form>
              </div>
            {/if}
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{Router::link('auth/logout')}">
              <i class="icon icon-logout"></i>
              {t}log out{/t}
            </a>
          </div>
        </li>
      {else}
        <li class="nav-item">
          <a class="nav-link" href="{Router::link('auth/login')}">
            <i class="icon icon-login"></i>
            {t}log in{/t}
          </a>
        </li>
      {/if}

    </ul>

  </div>
</nav>
