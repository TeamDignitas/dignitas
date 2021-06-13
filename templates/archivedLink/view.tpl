{extends "layout.tpl"}

{block "title"}{t}title-archived-link{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h3 class="mb-4">
      {t}info-archived-link{/t}
    </h3>

    <span class="me-5">
      {t}label-archived-link-downloaded{/t}
      {include "bits/moment.tpl" t=$archivedLink->getTimestamp()}
    </span>

    <a href="{$archivedLink->url}" class="btn btn-sm btn-outline-primary">
      {t}link-live-page{/t}
    </a>
  </div>

  <iframe
    class="frame-archive"
    src="{$archivedLink->getArchivedUrl()}">
  </iframe>

{/block}
