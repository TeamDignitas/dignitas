{strip}

{if $statement}
  <a href="{Router::link('statement/view')}/{$statement->id}">
    {$statement->summary|escape}
  </a>
{/if}

{/strip}
