{$activeCategoryId=$activeCategoryId|default:null}
{$activePageId=$activePageId|default:null}
<div class="help-sidebar">
  {foreach HelpCategory::loadAll() as $c}

    {$active=($c->id == $activeCategoryId)}
    <div class="help-sidebar-category {if $active}active{/if}">
      <a {if !$active}href="{Router::helpLink($c)}"{/if}>{$c->name}</a>
    </div>

    {foreach $c->getPages() as $p}
      {$active=($p->id == $activePageId)}
      <div class="help-sidebar-page {if $active}active{/if}">
        <a {if !$active}href="{Router::helpLink($p)}"{/if}>{$p->title}</a>
      </div>
    {/foreach}

  {/foreach}
</div>
