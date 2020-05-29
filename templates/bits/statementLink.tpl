{$class=$class|default:''}
{$quotes=$quotes|default:true}

{strip}

{if $statement}
  <a href="{$statement->getViewUrl()}" class="{$class}">
    {if $quotes}
      {t 1=$statement->summary}
      quoted-string-%1
      {/t}
    {else}
      {$statement->summary|escape}
    {/if}
  </a>
  {$statusInfo=$statement->getStatusInfo()}
  {if $statusInfo}
    &nbsp;
    [{$statusInfo['status']}]
  {/if}
{/if}

{/strip}
