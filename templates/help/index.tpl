{extends "layout.tpl"}

{block "title"}{cap}{t}help-center{/t}{/cap}{/block}

{block "content"}
  <h3>{cap}{t}help-center{/t}{/cap}</h3>

  {foreach HelpCategory::loadAll() as $cat}
    <h4>{cap}{$cat->name}{/cap}</h4>
    {foreach $cat->getPages() as $p}
      <div>
        <a href="{Router::helpLink($p)}">{$p->title}</a>
      </div>
    {/foreach}
  {/foreach}
{/block}
