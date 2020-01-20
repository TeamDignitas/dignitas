{* mandatory argument: $answer *}
{$highlighted=$highlighted|default:false}
{$flagBox=$flagBox|default:true}
{$voteBox=$voteBox|default:true}
<div class="answer clearfix {if $highlighted}highlightedAnswer{/if}">
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
        {include "bits/reviewFlagList.tpl" obj=$answer}
      {/if}
    </div>
  {/if}

  {if $voteBox}
    {include "bits/scoreAndVote.tpl"
      type=Vote::TYPE_ANSWER
      object=$answer
      upvotePriv=User::PRIV_UPVOTE_ANSWER
      downvotePriv=User::PRIV_DOWNVOTE_ANSWER
      classes="float-left"}
  {/if}

  <ul class="list-inline text-muted float-right">
    <li class="list-inline-item">
      {t}posted by{/t}
      {include 'bits/userLink.tpl' u=$answer->getUser()}
      {$answer->createDate|moment}
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
