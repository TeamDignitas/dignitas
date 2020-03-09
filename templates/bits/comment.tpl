{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}

<div class="vote-container comment mb-2 mt-2">

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
      {if $comment->isDeletable()}
        <a
          href="#"
          class="delete-comment"
          data-comment-id="{$comment->id}"
          data-confirm-msg="{t}info-confirm-delete-comment{/t}">
          <i class="icon icon-trash"></i>
        </a>
      {/if}

      {if $flagBox && ($comment->isFlaggable() || $comment->isFlagged())}
        {include "bits/flagLinks.tpl" obj=$comment tiny=true}
      {/if}
    </span>

  </div>

</div>
