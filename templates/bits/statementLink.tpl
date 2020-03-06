{$class=$class|default:''}

{strip}

{if $statement}
  &quot;<a href="{Router::link('statement/view')}/{$statement->id}" class="{$class}">
    {$statement->summary|escape}
  </a>&quot;
  {$statusInfo=$statement->getStatusInfo()}
  {if $statusInfo}
    &nbsp;
    [{$statusInfo['status']}]
  {/if}
{/if}

{/strip}
