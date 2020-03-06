{extends "layout.tpl"}

{block "title"}{cap}{t}help-center{/t}{/cap}{/block}

{block "content"}
  <h3>{cap}{t}help-center{/t}{/cap}</h3>

  {foreach $categories as $cat}
    <h4>{cap}{$cat->name}{/cap}</h4>
    {foreach $cat->getPages() as $p}
      <div>
        <a href="{Router::helpLink($p)}">{$p->title}</a>
      </div>
    {/foreach}
  {/foreach}


  {if User::isModerator()}
    <div class="mt-2">
      <a class="btn btn-secondary" href="{Router::link('help/categoryList')}">
        {t}link-reorder-help-categories{/t}
      </a>

      <a class="btn btn-secondary" href="{Router::link('help/categoryEdit')}">
        <i class="icon icon-plus"></i>
        {t}link-add-category{/t}
      </a>

      {if count($categories)}
        <a class="btn btn-secondary" href="{Router::link('help/pageEdit')}">
          <i class="icon icon-plus"></i>
          {t}link-add-help-page{/t}
        </a>
      {/if}
    </div>
  {/if}

{/block}
