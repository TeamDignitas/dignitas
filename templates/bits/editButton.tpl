{* edit / suggest edit button for objects that admit pending edits *}
{$class=$class|default:"btn btn-light"}

{$editable=$obj->isEditable()}
{$suggestable=$obj->acceptsSuggestions()}

{capture "suggestMsg"}
{t}info-not-enough-reputation-change-directly{/t}
{/capture}

{if $editable || $suggestable}
  <a
    href="{Router::getEditLink($obj)}"
    class="{$class}"
    {if !$editable}title="{$smarty.capture.suggestMsg}"{/if}
  >
    <i class="icon icon-edit"></i>
    {if $editable}
      {t}link-edit{/t}
    {else}
      {t}link-suggest-edit{/t}
    {/if}
  </a>
{/if}
