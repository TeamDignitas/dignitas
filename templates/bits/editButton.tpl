{* edit / suggest edit button for objects that admit pending edits *}
{$class=$class|default:"btn btn-light"}

{$editable=$obj->isEditable()}
{$suggestable=$obj->acceptsSuggestions()}

{capture "suggestMsg"}
{t}You do not have enough reputation to make changes directly. You can suggest
changes which will be placed in the review queue.{/t}
{/capture}

{if $editable || $suggestable}
  <a
    href="{Router::getEditLink($obj)}"
    class="{$class}"
    {if !$editable}title="{$smarty.capture.suggestMsg}"{/if}
  >
    <i class="icon icon-edit"></i>
    {if $editable}
      {t}edit{/t}
    {else}
      {t}suggest an edit{/t}
    {/if}
  </a>
{/if}
