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
      <div class="col-md-6">
        <span class="btn btn-sm badge-secondary">
          <i class="icon icon-hammer"></i>
          {$answer->getVerdictName()}
        </span>
      </div>
    {/if}
    <div class="col-md-6 text-right">
      {t}answer-posted-by{/t}
      {include 'bits/userLink.tpl' u=$answer->getUser()}
      {include 'bits/moment.tpl' t=$answer->createDate}
    </div>
  </div>

  <ul class="answer-actions text-right text-muted mb-2 ml-0 pl-0">
    {if $answer->hasRevisions()}
      <li class="list-inline-item">
        <a href="{Router::link('answer/history')}/{$answer->id}" class="btn btn-sm btn-outline-secondary">
          {t}link-show-revisions{/t}
        </a>
      </li>
    {/if}

    {if $answer->isDeletable()}
      <li class="list-inline-item">
        <a
          href="?deleteAnswerId={$answer->id}"
          class="btn btn-sm btn-outline-secondary"
          data-confirm="{t}info-confirm-delete-answer{/t}">
          {t}link-delete{/t}
        </a>
      </li>
    {/if}

    <li class="list-inline-item">
      {include "bits/editButton.tpl" obj=$answer class="btn btn-sm btn-outline-secondary"}
    </li>

    {if $flagBox && ($answer->isFlaggable() || $answer->isFlagged())}
      <li class="list-inline-item">
        {include "bits/flagLinks.tpl" obj=$answer class="btn btn-sm btn-outline-secondary"}
      </li>
    {/if}
  </ul>
</div>

<div class="answer-comment col-md-12 px-0 text-right">
  {if $showComments}
    {foreach Comment::getFor($answer) as $comment}
      {include 'bits/comment.tpl'}
    {/foreach}
  {/if}
</div>
