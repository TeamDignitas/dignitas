{extends "layout.tpl"}

{block "title"}{cap}{$category->name}{/cap} - {cap}{t}help-center{/t}{/cap}{/block}

{block "content"}
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-8 mb-3">

        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="{Router::link('help/index')}">
                {cap}{t}help-center{/t}{/cap}
              </a>
            </li>
            <li class="breadcrumb-item active">
              {cap}{$category->name}{/cap}
            </li>
          </ol>
        </nav>

        <h1 class="my-4">{$category->name}</h1>

        <ul>
        {foreach $category->getPages() as $p}
          <li class="pl-2">
            <a href="{$p->getViewUrl()}">{$p->title}</a>
          </li>
        {/foreach}
      </ul>

        {if User::isModerator()}
          <div class="mt-4">
            <a
              class="btn btn-sm btn-outline-primary"
              href="{Router::link('help/categoryEdit')}/{$category->id}">
              <i class="icon icon-edit"></i>
              {t}link-edit{/t}
            </a>
          </div>
        {/if}
      </div>

      <div class="col-md-4">
        {include "bits/helpSidebar.tpl" activeCategoryId=$category->id}
      </div>
    </div>
  </div>
{/block}
