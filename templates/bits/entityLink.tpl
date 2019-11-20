{$showStatus=$showStatus|default:false}
{strip}
<a href="{Router::link('entity/view')}/{$e->id}">
  {$e->name|escape}
</a>
{if $showStatus}
  {$statusInfo=$e->getStatusInfo()}
  {if $statusInfo}
    &nbsp;
    [{$statusInfo['status']}]
  {/if}
{/if}
{strip}
