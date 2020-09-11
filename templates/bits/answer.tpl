{* mandatory argument: $answer *}
{$voteBox=$voteBox|default:true}
{$addComment=$addComment|default:false}

<div class="row row-cols-2 vote-container answer" id="a{$answer->id}">
  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_ANSWER
      object=$answer
      upvotePriv=User::PRIV_UPVOTE_ANSWER
      downvotePriv=User::PRIV_DOWNVOTE_ANSWER}
  {/if}

  <div class="col-md-7 col-sm-10 mb-1 px-0">
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
        <div class="col-md-7">
          {t}answer-posted-by{/t}
          {include 'bits/userLink.tpl' u=$answer->getUser()}
          {include 'bits/moment.tpl' t=$answer->createDate}
        </div>
        {if $answer->verdict != Ct::VERDICT_NONE}
          <div class="col-md-5 mb-1 text-right">
            <span class="badge badge-pill badge-secondary">
              <i class="icon icon-hammer"></i>
              {$answer->getVerdictName()}
            </span>
          </div>
        {/if}
      </div>

      {$comments=Comment::getFor($answer)}
      <div class="text-muted text-left mb-2 ml-0 pl-0">
        <a
          href="#a{$answer->id}"
          class="btn btn-outline-secondary mt-1"
          title="{t}info-answer-permalink{/t}">
          <i class="icon icon-link"></i>
        </a>

        {if $answer->hasRevisions()}
          <a
            href="{Router::link('answer/history')}/{$answer->id}"
            class="btn btn-outline-secondary mt-1"
            title="{t}link-show-revisions{/t}">
            <i class="icon icon-hourglass"></i>
          </a>
        {/if}

        {include "bits/editButton.tpl" obj=$answer}
        {include "bits/flagLinks.tpl" obj=$answer class="btn btn-outline-secondary mt-1"}

        {if $addComment && empty($comments)}
          {include "bits/addCommentLink.tpl" object=$answer}
        {/if}
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
  <div class="border-bottom col-md-8"></div>
</div>
