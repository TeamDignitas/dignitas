{$phrase=$phrase|default:RelationType::PHRASE_REGULAR}
{$showStatus=$showStatus|default:false}
{strip}
{$e->getHyperlink($phrase)}
{if $showStatus}
  {$statusInfo=$e->getStatusInfo()}
  {if $statusInfo}
    &nbsp;
    [{$statusInfo['status']}]
  {/if}
{/if}
{strip}
