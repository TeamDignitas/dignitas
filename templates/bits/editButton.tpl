{* edit / suggest edit button for objects that admit pending edits *}
{$class=$class|default:"btn mt-1"}

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
    class="{$class}">
    <i class="icon icon-pencil"></i>
    {$smarty.capture.msg}
  </a>
{/if}
