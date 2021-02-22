{extends "layout.tpl"}

{block "title"}{cap}{Review::getDescription($reason)}{/cap}{/block}

{block "content"}
  <div class="container my-5">
    <h1 class="mb-4">{Review::getDescription($reason)}</h1>

    <div class="review-message">
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

          <h3 class="mt-5 mb-3">
            {t}info-review-target-item{/t}
          </h3>
        {/if}

        {if $type == Proto::TYPE_STATEMENT}

          {if $review->reason == Ct::REASON_DUPLICATE}
            <div class="alert alert-warning">
              {t}info-statement-duplicate{/t}
              {include "bits/statementLink.tpl" statement=$review->getDuplicate()}
            </div>
          {/if}

          <div class="mr-1 callout">
            {include "bits/statement.tpl"
              statement=$object
              addComment=true
              ellipsisMenu=false
              permalink=true}
          </div>

        {elseif $type == Proto::TYPE_ANSWER}

          <div class="border border-dark px-4 py-4">
            {include "bits/answer.tpl"
              answer=$object
              addComment=true
              ellipsisMenu=false}
          </div>

          <h5 class="mt-5 mb-3">{cap}{t}title-pertaining-to-statement{/t}{/cap}</h5>

          <div id="parent-object" class="callout">
            {include "bits/statement.tpl"
              statement=$object->getStatement()
              ellipsisMenu=false
              flagLinks=false
              permalink=true
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

          {include "bits/entity.tpl"
            entity=$object
            ellipsisMenu=false
            showAddStatementButton=false}

        {elseif $type == Proto::TYPE_COMMENT}

          {include "bits/comment.tpl" comment=$object ellipsisMenu=false}

          {$parent=$object->getObject()} {* What a nice sentence *}

          {if $parent instanceof Answer}

            <h3>{cap}{t}title-pertaining-to-answer{/t}{/cap}</h3>

            <div id="parent-object" class="border border-dark px-4 py-4">
              {include "bits/answer.tpl"
                answer=$parent
                ellipsisMenu=false
                flagLinks=false
                voteBox=false}
            </div>

            <h3>{cap}{t}title-pertaining-to-statement{/t}{/cap}</h3>

            <div id="parent-object">
              {include "bits/statement.tpl"
                statement=$parent->getStatement()
                ellipsisMenu=false
                flagLinks=false
                permalink=true
                voteBox=false}
            </div>

          {else}  {* Comment on a statement *}

            <h3>{cap}{t}title-pertaining-to-statement{/t}{/cap}</h3>

            <div id="parent-object">
              {include "bits/statement.tpl"
                statement=$parent
                ellipsisMenu=false
                flagLinks=false
                permalink=true
                voteBox=false}
            </div>

          {/if}

        {/if}

        {include "bits/reviewFlags.tpl"}

      {else}
        {t}info-review-queue-empty{/t}
      {/if}

      {include "bits/commentForm.tpl"}
      {include "bits/flagModal.tpl"}
    </div>
  </div>
{/block}
