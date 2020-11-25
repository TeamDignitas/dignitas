{* mandatory argument: $answer *}
{$voteBox=$voteBox|default:true}
{$addComment=$addComment|default:false}

<div class="answer-container row pt-2" id="a{$answer->id}">
  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_ANSWER
      object=$answer
      upvotePriv=User::PRIV_UPVOTE_ANSWER
      downvotePriv=User::PRIV_DOWNVOTE_ANSWER}
  {/if}

  <div class="col-sm-12 col-md-11 mb-1">
    <div class="answer-body col-md-12 px-0">
      {$answer->contents|md}
    </div>

    {if $answer->status == Ct::STATUS_DELETED}
      <div class="alert alert-secondary small">
        {$answer->getDeletedMessage()}

        {if $answer->reason == Ct::REASON_BY_USER}
          {include "bits/userLink.tpl" u=$answer->getStatusUser()}
        {elseif $answer->reason != Ct::REASON_BY_OWNER}
          <hr>
          {include "bits/reviewFlagList.tpl" review=$answer->getRemovalReview()}
        {/if}
      </div>
    {/if}

    <div class="answer-footer col-md-12 px-0">
      <div class="answer-read-only text-muted mb-2 row">
        <div class="col-md-6">
          {t}answer-posted-by{/t}
          {include 'bits/userLink.tpl' u=$answer->getUser()}
          {include 'bits/moment.tpl' t=$answer->createDate}
        </div>

        {if $answer->verdict != Ct::VERDICT_NONE}
          <div class="col-md-4 mb-1">
            <span class="badge badge-pill badge-secondary">
              <i class="icon icon-hammer"></i>
              {$answer->getVerdictName()}
            </span>
          </div>
        {/if}

        <div class="col-md-2 text-right">
          {if $addComment && empty($comments)}
            {include "bits/addCommentLink.tpl" object=$answer}
          {/if}

          {$comments=Comment::getFor($answer)}
          <button class="btn pt-0" type="button" id="ellipsisMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="icon icon-plus"></i>
          </button>

          <div class="dropdown-menu" aria-labelledby="ellipsisMenu">
            <button class="dropdown-item" type="button">
              <a href="#a{$answer->id}"
                 class="btn"
                 title="{t}info-answer-permalink{/t}">
                <i class="icon icon-link"></i>
              </a>
            </button>

            <button class="dropdown-item" type="button">
              {include "bits/editButton.tpl" obj=$answer}
            </button>

            <button class="dropdown-item" type="button">
              {include "bits/subscribeLinks.tpl" obj=$answer}
            </button>

            <button class="dropdown-item" type="button">
              {include "bits/flagLinks.tpl" obj=$answer class="btn mt-1"}
            </button>

            <button class="dropdown-item" type="button">
              {include "bits/historyButton.tpl" obj=$answer}
            </button>
          </div>
        </div>
      </div>

    </div>

    {if !empty($comments)}
      <div class="comment-list">
        {foreach $comments as $comment}
          {include 'bits/comment.tpl'}
        {/foreach}
      </div>

      {if $addComment}
        <div class="text-muted text-right mb-2 ml-0 pl-0">
          {include "bits/addCommentLink.tpl" object=$answer}
        </div>
      {/if}
    {/if}

  </div>
</div>
<div class="border-bottom"></div>
