{* $type: type of object being voted *}
{* $object: object being voted (should have id and score fields) *}

{$voteValue=$object->getVote()->value|default:0}

{* include the various popover messages just once *}
{if !isset($VOTE_POPOVER_MESSAGES_ONCE)}
  {$VOTE_POPOVER_MESSAGES_ONCE=1 scope="global"}
  <div id="vote-popover-messages" style="display: none">
    <div class="title">
      {t}vote-popover-title{/t}
      <a href="#" class="close" data-dismiss="alert">&times;</a>
    </div>
    {if User::needsStatementVoteReminder()}
      <div class="body-statement">
        {t}vote-popover-statement{/t}
      </div>
    {/if}
    {if User::needsDownvoteReminder()}
      <div class="body-downvote">
        {t}vote-popover-downvote{/t}
      </div>
    {/if}
  </div>
{/if}

<div class="vote-box col-sm-12 col-md-1">

  <button
    class="btn btn-vote {if $voteValue == 1}voted{/if}"
    {if !User::getActive()}
    disabled
    title="{t}label-log-in-vote{/t}"
    {elseif !User::may($upvotePriv)}
    disabled
    title="{t 1=$upvotePriv|nf}label-minimum-reputation-upvote{/t}"
    {else}
    data-toggle="popover"
    {/if}
    data-type="{$type}"
    data-object-id="{$object->id}"
    data-score-id="#score-{$type}-{$object->id}"
    data-value="1">
    <i class="icon icon-thumbs-up-alt"></i>
  </button>

  <div id="score-{$type}-{$object->id}">{$object->getScore()|nf}</div>

  <button
    class="btn btn-vote {if $voteValue == -1}voted{/if}"
    {if !User::getActive()}
    disabled
    title="{t}label-log-in-vote{/t}"
    {elseif !User::may($downvotePriv)}
    disabled
    title="{t 1=$downvotePriv|nf}label-minimum-reputation-downvote{/t}"
    {else}
    data-toggle="popover"
    {/if}
    data-type="{$type}"
    data-object-id="{$object->id}"
    data-score-id="#score-{$type}-{$object->id}"
    data-value="-1">
    <i class="icon icon-thumbs-down-alt"></i>
  </button>

  {**
    * Show the "proof" icon for answers in two situations:
    * 1. Answer is accepted as proof (read-only).
    * 2. User is moderator and can accept/unaccept answers as proof (clickable).
    **}
  {$isAnswer=($object->getObjectType() == Proto::TYPE_ANSWER)}
  {if $isAnswer && (User::isModerator() || $object->proof)}
    {* Move tooltip management to a wrapper div. Bootstrap recommends this *}
    {* approach when the underlying button may be disabled. *}
    <div
      data-toggle="tooltip"
      data-trigger="hover"
      {if User::isModerator()}
      title="{t}label-toggle-answer-proof{/t}"
      {else}
      title="{t}label-answer-is-proof{/t}"
      {/if}
    >
      <button
        class="btn btn-proof {if $object->proof}accepted{/if}"
        data-answer-id="{$object->id}"
        {if !User::isModerator()}disabled style="pointer-events: none;"{/if}
      >
        <i class="icon icon-ok"></i>
      </button>
    </div>
  {/if}

</div>
