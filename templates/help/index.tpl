{extends "layout.tpl"}

{block "title"}{cap}{t}help-center{/t}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{cap}{t}help-center{/t}{/cap}</h1>

    {foreach $categories as $cat}
      <h4 class="mt-3">{cap}{$cat->name}{/cap}</h4>
      <ul>
        {foreach $cat->getPages() as $p}
          <li>
            <a href="{$p->getViewUrl()}">{$p->title}</a>
          </li>
        {/foreach}
      </ul>
    {/foreach}


    {if User::isModerator()}
      <div class="my-4">
        <a class="btn btn-sm btn-outline-secondary mb-2" href="{Router::link('help/categoryList')}">
          <i class="icon icon-sort"></i>
          {t}link-reorder-help-categories{/t}
        </a>

        <a class="btn btn-sm btn-outline-primary mb-2" href="{Router::link('help/categoryEdit')}">
          <i class="icon icon-plus"></i>
          {t}link-add-category{/t}
        </a>

        {if count($categories)}
          <a class="btn btn-sm btn-outline-secondary mb-2" href="{Router::link('help/pageEdit')}">
            <i class="icon icon-plus"></i>
            {t}link-add-help-page{/t}
          </a>
        {/if}
      </div>
    {/if}
  </div>
{/block}
