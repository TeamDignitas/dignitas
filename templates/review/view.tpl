{extends "layout.tpl"}

{block "title"}{cap}{Review::getDescription($reason)}{/cap}{/block}

{block "content"}
  <h3>{Review::getDescription($reason)}</h3>

  {if isset($review)}
    {$object=$review->getObject()}
    {$type=$object->getObjectType()}

    {* Display the action bar or a message explaining the lack of the action bar. *}
    {if $review->status == Review::STATUS_PENDING}
      {include "bits/reviewActions.tpl"}
    {else}
      <p class="text-warning">
        {t}info-review-complete{/t}
      </p>
    {/if}

    {* Display the object being reviewed. *}
    {if isset($objectDiff)}
      {include "bits/diff/objectDiff.tpl" od=$objectDiff}

    {elseif $type == Proto::TYPE_STATEMENT}

      {if $review->reason == Ct::REASON_DUPLICATE}
        <div class="alert alert-warning">
          {t}info-statement-duplicate{/t}
          {include "bits/statementLink.tpl" statement=$review->getDuplicate()}
        </div>
      {/if}

      {include "bits/statement.tpl" statement=$object addComment=true}

    {elseif $type == Proto::TYPE_ANSWER}

      {include "bits/answer.tpl" answer=$object addComment=true}

      <h3>{cap}{t}title-pertaining-to-statement{/t}{/cap}</h3>

      <div id="parent-object">
        {include "bits/statement.tpl"
          statement=$object->getStatement()
          flagLinks=false
          voteBox=false}
      </div>

    {elseif $type == Proto::TYPE_ENTITY}

      {if $review->reason == Ct::REASON_DUPLICATE}
        <div class="alert alert-warning">
          {t}info-entity-duplicate{/t}
          {include "bits/entityLink.tpl" e=$review->getDuplicate()}.

          {t}info-entity-duplicate-process{/t}
        </div>
      {/if}

      {include "bits/entity.tpl" entity=$object}

    {elseif $type == Proto::TYPE_COMMENT}

      {include "bits/comment.tpl" comment=$object}

      {$parent=$object->getObject()} {* What a nice sentence *}

      {if $parent instanceof Answer}

        <h3>{cap}{t}title-pertaining-to-answer{/t}{/cap}</h3>

        <div id="parent-object">
          {include "bits/answer.tpl"
            answer=$parent
            flagLinks=false
            voteBox=false}
        </div>

        <h3>{cap}{t}title-pertaining-to-statement{/t}{/cap}</h3>

        <div id="parent-object">
          {include "bits/statement.tpl"
            statement=$parent->getStatement()
            flagLinks=false
            voteBox=false}
        </div>

      {else}  {* Comment on a statement *}

        <h3>{cap}{t}title-pertaining-to-statement{/t}{/cap}</h3>

        <div id="parent-object">
          {include "bits/statement.tpl"
            statement=$parent
            flagLinks=false
            voteBox=false}
        </div>

      {/if}

    {/if}

    <hr>

    {include "bits/reviewFlags.tpl"}

  {else}
    {t}info-review-queue-empty{/t}
  {/if}

  {include "bits/commentForm.tpl"}
  {include "bits/flagModal.tpl"}

{/block}
