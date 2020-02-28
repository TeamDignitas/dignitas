<div class="answer-body col-md-12 px-0">
  {$answer->contents|md}
</div>

{if $answer->status == Ct::STATUS_DELETED}
  <div class="alert alert-secondary">
    {$answer->getDeletedMessage()}

    {if $answer->reason == Ct::REASON_BY_USER}
      {include "bits/userLink.tpl" u=$answer->getStatusUser()}
    {elseif $answer->reason != Ct::REASON_BY_OWNER}
      <hr>
      {include "bits/reviewFlagList.tpl" flags=$answer->getReviewFlags()}
    {/if}
  </div>
{/if}

<div class="answerFooter col-md-12 px-0">
  <div class="answer-read-only text-muted mb-2 row">
    {if $answer->verdict != Ct::VERDICT_NONE}
      <div class="col-md-5 mb-1">
        <span class="badge badge-pill badge-secondary">
          <i class="icon icon-hammer"></i>
          {$answer->getVerdictName()}
        </span>
      </div>
    {/if}
    <div class="col-md-7 text-right">
      {t}answer-posted-by{/t}
      {include 'bits/userLink.tpl' u=$answer->getUser()}
      {include 'bits/moment.tpl' t=$answer->createDate}
    </div>
  </div>

  <div class="answer-actions row text-muted">
    <div class="col-md-12 text-right mb-2 ml-0 pl-0">
      {if $answer->hasRevisions()}
        <a href="{Router::link('answer/history')}/{$answer->id}" class="btn btn-sm btn-outline-secondary">
          {t}link-show-revisions{/t}
        </a>
      {/if}

      {if $answer->isDeletable()}
        <a
          href="?deleteAnswerId={$answer->id}"
          class="btn btn-sm btn-outline-secondary"
          data-confirm="{t}info-confirm-delete-answer{/t}">
          {t}link-delete{/t}
        </a>
      {/if}

      {include "bits/editButton.tpl" obj=$answer class="btn btn-sm btn-outline-secondary"}

      {if $flagBox && ($answer->isFlaggable() || $answer->isFlagged())}
        {include "bits/flagLinks.tpl" obj=$answer class="btn btn-sm btn-outline-secondary"}
      {/if}

      {if $showComments}
        {foreach Comment::getFor($answer) as $comment}
          {include 'bits/comment.tpl'}
        {/foreach}
      {/if}

      {if $addComment}
        {include "bits/addCommentLink.tpl" object=$answer}
      {/if}
    </div>
  </div>
</div>
