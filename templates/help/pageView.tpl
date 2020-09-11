{extends "layout.tpl"}

{block "title"}{cap}{$page->title}{/cap} - {cap}{t}help-center{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <div class="row">
      <div class="col-md-8 mb-3">

        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="{Router::link('help/index')}">
                {cap}{t}help-center{/t}{/cap}
              </a>
            </li>
            <li class="breadcrumb-item">
              <a href="{$category->getViewUrl()}">
                {cap}{$category->name}{/cap}
              </a>
            </li>
          </ol>
        </nav>

        <h1 class="mb-4">{$page->title}</h1>

        {$page->contents|md}


        {if User::isModerator()}
          <div class="mt-2">
            <a
              class="btn btn-outline-secondary mt-1"
              href="{Router::link('help/pageEdit')}/{$page->id}"
              title="{t}link-edit{/t}">
              <i class="icon icon-pencil"></i>
            </a>
            {include "bits/historyButton.tpl" obj=$page}
          </div>
        {/if}
      </div>

      <div class="col-md-4">
        {include "bits/helpSidebar.tpl" activePageId=$page->id}
      </div>
    </div>
  </div>
{/block}
