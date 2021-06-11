{$ellipsisMenu=$ellipsisMenu|default:true}
{$voteBox=$voteBox|default:true}

<div class="vote-container comment mb-2 mt-2" id="c{$comment->id}">

  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_COMMENT
      object=$comment
      upvotePriv=User::PRIV_UPVOTE_COMMENT
      downvotePriv=User::PRIV_DOWNVOTE_COMMENT}
  {/if}

  <div class="text-start">
    <span class="archivable">
      {$comment->contents|md}
    </span>
    &mdash;
    {include 'bits/userLink.tpl' u=$comment->getUser()}
    {include 'bits/moment.tpl' t=$comment->createDate}

    {if $ellipsisMenu}
      <button
        class="btn comment-actions"
        type="button"
        id="comment-menu-{$comment->id}"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false">
        {include "bits/icon.tpl" i=more_vert}
      </button>

      <div class="dropdown-menu ellipsis-menu" aria-labelledby="comment-menu-{$comment->id}">
        <a
          href="#c{$comment->id}"
          class="dropdown-item"
          title="{t}info-comment-permalink{/t}">
          {include "bits/icon.tpl" i=insert_link}
          {t}link-permalink{/t}
        </a>

        {if $comment->isDeletable()}
          <a
            href="#"
            class="dropdown-item delete-comment"
            data-comment-id="{$comment->id}"
            data-confirm="{t}info-confirm-delete-comment{/t}">
            {include "bits/icon.tpl" i=delete_forever}
            {t}link-delete{/t}
          </a>
        {/if}

        {include "bits/flagLinks.tpl" obj=$comment class="dropdown-item"}
      </div>
    {/if}

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
