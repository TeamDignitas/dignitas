{extends "layout.tpl"}

{block "title"}{cap}{$page->title}{/cap} - {cap}{t}help-center{/t}{/cap}{/block}

{block "content"}
  <div class="row">
    <div class="col-8">

      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{Router::link('help/index')}">
              {cap}{t}help-center{/t}{/cap}
            </a>
          </li>
          <li class="breadcrumb-item">
            <a href="{Router::helpLink($category)}">
              {cap}{$category->name}{/cap}
            </a>
          </li>
        </ol>
      </nav>

      <h3>{$page->title}</h3>

      {$page->contents|md}


      {if User::isModerator()}
        <div class="mt-2">
          <a
            class="btn btn-sm btn-outline-secondary"
            href="{Router::link('help/pageEdit')}/{$page->id}">
            <i class="icon icon-edit"></i>
            {t}link-edit{/t}
          </a>
          {if $page->hasRevisions()}
            <a
              class="btn btn-sm btn-outline-secondary"
              href="{Router::link('help/pageHistory')}/{$page->id}">
              {t}link-show-revisions{/t}
            </a>
          {/if}
        </div>
      {/if}
    </div>

    <div class="col-4">
      {include "bits/helpSidebar.tpl" activePageId=$page->id}
    </div>
  </div>
{/block}
