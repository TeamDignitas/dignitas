{* Recursively displays a tag tree (or forest). *}
{if $tags}
  <ul>
    {foreach $tags as $t}
      <li>
        <i class="expand icon {if count($t->children)}icon-down-open{/if}"></i>
        {include "bits/tag.tpl" link=$link}
        {include "bits/tagTree.tpl" tags=$t->children link=$link}
      </li>
    {/foreach}
  </ul>
{/if}
