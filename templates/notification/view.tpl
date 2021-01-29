{extends "layout.tpl"}

{block "title"}{t}title-notifications{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">
      {t}title-notifications{/t}
    </h1>

    <div id="notification-wrapper" class="mb-4">
      {include "bits/notifications.tpl"}
    </div>

    {include "bits/paginationWrapper.tpl"
      n=$numPages
      k=1
      url="{Config::URL_PREFIX}ajax/notifications"
      target="#notification-wrapper"}

  </div>

{/block}
