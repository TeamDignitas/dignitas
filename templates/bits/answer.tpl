{* mandatory argument: $answer *}
{$addComment=$addComment|default:false}
{$ellipsisMenu=$ellipsisMenu|default:true}
{$voteBox=$voteBox|default:true}

<div class="answer-container col-12 col-md-8 pt-2" id="a{$answer->id}">
  <div class="container px-0">
    <div class="row">
      {if $answer->status == Ct::STATUS_DRAFT}
        {include "bits/draftIndicator.tpl"}
      {elseif $voteBox}
        {capture "tooltipDownvote"}
          {t
            count=-Config::REP_DOWNVOTE_ANSWER
            1=-Config::REP_DOWNVOTE_ANSWER
            plural="tooltip-downvote-answer-plural-%1"}
            tooltip-downvote-answer-singular-%1
          {/t}
        {/capture}
        {include "bits/scoreAndVote.tpl"
          type=Vote::TYPE_ANSWER
          object=$answer
          upvotePriv=User::PRIV_UPVOTE_ANSWER
          downvotePriv=User::PRIV_DOWNVOTE_ANSWER
          tooltipDownvote="{$smarty.capture.tooltipDownvote}"
        } {* no upvote tooltip for now *}
      {/if}

      <div class="col-12 col-md-11 mt-2 mb-1">
        <div class="archivable px-0">
          {$answer->contents|md}
        </div>

        {if $answer->status == Ct::STATUS_DELETED}
          {notice icon="info"}
            {$answer->getDeletedMessage()}

            {if $answer->reason == Ct::REASON_BY_USER}
              {include "bits/userLink.tpl" u=$answer->getStatusUser()}
            {elseif $answer->reason != Ct::REASON_BY_OWNER}
              <hr>
              {include "bits/reviewFlagList.tpl" review=$answer->getRemovalReview()}
            {/if}
          {/notice}
        {/if}

        {if $answer->verdict != Statement::VERDICT_NONE}
          <div class="mb-1">
            <span class="badge rounded-pill bg-secondary py-1 px-2">
              {include "bits/icon.tpl" i=gavel}
              {$answer->getVerdictName()}
            </span>
          </div>
        {/if}

        <div class="text-muted mt-2 mb-2 row align-items-center surface">
          <div class="answer-read-only col-12 col-md-9">
            {t}answer-posted-by{/t}
            {include 'bits/userLink.tpl' u=$answer->getUser()}
            {include 'bits/moment.tpl' t=$answer->createDate}
          </div>

          <div class="col-12 col-md-3 px-0 text-end">
            {$comments=Comment::getFor($answer)}
            {if $addComment && empty($comments)}
              {include "bits/addCommentLink.tpl" object=$answer}
            {/if}

            {if $ellipsisMenu}
              <button
                class="btn"
                type="button"
                id="answer-menu-{$answer->id}"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                {include "bits/icon.tpl" i=more_vert}
              </button>

              <div class="dropdown-menu ellipsis-menu" aria-labelledby="answer-menu-{$answer->id}">
                <a
                  href="#a{$answer->id}"
                  class="dropdown-item"
                  title="{t}info-answer-permalink{/t}">
                  {include "bits/icon.tpl" i=insert_link}
                  {t}link-permalink{/t}
                </a>

                {include "bits/editButton.tpl" obj=$answer class="dropdown-item"}
                {include "bits/subscribeLinks.tpl" obj=$answer class="dropdown-item"}
                {include "bits/flagLinks.tpl" obj=$answer class="dropdown-item"}
                {include "bits/historyButton.tpl" obj=$answer class="dropdown-item"}
                {include "bits/viewMarkdownButton.tpl" obj=$answer}
              </div>
            {/if}
          </div>

        </div>

        {if !empty($comments)}
          <div class="comment-list container px-1">
            {foreach $comments as $comment}
              {include 'bits/comment.tpl'}
            {/foreach}
          </div>

          {if $addComment}
            <div class="text-muted text-end mb-2 ms-0 ps-0">
              {include "bits/addCommentLink.tpl" object=$answer}
            </div>
          {/if}
        {/if}

      </div>
    </div>
  </div>
</div>
