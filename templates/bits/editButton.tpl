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
    {include "bits/icon.tpl" i=mode_edit class=""}
    {$smarty.capture.msg}
  </a>
{/if}
