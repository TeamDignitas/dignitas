<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button
    class="navbar-toggler"
    type="button"
    data-toggle="collapse"
    data-target="#navbarContent"
    aria-controls="navbarContent"
    aria-expanded="false"
    aria-label="{t}toggle menu{/t}">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarContent">
    <ul class="navbar-nav mr-auto">

      {if $pageType != 'home'}
        <li class="nav-item">
          <a class="nav-link" href="{$wwwRoot}">
            {t}home page{/t}
          </a>
        </li>
      {/if}

    </ul>

    <ul class="navbar-nav">
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
            <a class="dropdown-item" href="{$wwwRoot}changeLocale?id={$id}">
              <i class="icon icon-ok {if $id != LocaleUtil::getCurrent()}invisible{/if}"></i>
              {$name}
            </a>
          {/foreach}
        </div>
      </li>

      {if User::getActive()}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle"
            href="#"
            id="navbarUserDropdown"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
            <i class="icon icon-user"></i>
            username
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarUserDropdown">
            <a class="dropdown-item" href="{$wwwRoot}auth/logout">
              <i class="icon icon-logout"></i>
              {t}log out{/t}
            </a>
          </div>
        </li>
      {else}
        <li class="nav-item">
          <a class="nav-link" href="{$wwwRoot}auth/login">
            <i class="icon icon-login"></i>
            {t}log in{/t}
          </a>
        </li>
      {/if}

    </ul>
  </div>
</nav>
