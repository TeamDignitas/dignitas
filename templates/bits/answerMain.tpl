<div>
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

<div class="answerFooter">
  <ul class="list-inline text-muted">
    <li class="list-inline-item">
      {t}posted by{/t}
      {include 'bits/userLink.tpl' u=$answer->getUser()}
      {include 'bits/moment.tpl' t=$answer->createDate}
    </li>

    {if $answer->hasRevisions()}
      <li class="list-inline-item">
        <a href="{Router::link('answer/history')}/{$answer->id}" class="btn btn-sm btn-link">
          {t}show revisions{/t}
        </a>
      </li>
    {/if}

    {if $answer->isDeletable()}
      <li class="list-inline-item">
        <a
          href="?deleteAnswerId={$answer->id}"
          class="btn btn-sm btn-link"
          data-confirm="{t}Are you sure you want to delete this answer?{/t}">
          {t}delete{/t}
        </a>
      </li>
    {/if}

    <li class="list-inline-item">
      {include "bits/editButton.tpl" obj=$answer class="btn btn-sm btn-link"}
    </li>

    {if $flagBox && ($answer->isFlaggable() || $answer->isFlagged())}
      <li class="list-inline-item">
        {include "bits/flagLinks.tpl" obj=$answer class="btn btn-sm btn-link"}
      </li>
    {/if}
  </ul>
</div>

{if $showComments}
  {foreach Comment::getFor($answer) as $comment}
    {include 'bits/comment.tpl'}
  {/foreach}
{/if}

{if $addComment}
  {include "bits/addCommentLink.tpl" object=$answer}
{/if}
