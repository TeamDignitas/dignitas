{* edit / suggest edit button for objects that admit pending edits *}
{$class=$class|default:"btn btn-outline-secondary mt-1"}

{$editable=$obj->isEditable()}
{$suggestable=$obj->acceptsSuggestions()}

{if $editable || $suggestable}
  {capture "msg"}
  {if $editable}
    {t}link-edit{/t}
  {else}
    {t}link-suggest-edit{/t}
  {/if}
  {/capture}

  <a
    href="{$obj->getEditUrl()}"
    class="{$class}"
    title="{$smarty.capture.msg}">
    <i class="icon icon-edit"></i>
  </a>
{/if}
