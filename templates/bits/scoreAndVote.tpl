{* $type: type of object being voted *}
{* $object: object being voted (should have id and score fields) *}

{$position=$position|default:'center'}
{$voteValue=$object->getVote()->value|default:0}

{if $position == 'before'}
  <div
    id="score-{$type}-{$object->id}"
    class="voteLeft">
    {$object->score}
  </div>
{/if}

<div class="voteLeft col-md-1">

  <button
    class="btn btn-vote {if $voteValue == 1}voted{/if}"
    {if !User::getActive()}
    disabled
    title="{t}Please log in to vote.{/t}"
    {elseif !User::may($upvotePriv)}
    disabled
    title="{t 1=$upvotePriv|nf}You need at least %1 reputation to upvote.{/t}"
    {/if}
    data-type="{$type}"
    data-object-id="{$object->id}"
    data-score-id="#score-{$type}-{$object->id}"
    data-value="1">
    <i class="icon icon-up-open"></i>
  </button>

  {if $position == 'center'}
    <div id="score-{$type}-{$object->id}">{$object->score}</div>
  {/if}

  <button
    class="btn btn-vote {if $voteValue == -1}voted{/if}"
    {if !User::getActive()}
    disabled
    title="{t}Please log in to vote.{/t}"
    {elseif !User::may($downvotePriv)}
    disabled
    title="{t 1=$downvotePriv|nf}You need at least %1 reputation to downvote.{/t}"
    {/if}
    data-type="{$type}"
    data-object-id="{$object->id}"
    data-score-id="#score-{$type}-{$object->id}"
    data-value="-1">
    <i class="icon icon-down-open"></i>
  </button>

</div>
