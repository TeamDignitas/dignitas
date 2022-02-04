{* edit / suggest edit button for objects that admit pending edits *}
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
    class="dropdown-item">
    {include "bits/icon.tpl" i=mode_edit class=""}
    {$smarty.capture.msg}
  </a>
{/if}
