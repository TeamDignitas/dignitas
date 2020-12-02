{$voteBox=$voteBox|default:true}

<div class="vote-container comment mb-2 mt-2" id="c{$comment->id}">

  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_COMMENT
      object=$comment
      upvotePriv=User::PRIV_UPVOTE_COMMENT
      downvotePriv=User::PRIV_DOWNVOTE_COMMENT}
  {/if}

  <div class="text-left">
    {$comment->contents|md}
    &mdash;
    {include 'bits/userLink.tpl' u=$comment->getUser()}
    {include 'bits/moment.tpl' t=$comment->createDate}

    <span class="comment-actions">
      <a
        href="#c{$comment->id}"
        title="{t}info-comment-permalink{/t}">
        <i class="icon icon-link"></i>
      </a>

      {if $comment->isDeletable()}
        <a
          href="#"
          class="delete-comment"
          data-comment-id="{$comment->id}"
          data-confirm-msg="{t}info-confirm-delete-comment{/t}">
          <i class="icon icon-trash"></i>
        </a>
      {/if}

      {include "bits/flagLinks.tpl" obj=$comment iconOnly=true}
    </span>

    {if $comment->status == Ct::STATUS_DELETED}
      <div class="alert alert-secondary">
        {$comment->getDeletedMessage()}

        {if $comment->reason == Ct::REASON_BY_USER}
          {include "bits/userLink.tpl" u=$comment->getStatusUser()}
        {elseif $comment->reason != Ct::REASON_BY_OWNER}
          <hr>
          {include "bits/reviewFlagList.tpl" review=$comment->getRemovalReview()}
        {/if}
      </div>
    {/if}
  </div>

</div>
