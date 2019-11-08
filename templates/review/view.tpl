{extends "layout.tpl"}

{block "title"}{cap}{t}{Review::getDescription($reason)}{/t}{/cap}{/block}

{block "content"}
  <h3>{t}{Review::getDescription($reason)}{/t}</h3>

  {if isset($review)}
    {$object=$review->getObject()}
    {$type=$object->getObjectType()}
    {if $type == BaseObject::TYPE_STATEMENT}

      {include "bits/reviewActions.tpl"}

      {if $review->reason == Review::REASON_DUPLICATE}
        <h5>
          {t}This statement was flagged as a duplicate of{/t}
          {include "bits/statementLink.tpl" statement=$review->getDuplicate()}
        </h5>
      {/if}

      {include "bits/statement.tpl"
        statement=$object
        flagBox=true
        voteBox=true}

    {elseif $type == BaseObject::TYPE_ANSWER}

      {include "bits/reviewActions.tpl"}

      {include "bits/answer.tpl"
        answer=$object
        flagBox=true
        voteBox=true}

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

  {include "bits/flagModal.tpl"}

{/block}
