{* $type: type of object being voted *}
{* $object: object being voted (should have id and score fields) *}
{* $classes: additional CSS classes for the wrapper div *}

{$voteValue=$object->getVote()->value|default:0}

<div class="scoreAndVote {$classes|default:''}">
  <button
    class="btn btn-sm btn-light voteButton {if $voteValue == 1}voted{/if}"
    {if !User::may($upvotePriv)}
    disabled
    title="{t 1=$upvotePriv}You need at least %1 reputation to upvote.{/t}"
    {/if}
    data-type="{$type}"
    data-object-id="{$object->id}"
    data-value="1">
    <i class="icon icon-thumbs-up-alt"></i>
  </button>

  {t}score{/t}: <span class="score">{$object->score}</span>

  <button
    class="btn btn-sm btn-light voteButton {if $voteValue == -1}voted{/if}"
    {if !User::may($downvotePriv)}
    disabled
    title="{t 1=$downvotePriv}You need at least %1 reputation to downvote.{/t}"
    {/if}
    data-type="{$type}"
    data-object-id="{$object->id}"
    data-value="-1">
    <i class="icon icon-thumbs-down-alt"></i>
  </button>

</div>
