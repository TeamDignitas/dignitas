{extends "layout.tpl"}

{block "title"}{t}title-archived-link{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h3 class="mb-4">
      {t}info-archived-link{/t}
    </h3>
    <h6>
      <a href="{$archivedLink->url}">{t}link-live-page{/t}</a>
    </h6>
  </div>

  <iframe
    class="frame-archive"
    src="{$archivedLink->getArchivedUrl()}">
  </iframe>

{/block}
