{extends "layout.tpl"}

{block "title"}{t}title-markdown-code{/t}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}title-markdown-code{/t}{/cap}</h1>

    <div class="mb-2">
      <a href="{$obj->getViewUrl()}" class="btn btn-outline-primary">
        {include "bits/icon.tpl" i=arrow_back}
        {t}label-back{/t}
      </a>
    </div>

    <form>
      {foreach $obj->getMarkdownFields() as $field}
        <textarea
          name="$field"
          class="easy-mde"
          readonly
          rows="10">{$obj->$field|escape}</textarea>
      {/foreach}
    </form>

    <div class="mt-3 text-muted">
      {t}info-markdown-code{/t}
    </div>
  </div>
{/block}
