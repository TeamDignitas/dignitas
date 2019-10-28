{extends "layout.tpl"}

{block "title"}{cap}{t}{Review::getDescription($reason)}{/t}{/cap}{/block}

{block "content"}
  <h3>{t}{Review::getDescription($reason)}{/t}</h3>

  {if isset($object)}
    {if $object->getObjectType() == BaseObject::TYPE_STATEMENT}

      {include "bits/statement.tpl"
        statement=$object
        flagBox=false
        voteBox=false}

      {elseif $object->getObjectType() == BaseObject::TYPE_ANSWER}

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
    {t}This review queue is empty.{/t}
  {/if}
{/block}
