{* $type: type of object being voted *}
{* $object: object being voted (should have id and score fields) *}

{$voteValue=$object->getVote()->value|default:0}

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

  <div id="score-{$type}-{$object->id}">{$object->getScore()|nf}</div>

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

</div>
