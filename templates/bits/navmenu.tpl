<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header navbar-left pull-left">
      {if $pageType != 'home'}
        <div class="logo-wrapper">
          <a class="navbar-brand" href="{$wwwRoot}">
            {cap}{t}home page{/t}{/cap}
          </a>
        </div>
      {/if}
    </div>

    <div class="navbar-header navbar-right pull-right">
      <button type="button"
        class="navbar-toggle collapsed hamburger-menu"
        data-toggle="collapse"
        data-target="#navMenu"
        aria-expanded="false">
        <span class="sr-only">{t}navigation{/t}</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="navMenu">

      <ul class="nav navbar-nav navbar-right">

        {* language selector *}
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"
            role="button" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-globe"></i>
            <span class="caret"></span>
          </a>

          <ul class="dropdown-menu">
            {foreach LocaleUtil::getAll() as $id => $name}
              <li>
                <a href="{$wwwRoot}changeLocale?id={$id}">
                  <i class="glyphicon glyphicon-ok {if $id != LocaleUtil::getCurrent()}invisible{/if}">
                  </i>
                  {$name}
                </a>
              </li>
            {/foreach}
          </ul>
        </li>

        {* user menu *}
        {if User::getActive()}
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
              role="button" aria-haspopup="true" aria-expanded="false">
              <i class="glyphicon glyphicon-user"></i>
              {User::getActive()|escape}
              <span class="caret"></span>
            </a>

            <ul class="dropdown-menu">
              <li>
                <a href="{$wwwRoot}auth/logout">
                  <i class="glyphicon glyphicon-log-out"></i>
                  <span>{t}log out{/t}</span>
                </a>
              </li>
            </ul>
          </li>
        {else}
          <li>
            <a href="{$wwwRoot}auth/login">
              <i class="glyphicon glyphicon-log-in"></i>
              <span>{t}log in{/t}</span>
            </a>
          </li>
        {/if}
      </ul>

    </div>
  </div>
</nav>
