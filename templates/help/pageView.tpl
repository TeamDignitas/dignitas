{extends "layout.tpl"}

{block "title"}{cap}{$page->title}{/cap} - {cap}{t}help-center{/t}{/cap}{/block}

{block "content"}
  <h4>
    <a href="{Router::link('help/index')}">{cap}{t}help-center{/t}{/cap}</a>
    <i class="icon icon-right-open small text-muted"></i>
    <a href="{Router::helpLink($category)}">{cap}{$category->name}{/cap}</a>
  </h4>

  <h3>{$page->title}</h3>

  {$page->contents|md}
{/block}
