{extends "layout.tpl"}

{block "title"}{cap}{t}help-center{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}help-center{/t}{/cap}</h1>

    {if User::isModerator()}
      <div class="my-4">
        <a class="btn btn-sm btn-primary col-12 col-md-4 col-lg-3 me-2 mb-2" href="{Router::link('help/categoryEdit')}">
          {include "bits/icon.tpl" i=add_circle}
          {t}link-add-category{/t}
        </a>

        {if count($categories)}
          <a class="btn btn-sm btn-outline-secondary col-12 col-md-4 col-lg-3 mb-2" href="{Router::link('help/pageEdit')}">
            {include "bits/icon.tpl" i=add_circle}
            {t}link-add-help-page{/t}
          </a>
        {/if}
      </div>
    {/if}

    {foreach $categories as $cat}
      <h4 class="mt-3">{cap}{$cat->getName()}{/cap}</h4>
      <ul>
        {foreach $cat->getPages() as $p}
          <li>
            <a href="{$p->getViewUrl()}">{$p->getTitle()}</a>
          </li>
        {/foreach}
      </ul>
    {/foreach}

    {if User::isModerator()}
      <div class="my-4">
        <a
          class="btn btn-sm btn-outline-secondary col-12 col-md-4 col-lg-3 mb-2"
          href="{Router::link('help/categoryList')}">
          {t}link-reorder-help-categories{/t}
        </a>
      </div>
    {/if}
  </div>
{/block}
