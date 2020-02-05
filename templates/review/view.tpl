{extends "layout.tpl"}

{block "title"}{cap}{t}{Review::getDescription($reason)}{/t}{/cap}{/block}

{block "content"}
  <h3>{t}{Review::getDescription($reason)}{/t}</h3>

  {if isset($review)}
    {$object=$review->getObject()}
    {$type=$object->getObjectType()}

    {* Display the action bar or a message explaining the lack of the action bar. *}
    {if $review->status == Review::STATUS_PENDING}
      {include "bits/reviewActions.tpl"}
    {else}
      <p>
        {t}This review is now complete. No further actions can be taken.{/t}
      </p>
    {/if}

    {* Display the object being reviewed. *}
    {if isset($objectDiff)}
      {include "bits/diff/objectDiff.tpl" od=$objectDiff}

    {elseif $type == BaseObject::TYPE_STATEMENT}

      {if $review->reason == Ct::REASON_DUPLICATE}
        <div class="alert alert-warning">
          {t}This statement was flagged as a duplicate of{/t}
          {include "bits/statementLink.tpl" statement=$review->getDuplicate()}
        </div>
      {/if}

      {include "bits/statement.tpl" statement=$object flagBox=false}

    {elseif $type == BaseObject::TYPE_ANSWER}

      {include "bits/answer.tpl" answer=$object flagBox=false}

      <h3>{cap}{t}pertaining to statement:{/t}{/cap}</h3>

      <div id="parentObject">
        {include "bits/statement.tpl"
          statement=$object->getStatement()
          flagBox=false
          voteBox=false}
      </div>

    {elseif $type == BaseObject::TYPE_ENTITY}

      {if $review->reason == Ct::REASON_DUPLICATE}
        <div class="alert alert-warning">
          {t}This entity was flagged as a duplicate of{/t}
          {include "bits/entityLink.tpl" e=$review->getDuplicate()}.

          {t}Accepting this flag will transfer all statements from this
          duplicate to its canonical entity. The aliases, relationships and
          other attributes will not be transferred.{/t}
        </div>
      {/if}

      {include "bits/entity.tpl" entity=$object flagBox=false}

    {elseif $type == BaseObject::TYPE_COMMENT}

      {include "bits/comment.tpl" comment=$object flagBox=false}

      {$parent=$object->getObject()} {* What a nice sentence *}

      {if $parent instanceof Answer}

        <h3>{cap}{t}pertaining to answer:{/t}{/cap}</h3>

        <div id="parentObject">
          {include "bits/answer.tpl"
            answer=$parent
            flagBox=false
            voteBox=false
            showComments=false}
        </div>

        <h3>{cap}{t}pertaining to statement:{/t}{/cap}</h3>

        <div id="parentObject">
          {include "bits/statement.tpl"
            statement=$parent->getStatement()
            flagBox=false
            voteBox=false}
        </div>

        {else}  {* Comment on a statement *}

          <h3>{cap}{t}pertaining to statement:{/t}{/cap}</h3>

          <div id="parentObject">
            {include "bits/statement.tpl"
              statement=$parent
              flagBox=false
              voteBox=false}
          </div>

        {/if}

    {/if}

    <hr>

    {include "bits/reviewFlags.tpl"}

  {else}
    {t}This review queue is empty.{/t}
  {/if}

  {include "bits/flagModal.tpl"}

{/block}
