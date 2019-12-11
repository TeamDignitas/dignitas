{* edit / suggest edit button for objects that admit pending edits *}
{$class=$class|default:"btn btn-light"}

{capture assign="buttonText"}
{if $obj->isEditable()}
  {t}edit{/t}
{else if User::canSuggestEdits()}
  {t}suggest an edit{/t}
{/if}
{/capture}

{if $buttonText}
  {if $obj->hasPendingEdit()}
    <button
      class="{$class}"
      disabled
      title="{t}this item already has a pending edit; please wait for it to be reviewed{/t}">
      <i class="icon icon-edit"></i>
      {$buttonText}
    </button>
  {else}
    <a href="{$url}" class="{$class}">
      <i class="icon icon-edit"></i>
      {$buttonText}
    </a>
  {/if}
{/if}
