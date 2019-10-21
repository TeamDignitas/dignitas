{extends "layout.tpl"}

{block "title"}{cap}{t}{Queue::getDescription($queueType)}{/t}{/cap}{/block}

{block "content"}
  <h3>{t}{Queue::getDescription($queueType)}{/t}</h3>

  {if isset($object)}
    {if $object instanceof Statement}

      {include "bits/statement.tpl"
        statement=$object
        flagBox=false
        voteBox=false}

    {elseif $object instanceof Answer}

      {include "bits/answer.tpl"
        answer=$object
        flagBox=false
        voteBox=false}

      {include "bits/statement.tpl"
        statement=$object->getStatement()
        flagBox=false
        voteBox=false}

    {/if}
  {else}
    {t}This queue is empty.{/t}
  {/if}
{/block}
