{* Recursively displays a tag tree (or forest). *}
{$hidden=$hidden|default:false}
{if $tags}
  <ul {if $hidden}style="display: none"{/if}>
    {foreach $tags as $t}
      <li>
        {if count($t->children)}
          {include "bits/icon.tpl" i=expand_more class="expand"}
        {/if}
        {include "bits/tag.tpl" link=$link}
        {include "bits/tagTree.tpl" tags=$t->children link=$link hidden=true}
      </li>
    {/foreach}
  </ul>
{/if}
