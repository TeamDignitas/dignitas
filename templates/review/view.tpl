{extends "layout.tpl"}

{block "title"}{cap}{t}{Review::getDescription($reason)}{/t}{/cap}{/block}

{block "content"}
  <h3>{t}{Review::getDescription($reason)}{/t}</h3>

  {if isset($review)}
    {$object=$review->getObject()}
    {$type=$object->getObjectType()}
    {if $type == BaseObject::TYPE_STATEMENT}

      {include "bits/reviewActions.tpl"}

      {include "bits/statement.tpl"
        statement=$object
        flagBox=false
        voteBox=false}

    {elseif $type == BaseObject::TYPE_ANSWER}

      {include "bits/reviewActions.tpl"}

      {include "bits/answer.tpl"
        answer=$object
        flagBox=false
        voteBox=false}

      <h3>{cap}{t}pertaining to statement:{/t}{/cap}</h3>

      <div id="parentStatement">
        {include "bits/statement.tpl"
          statement=$object->getStatement()
          flagBox=false
          voteBox=false}
      </div>

    {/if}

    <hr>

    {include "bits/reviewFlags.tpl"}

  {else}
    {t}This review queue is empty.{/t}
  {/if}
{/block}
