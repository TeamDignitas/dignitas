{extends "layout.tpl"}

{block "title"}{cap}{$category->name}{/cap} - {cap}{t}help-center{/t}{/cap}{/block}

{block "content"}
  <h4>
    <a href="{Router::link('help/index')}">{cap}{t}help-center{/t}{/cap}</a>
    <i class="icon icon-right-open small text-muted"></i>
    <a href="{Router::helpLink($category)}">{cap}{$category->name}{/cap}</a>
  </h4>

  {foreach $category->getPages() as $p}
    <div>
      <a href="{Router::helpLink($p)}">{$p->title}</a>
    </div>
  {/foreach}
{/block}
