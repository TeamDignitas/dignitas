{$class=$class|default:''}

{strip}

{if $statement}
  <a href="{Router::link('statement/view')}/{$statement->id}" class="{$class}">
    {t 1=$statement->summary}
    quoted-string-%1
    {/t}
  </a>
  {$statusInfo=$statement->getStatusInfo()}
  {if $statusInfo}
    &nbsp;
    [{$statusInfo['status']}]
  {/if}
{/if}

{/strip}
