{$class=$class|default:''}

{strip}

{if $statement}
  <a href="{Router::link('statement/view')}/{$statement->id}" class="{$class}">
    {$statement->summary|escape}
  </a>
  {$statusInfo=$statement->getStatusInfo()}
  {if $statusInfo}
    &nbsp;
    [{$statusInfo['status']}]
  {/if}
{/if}

{/strip}
