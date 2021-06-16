{extends "layout.tpl"}

{block "title"}{cap}{$category->getName()}{/cap} - {cap}{t}help-center{/t}{/cap}{/block}

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
            <li class="breadcrumb-item active">
              {cap}{$category->getName()}{/cap}
            </li>
          </ol>
        </nav>

        <h1 class="my-4">{$category->getName()}</h1>

        <ul>
        {foreach $category->getPages() as $p}
          <li class="ps-2">
            <a href="{$p->getViewUrl()}">{$p->getTitle()}</a>
          </li>
        {/foreach}
      </ul>

        {if User::isModerator()}
          <div class="mt-4">
            <a
              class="btn btn-sm btn-primary"
              href="{Router::link('help/categoryEdit')}/{$category->id}">
              {include "bits/icon.tpl" i=mode_edit}
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
