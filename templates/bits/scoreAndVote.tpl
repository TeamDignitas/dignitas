{* $type: type of object being voted *}
{* $object: object being voted (should have id and score fields) *}

{$position=$position|default:'center'}
{$voteValue=$object->getVote()->value|default:0}

{if $position == 'before'}
  <div
    id="score-{$type}-{$object->id}"
    class="voteLeft">
    {$object->getScore()|nf}
  </div>
{/if}

<div class="voteLeft col-md-1">

  <button
    class="btn btn-vote {if $voteValue == 1}voted{/if}"
    {if !User::getActive()}
    disabled
    title="{t}label-log-in-vote{/t}"
    {elseif !User::may($upvotePriv)}
    disabled
    title="{t 1=$upvotePriv|nf}label-minimum-reputation-upvote{/t}"
    {/if}
    data-type="{$type}"
    data-object-id="{$object->id}"
    data-score-id="#score-{$type}-{$object->id}"
    data-value="1">
    <i class="icon icon-up-open"></i>
  </button>

  {if $position == 'center'}
    <div id="score-{$type}-{$object->id}">{$object->getScore()|nf}</div>
  {/if}

  <button
    class="btn btn-vote {if $voteValue == -1}voted{/if}"
    {if !User::getActive()}
    disabled
    title="{t}label-log-in-vote{/t}"
    {elseif !User::may($downvotePriv)}
    disabled
    title="{t 1=$downvotePriv|nf}label-minimum-reputation-downvote{/t}"
    {/if}
    data-type="{$type}"
    data-object-id="{$object->id}"
    data-score-id="#score-{$type}-{$object->id}"
    data-value="-1">
    <i class="icon icon-down-open"></i>
  </button>

  {**
    * Show the "proof" icon for answers in two situations:
    * 1. Answer is accepted as proof (read-only).
    * 2. User is moderator and can accept/unaccept answers as proof (clickable).
    **}
  {$isAnswer=($object->getObjectType() == Proto::TYPE_ANSWER)}
  {if $isAnswer && (User::isModerator() || $object->proof)}
    <button
      class="btn btn-proof {if $object->proof}accepted{/if}"
      data-answer-id="{$object->id}"
      {if User::isModerator()}
      title="{t}label-toggle-answer-proof{/t}"
      {else}
      disabled
      title="{t}label-answer-is-proof{/t}"
      {/if}
    >
      <i class="icon icon-ok"></i>
    </button>
  {/if}

</div>
